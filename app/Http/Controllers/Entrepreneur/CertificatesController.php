<?php

namespace App\Http\Controllers\Entrepreneur;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CertificatesController extends Controller
{
    /**
     * Создание нового экземпляра контроллера.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:predprinimatel,admin']);
    }

    /**
     * Отображение списка сертификатов.
     */
    public function index(Request $request)
    {
        $query = Auth::user()->certificates()
            ->with('template');
        
        // Фильтр по статусу
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Поиск по номеру или получателю
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('certificate_number', 'like', "%{$search}%")
                  ->orWhere('recipient_name', 'like', "%{$search}%");
            });
        }
        
        // Получение результатов с пагинацией и сохранение параметров запроса
        $certificates = $query->latest()->paginate(10)->withQueryString();
        
        // Дополнительная информация для статистики
        $activeCount = Auth::user()->certificates()->where('status', 'active')->count();
        $totalAmount = Auth::user()->certificates()->sum('amount');
        
        return view('entrepreneur.certificates.index', compact('certificates', 'activeCount', 'totalAmount'));
    }

    /**
     * Отображение формы выбора шаблона для создания сертификата.
     */
    public function selectTemplate()
    {
        $templates = CertificateTemplate::where('is_active', true)
            ->when(!Auth::user()->hasRole('admin'), function ($query) {
                return $query->where('is_premium', false);
            })
            ->get();
            
        return view('entrepreneur.certificates.select-template', compact('templates'));
    }

    /**
     * Показать форму для создания сертификата на основе выбранного шаблона.
     */
    public function create(CertificateTemplate $template)
    {
        // Проверка доступа к премиум-шаблонам
        if ($template->is_premium && !Auth::user()->hasRole('admin')) {
            return redirect()->route('entrepreneur.certificates.select-template')
                ->with('error', 'Доступ к премиум шаблонам ограничен.');
        }
        
        return view('entrepreneur.certificates.create', compact('template'));
    }

    /**
     * Сохранить новый сертификат.
     */
    public function store(Request $request, CertificateTemplate $template)
    {
        $request->validate([
            'recipient_name' => ['required', 'string', 'max:255'],
            'recipient_email' => ['nullable', 'email', 'max:255'],
            'recipient_phone' => ['nullable', 'string', 'max:20'],
            'amount' => ['required', 'numeric', 'min:0'],
            'message' => ['nullable', 'string'],
            'valid_from' => ['required', 'date', 'after_or_equal:today'],
            'valid_until' => ['required', 'date', 'after:valid_from'],
            'custom_fields' => ['nullable', 'array'],
        ]);

        // Генерация уникального номера сертификата
        $certificateNumber = $this->generateCertificateNumber();

        $certificate = new Certificate([
            'certificate_number' => $certificateNumber,
            'recipient_name' => $request->recipient_name,
            'recipient_email' => $request->recipient_email,
            'recipient_phone' => $request->recipient_phone,
            'amount' => $request->amount,
            'message' => $request->message,
            'valid_from' => Carbon::parse($request->valid_from),
            'valid_until' => Carbon::parse($request->valid_until),
            'custom_fields' => $request->custom_fields,
            'status' => 'active',
        ]);

        $certificate->user()->associate(Auth::user());
        $certificate->template()->associate($template);
        $certificate->save();

        return redirect()->route('entrepreneur.certificates.index')
            ->with('success', 'Сертификат успешно создан.');
    }

    /**
     * Показать детали сертификата.
     */
    public function show(Certificate $certificate)
    {
        $this->authorize('view', $certificate);
        
        return view('entrepreneur.certificates.show', compact('certificate'));
    }

    /**
     * Показать форму для редактирования сертификата.
     */
    public function edit(Certificate $certificate)
    {
        $this->authorize('update', $certificate);
        
        $template = $certificate->template;
        
        return view('entrepreneur.certificates.edit', compact('certificate', 'template'));
    }

    /**
     * Обновить сертификат.
     */
    public function update(Request $request, Certificate $certificate)
    {
        $this->authorize('update', $certificate);
        
        $request->validate([
            'recipient_name' => ['required', 'string', 'max:255'],
            'recipient_email' => ['nullable', 'email', 'max:255'],
            'recipient_phone' => ['nullable', 'string', 'max:20'],
            'amount' => ['required', 'numeric', 'min:0'],
            'message' => ['nullable', 'string'],
            'valid_from' => ['required', 'date'],
            'valid_until' => ['required', 'date', 'after:valid_from'],
            'status' => ['required', 'in:active,expired,canceled'],
            'custom_fields' => ['nullable', 'array'],
        ]);

        $certificate->update([
            'recipient_name' => $request->recipient_name,
            'recipient_email' => $request->recipient_email,
            'recipient_phone' => $request->recipient_phone,
            'amount' => $request->amount,
            'message' => $request->message,
            'valid_from' => Carbon::parse($request->valid_from),
            'valid_until' => Carbon::parse($request->valid_until),
            'status' => $request->status,
            'custom_fields' => $request->custom_fields,
        ]);

        return redirect()->route('entrepreneur.certificates.index')
            ->with('success', 'Сертификат успешно обновлен.');
    }

    /**
     * Удалить сертификат (или отметить как отмененный).
     */
    public function destroy(Certificate $certificate)
    {
        $this->authorize('delete', $certificate);
        
        // Вместо фактического удаления меняем статус на "отменен"
        $certificate->update(['status' => 'canceled']);
        
        return redirect()->route('entrepreneur.certificates.index')
            ->with('success', 'Сертификат успешно отменен.');
    }

    /**
     * Генерация уникального номера сертификата.
     */
    private function generateCertificateNumber()
    {
        do {
            $number = strtoupper(Auth::id() . '-' . Str::random(8));
        } while (Certificate::where('certificate_number', $number)->exists());
        
        return $number;
    }

    /**
     * Отправить сертификат по электронной почте.
     */
    public function sendEmail(Request $request, Certificate $certificate)
    {
        $this->authorize('view', $certificate);
        
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'message' => ['nullable', 'string'],
        ]);
        
        // В реальном приложении здесь будет код для отправки email с сертификатом
        // Mail::to($request->email)->send(new CertificateEmail($certificate, $request->message));
        
        // Заглушка для демонстрации
        return back()->with('success', 'Сертификат был отправлен на адрес ' . $request->email);
    }
}
