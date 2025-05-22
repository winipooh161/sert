<?php

namespace App\Http\Controllers\Entrepreneur;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\TemplateCategory;
use App\Services\ImageService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificatesController extends Controller
{
    protected $imageService;
    
    /**
     * Создание нового экземпляра контроллера.
     */
    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
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
            ->with('category') // Добавляем связь с категорией
            ->when(!Auth::user()->hasRole('admin'), function ($query) {
                return $query->where('is_premium', false);
            })
            ->orderBy('created_at', 'desc') // Добавляем сортировку: новые вверху
            ->get();
            
        $categories = TemplateCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
            
        return view('entrepreneur.certificates.select-template', compact('templates', 'categories'));
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
        // Валидация запроса
        $validated = $request->validate([
            'amount' => 'required_if:amount_type,money|integer|min:1',
            'percent_value' => 'required_if:amount_type,percent|integer|min:1|max:100',
            'amount_type' => 'required|in:money,percent',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:255',
            'recipient_email' => 'nullable|email|max:255',
            'message' => 'nullable|string|max:1000',
            'temp_cover_path' => 'required|string', // Теперь это поле обязательно
            'animation_effect_id' => 'nullable|integer|exists:animation_effects,id',
            'logo_type' => 'required|in:default,custom,none',
            'custom_logo' => 'required_if:logo_type,custom|nullable|image|max:5120',
        ]);
        
        // Генерация уникального номера сертификата перед созданием
        $certificateNumber = $this->generateCertificateNumber();
        
        // Подготавливаем данные для создания сертификата
        $certificateData = [
            'user_id' => Auth::id(),
            'certificate_template_id' => $template->id, // Добавляем ID шаблона
            'certificate_number' => $certificateNumber,
            'amount' => $request->amount_type === 'money' ? $request->amount : $request->percent_value,
            'is_percent' => $request->amount_type === 'percent',
            'recipient_name' => $request->recipient_name,
            'recipient_email' => $request->recipient_email,
            'recipient_phone' => $request->recipient_phone,
            'message' => $request->message,
            'valid_from' => Carbon::parse($request->valid_from),
            'valid_until' => Carbon::parse($request->valid_until),
            'custom_fields' => $request->custom_fields,
            'status' => 'active',
            'animation_effect_id' => $request->input('animation_effect_id'),
        ];
        
        // Создаем сертификат
        $certificate = Certificate::create($certificateData);
        
        // Обработка логотипа
        if ($request->logo_type === 'default') {
            // Используем логотип по умолчанию
            $certificate->company_logo = Auth::user()->company_logo;
        } elseif ($request->logo_type === 'custom' && $request->hasFile('custom_logo')) {
            // Обрабатываем загрузку пользовательского логотипа
            $certificate->company_logo = $this->imageService->createLogo($request->file('custom_logo'), 'company_logos');
        } else {
            $certificate->company_logo = null; // Если логотип не нужен
        }
        
        // Обработка изображения обложки - только из фоторедактора
        if ($request->has('temp_cover_path') && $request->temp_cover_path) {
            // Используем временное изображение из фоторедактора
            $tempPath = $request->temp_cover_path;
            $finalPath = 'certificates/covers/' . basename($tempPath);
            
            // Проверка существования файла
            if (!Storage::disk('public')->exists($tempPath)) {
                return back()->withInput()->withErrors([
                    'temp_cover_path' => 'Временное изображение не найдено. Пожалуйста, создайте изображение в фоторедакторе.'
                ]);
            }
            
            // Копируем файл в постоянное хранилище
            Storage::disk('public')->copy($tempPath, $finalPath);
            $certificate->cover_image = $finalPath;
            
            // Удаляем временный файл
            Storage::disk('public')->delete($tempPath);
        } else {
            return back()->withInput()->withErrors([
                'temp_cover_path' => 'Необходимо создать изображение в фоторедакторе.'
            ]);
        }
        
        // Теперь это не требуется, так как связь уже установлена
        // $certificate->template()->associate($template);
        $certificate->save();
        
        // Удаляем данные о временном изображении из сессии
        session()->forget('temp_certificate_cover');
        
        // Перенаправление на страницу списка сертификатов
        return redirect()->route('entrepreneur.certificates.show', $certificate)
            ->with('success', 'Сертификат успешно создан!');
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
     * Обновление сертификата.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Certificate  $certificate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Certificate $certificate)
    {
        // Проверяем доступ
        $this->authorize('update', $certificate);
        
        // Исключаем поле телефона из валидации
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'recipient_email' => 'nullable|email|max:255',
            'amount' => 'required|numeric|min:1',
            'message' => 'nullable|string|max:1000',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:valid_from',
            'status' => 'required|in:active,expired,canceled',
            'custom_fields' => 'nullable|array',
            'logo_type' => 'nullable|in:current,default,none',
            'custom_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
            'animation_effect_id' => 'nullable|integer|exists:animation_effects,id', // Добавляем валидацию
        ]);
        
        // Проверяем, не пытается ли пользователь изменить телефон
        if ($request->has('recipient_phone') && $request->recipient_phone !== $certificate->recipient_phone) {
            return back()->withInput()->withErrors([
                'recipient_phone' => 'Номер телефона нельзя изменить после создания сертификата.'
            ]);
        }
        
        // Обновляем обложку, если она загружена
        if ($request->hasFile('cover_image')) {
            // Удаляем старую обложку, если она существует
            if ($certificate->cover_image && Storage::exists('public/' . $certificate->cover_image)) {
                Storage::delete('public/' . $certificate->cover_image);
            }
            
            // Сжимаем и сохраняем новую обложку
            $certificate->cover_image = $this->imageService->createCover($request->file('cover_image'), 'certificate_covers');
        }
        
        // Продолжение логики обновления сертификата...
        $certificate->recipient_name = $request->recipient_name;
        $certificate->recipient_email = $request->recipient_email;
        $certificate->amount = $request->amount;
        $certificate->message = $request->message;
        $certificate->valid_from = Carbon::parse($request->valid_from);
        $certificate->valid_until = Carbon::parse($request->valid_until);
        $certificate->custom_fields = $request->custom_fields;
        $certificate->status = $request->status;
        $certificate->animation_effect_id = $request->animation_effect_id; // Добавляем поле animation_effect_id
        
        // Обработка пользовательского логотипа
        if ($request->logo_type === 'custom' && $request->hasFile('custom_logo')) {
            $logoPath = $this->imageService->createLogo($request->file('custom_logo'), 'company_logos');
            $certificate->company_logo = $logoPath;
        }
        
        $certificate->save();

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

    /**
     * Отправляет сертификат на указанный email
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Certificate  $certificate
     * @return \Illuminate\Http\Response
     */
 
    /**
     * Временно сохраняет логотип для предпросмотра.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function tempLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|max:2048',
        ]);
        
        if ($request->hasFile('logo')) {
            // Используем сервис для сжатия и сохранения логотипа
            $logoPath = $this->imageService->createLogo($request->file('logo'), 'temp');
            
            return response()->json([
                'success' => true,
                'logo_url' => asset('storage/' . $logoPath)
            ]);
        }
        
        return response()->json([
            'success' => false,
            'error' => 'Ошибка загрузки логотипа'
        ], 400);
    }

    /**
     * Страница быстрой проверки сертификата по QR-коду.
     */
    public function adminVerify(Certificate $certificate)
    {
        $this->authorize('view', $certificate);
        
        return view('entrepreneur.certificates.admin-verify', compact('certificate'));
    }
    
    /**
     * Отметить сертификат как использованный.
     */
    public function markAsUsed(Certificate $certificate)
    {
        $this->authorize('update', $certificate);
        
        // Проверяем, что сертификат активен
        if ($certificate->status !== 'active') {
            return back()->with('error', 'Нельзя отметить сертификат как использованный. Текущий статус: ' . $certificate->status);
        }
        
        // Изменяем статус и сохраняем время использования
        $certificate->update([
            'status' => 'used',
            'used_at' => now(),
        ]);
        
        return redirect()->route('entrepreneur.certificates.admin-verify', $certificate)
            ->with('success', 'Сертификат успешно отмечен как использованный.');
    }

    /**
     * Поиск сертификатов по номеру телефона или имени получателя
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (empty($query) || strlen($query) < 3) {
            return response()->json([
                'certificates' => []
            ]);
        }
        
        // Определяем, является ли запрос телефоном или именем
        $isPhone = preg_match('/^[0-9+\-\s()]{3,}$/', $query);
        
        // Очищаем телефон от лишних символов для сравнения
        if ($isPhone) {
            $cleanedQuery = preg_replace('/[^0-9]/', '', $query);
        }
        
        // Создаем запрос к базе
        $certificates = Auth::user()->certificates()
            ->with('template')
            ->where(function ($q) use ($query, $isPhone, $cleanedQuery) {
                if ($isPhone) {
                    // Поиск по телефону с учетом разных форматов
                    $q->where('recipient_phone', 'like', '%' . $cleanedQuery . '%')
                      ->orWhere('recipient_phone', 'like', '%' . $query . '%');
                } else {
                    // Поиск по имени
                    $q->where('recipient_name', 'like', '%' . $query . '%')
                      ->orWhere('certificate_number', 'like', '%' . $query . '%');
                }
            })
            ->latest()
            ->take(20)
            ->get();
            
        // Форматируем данные для фронтенда
        $formattedCertificates = $certificates->map(function ($certificate) {
            return [
                'id' => $certificate->id,
                'certificate_number' => $certificate->certificate_number,
                'recipient_name' => $certificate->recipient_name,
                'recipient_phone' => $certificate->recipient_phone,
                'amount' => $certificate->amount,
                'status' => $certificate->status,
                'created_at' => $certificate->created_at,
                'cover_image_url' => $certificate->cover_image_url,
                'public_url' => route('certificates.public', $certificate->uuid),
                'template_name' => $certificate->template ? $certificate->template->name : 'Неизвестный шаблон'
            ];
        });
        
        return response()->json([
            'certificates' => $formattedCertificates
        ]);
    }
    
    /**
     * Ленивая загрузка дополнительных сертификатов при скролле
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadMore(Request $request)
    {
        $page = $request->get('page', 1);
        
        $certificates = Auth::user()->certificates()
            ->with('template')
            ->latest()
            ->paginate(12, ['*'], 'page', $page);
            
        // Форматируем данные для фронтенда
        $formattedCertificates = $certificates->items()->map(function ($certificate) {
            return [
                'id' => $certificate->id,
                'certificate_number' => $certificate->certificate_number,
                'recipient_name' => $certificate->recipient_name,
                'recipient_phone' => $certificate->recipient_phone,
                'amount' => $certificate->amount,
                'status' => $certificate->status,
                'created_at' => $certificate->created_at,
                'cover_image_url' => $certificate->cover_image_url,
                'public_url' => route('certificates.public', $certificate->uuid),
                'template_name' => $certificate->template ? $certificate->template->name : 'Неизвестный шаблон'
            ];
        });
        
        return response()->json([
            'certificates' => $formattedCertificates,
            'has_more_pages' => $certificates->hasMorePages(),
            'current_page' => $certificates->currentPage(),
            'last_page' => $certificates->lastPage()
        ]);
    }
}
