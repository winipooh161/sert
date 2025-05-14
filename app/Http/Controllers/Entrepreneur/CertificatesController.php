<?php

namespace App\Http\Controllers\Entrepreneur;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\TemplateCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
            ->with('category') // Добавляем связь с категорией
            ->when(!Auth::user()->hasRole('admin'), function ($query) {
                return $query->where('is_premium', false);
            })
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
        $request->validate([
            'recipient_name' => ['required', 'string', 'max:255'],
            'recipient_email' => ['nullable', 'email', 'max:255'],
            'recipient_phone' => ['nullable', 'string', 'max:20'],
            'amount' => ['required', 'numeric', 'min:0'],
            'message' => ['nullable', 'string'],
            'valid_from' => ['required', 'date', 'after_or_equal:today'],
            'valid_until' => ['required', 'date', 'after:valid_from'],
            'custom_fields' => ['nullable', 'array'],
            'logo_type' => ['required', 'in:default,custom,none'],
            'custom_logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        // Генерация уникального номера сертификата
        $certificateNumber = $this->generateCertificateNumber();

        // Обработка логотипа
        $companyLogo = null;
        if ($request->logo_type === 'default') {
            // Используем логотип из профиля пользователя
            $companyLogo = Auth::user()->company_logo;
        } elseif ($request->logo_type === 'custom' && $request->hasFile('custom_logo')) {
            // Сохраняем загруженный логотип
            $companyLogo = $request->file('custom_logo')->store('certificates/logos', 'public');
        }
        // Если logo_type === 'none', оставляем $companyLogo = null

        $certificate = new Certificate([
            'certificate_number' => $certificateNumber,
            'recipient_name' => $request->recipient_name,
            'recipient_email' => $request->recipient_email,
            'recipient_phone' => $request->recipient_phone,
            'amount' => $request->amount,
            'message' => $request->message,
            'company_logo' => $companyLogo, // Сохраняем путь к логотипу
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
            'logo_type' => ['nullable', 'in:default,custom,current,none'],
            'custom_logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $data = [
            'recipient_name' => $request->recipient_name,
            'recipient_email' => $request->recipient_email,
            'recipient_phone' => $request->recipient_phone,
            'amount' => $request->amount,
            'message' => $request->message,
            'valid_from' => Carbon::parse($request->valid_from),
            'valid_until' => Carbon::parse($request->valid_until),
            'status' => $request->status,
            'custom_fields' => $request->custom_fields,
        ];
        
        // Обработка логотипа при редактировании
        if ($request->has('logo_type')) {
            if ($request->logo_type === 'default') {
                // Используем логотип из профиля пользователя
                $data['company_logo'] = Auth::user()->company_logo;
            } elseif ($request->logo_type === 'custom' && $request->hasFile('custom_logo')) {
                // Удаляем старый логотип, если он был загружен специально для этого сертификата
                if ($certificate->company_logo && Str::startsWith($certificate->company_logo, 'certificates/logos/')) {
                    Storage::disk('public')->delete($certificate->company_logo);
                }
                
                // Сохраняем новый загруженный логотип
                $data['company_logo'] = $request->file('custom_logo')->store('certificates/logos', 'public');
            } elseif ($request->logo_type === 'none') {
                // Устанавливаем логотип в null, если выбрано "Не использовать логотип"
                $data['company_logo'] = null;
            }
            // Если logo_type === 'current', то оставляем текущий логотип без изменений
        }

        $certificate->update($data);

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
     * Временно сохраняет логотип для предпросмотра.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function tempLogo(Request $request)
    {
        try {
            if (!$request->hasFile('logo')) {
                return response()->json(['success' => false, 'error' => 'Файл не найден']);
            }

            $file = $request->file('logo');
            // Валидация файла
            if (!$file->isValid() || 
                !in_array($file->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
                return response()->json(['success' => false, 'error' => 'Недопустимый формат файла']);
            }

            // Проверяем размер файла (макс. 2MB)
            $maxSize = 2 * 1024 * 1024;
            if ($file->getSize() > $maxSize) {
                return response()->json(['success' => false, 'error' => 'Размер файла не должен превышать 2MB']);
            }

            // Создаем директорию для временных файлов, если она не существует
            $tempDir = storage_path('app/public/temp/logos');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            // Сохраняем файл во временной директории с уникальным именем
            $fileName = uniqid('logo_') . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('temp/logos', $fileName, 'public');
            
            // Устанавливаем права доступа (для публичного доступа)
            chmod(storage_path('app/public/' . $path), 0644);
            
            // Оптимизируем логотип если это изображение слишком большое
            try {
                if (extension_loaded('gd') && $file->getSize() > 500 * 1024) {
                    $imagePath = storage_path('app/public/' . $path);
                    $image = imagecreatefromstring(file_get_contents($imagePath));
                    
                    if ($image !== false) {
                        // Получаем текущие размеры
                        $width = imagesx($image);
                        $height = imagesy($image);
                        
                        // Если изображение слишком большое, уменьшаем его
                        if ($width > 500 || $height > 500) {
                            // Вычисляем новые размеры с сохранением пропорций
                            if ($width > $height) {
                                $newWidth = 500;
                                $newHeight = intval($height * ($newWidth / $width));
                            } else {
                                $newHeight = 500;
                                $newWidth = intval($width * ($newHeight / $height));
                            }
                            
                            // Создаем новое изображение
                            $newImage = imagecreatetruecolor($newWidth, $newHeight);
                            
                            // Для PNG сохраняем прозрачность
                            if ($file->getClientOriginalExtension() == 'png') {
                                imagealphablending($newImage, false);
                                imagesavealpha($newImage, true);
                                $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                                imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
                            }
                            
                            // Изменяем размер
                            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                            
                            // Сохраняем изображение
                            if ($file->getClientOriginalExtension() == 'png') {
                                imagepng($newImage, $imagePath);
                            } else {
                                imagejpeg($newImage, $imagePath, 85);
                            }
                            
                            // Освобождаем память
                            imagedestroy($newImage);
                        }
                        imagedestroy($image);
                    }
                }
            } catch (\Exception $e) {
                // Неудача при оптимизации не должна прерывать работу
                \Log::warning('Ошибка при оптимизации логотипа: ' . $e->getMessage());
            }
            
            // Формируем полный URL к файлу с временной меткой для предотвращения кеширования
            $url = asset('storage/' . $path) . '?t=' . time();

            return response()->json([
                'success' => true, 
                'logo_url' => $url,
                'file_name' => $fileName,
                'path' => $path
            ]);
        } catch (\Exception $e) {
            \Log::error('Ошибка при обработке временного логотипа: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'error' => 'Ошибка при обработке изображения: ' . $e->getMessage()
            ]);
        }
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
}
