<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CertificateTemplate;
use App\Models\TemplateCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TemplatesController extends Controller
{
    /**
     * Создание нового экземпляра контроллера.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Отображение списка шаблонов.
     */
    public function index()
    {
        $templates = CertificateTemplate::paginate(12);
        return view('admin.templates.index', compact('templates'));
    }

    /**
     * Показать форму для создания шаблона.
     */
    public function create()
    {
        // Получаем список категорий шаблонов
        $categories = TemplateCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
            
        // Получаем список доступных файлов шаблонов
        $templateFiles = $this->getAvailableTemplateFiles();
        
        return view('admin.templates.create', compact('templateFiles', 'categories'));
    }

    /**
     * Сохранить новый шаблон.
     */
    public function store(Request $request)
    {
        // Добавляем отладочную информацию (можно убрать после отладки)
        Log::info('Template store request data:', $request->all());
        
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:template_categories,id'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:7168'],
            'template_path' => ['required', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        try {
            // Проверяем, существует ли директория сохранения изображений
            $templateImagePath = storage_path('app/public/templates');
            if (!is_dir($templateImagePath)) {
                if (!mkdir($templateImagePath, 0755, true)) {
                    throw new \Exception('Не удалось создать директорию для сохранения изображений');
                }
            }

            // Получаем содержимое HTML файла для поля html_template
            $templatePath = public_path($validatedData['template_path']);
            $htmlTemplate = '';
            
            if (file_exists($templatePath)) {
                $htmlTemplate = file_get_contents($templatePath);
            } else {
                throw new \Exception('Файл шаблона не найден: ' . $validatedData['template_path']);
            }

            $data = [
                'name' => $validatedData['name'],
                'category_id' => $validatedData['category_id'],
                'description' => $validatedData['description'] ?? null,
                'template_path' => $validatedData['template_path'],
                'html_template' => $htmlTemplate, // Добавляем содержимое HTML файла
                'is_premium' => $request->has('is_premium'),
                'is_active' => $request->has('is_active'),
                'sort_order' => $validatedData['sort_order'] ?? 0,
            ];
            
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('templates', 'public');
                $data['image'] = $path;
            }

            $template = CertificateTemplate::create($data);
            
            if (!$template) {
                throw new \Exception('Не удалось сохранить шаблон в базе данных');
            }

            return redirect()->route('admin.templates.index')
                ->with('success', 'Шаблон сертификата успешно создан.');
        } catch (\Exception $e) {
            Log::error('Error creating certificate template: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Произошла ошибка при создании шаблона: ' . $e->getMessage());
        }
    }

    /**
     * Показать форму для редактирования шаблона.
     */
    public function edit(CertificateTemplate $template)
    {
        // Получаем список категорий шаблонов
        $categories = TemplateCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
            
        // Получаем список доступных файлов шаблонов с поддержкой категорий
        $templateFiles = $this->getAvailableTemplateFiles();
        
        return view('admin.templates.edit', compact('template', 'templateFiles', 'categories'));
    }

    /**
     * Обновить шаблон.
     */
    public function update(Request $request, CertificateTemplate $template)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:template_categories,id'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:7168'],
            'template_path' => ['required', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        try {
            // Логируем полученные данные для отладки
            Log::info('Template update request data:', $request->all());

            // Получаем содержимое HTML файла для поля html_template
            $templatePath = public_path($validatedData['template_path']);
            $htmlTemplate = '';
            
            if (file_exists($templatePath)) {
                $htmlTemplate = file_get_contents($templatePath);
            } else {
                throw new \Exception('Файл шаблона не найден: ' . $validatedData['template_path']);
            }

            // Подготовка данных для обновления
            $data = [
                'name' => $validatedData['name'],
                'category_id' => $validatedData['category_id'],
                'description' => $validatedData['description'] ?? null,
                'template_path' => $validatedData['template_path'],
                'html_template' => $htmlTemplate,
                'is_premium' => $request->has('is_premium') ? 1 : 0,
                'is_active' => $request->has('is_active') ? 1 : 0,
                'sort_order' => $validatedData['sort_order'] ?? 0,
            ];
            
            // Логируем данные после обработки
            Log::info('Template update processed data:', $data);
            
            if ($request->hasFile('image')) {
                if ($template->image) {
                    Storage::disk('public')->delete($template->image);
                }
                
                $path = $request->file('image')->store('templates', 'public');
                $data['image'] = $path;
            }

            // Явное обновление каждого поля для надежности
            $template->name = $data['name'];
            $template->category_id = $data['category_id'];
            $template->description = $data['description'];
            $template->template_path = $data['template_path'];
            $template->html_template = $data['html_template'];
            $template->is_premium = $data['is_premium'];
            $template->is_active = $data['is_active'];
            $template->sort_order = $data['sort_order'];
            
            if (isset($data['image'])) {
                $template->image = $data['image'];
            }
            
            $template->save();
            
            // Логируем результат обновления
            Log::info('Template updated successfully. ID: ' . $template->id);

            return redirect()->route('admin.templates.index')
                ->with('success', 'Шаблон сертификата успешно обновлен.');
        } catch (\Exception $e) {
            Log::error('Error updating certificate template: ' . $e->getMessage(), [
                'template_id' => $template->id,
                'exception' => $e
            ]);
            return back()->withInput()->with('error', 'Произошла ошибка при обновлении шаблона: ' . $e->getMessage());
        }
    }

    /**
     * Переключение статуса активности шаблона.
     */
    public function toggleActive(Request $request, CertificateTemplate $template)
    {
        $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $template->update([
            'is_active' => $request->is_active,
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Удалить шаблон.
     */
    public function destroy(CertificateTemplate $template)
    {
        // Удаляем изображение, если оно есть
        if ($template->image) {
            Storage::disk('public')->delete($template->image);
        }
        
        $template->delete();
        
        return redirect()->route('admin.templates.index')
            ->with('success', 'Шаблон сертификата успешно удален.');
    }

    /**
     * Получить список доступных файлов шаблонов с поддержкой категорий
     */
    private function getAvailableTemplateFiles()
    {
        // Получаем все категории из базы данных
        $categories = TemplateCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        
        $files = [];
        
        foreach ($categories as $category) {
            $categoryPath = public_path('templates/' . $category->directory_name);
            
            if (is_dir($categoryPath)) {
                $htmlFiles = glob($categoryPath . '/*.html');
                
                foreach ($htmlFiles as $file) {
                    $relativePath = 'templates/' . $category->directory_name . '/' . basename($file);
                    $name = basename($file, '.html');
                    $name = Str::title(str_replace('-', ' ', $name));
                    $files[$category->id][$relativePath] = $name;
                }
            }
        }
        
        return [
            'files' => $files,
            'categories' => $categories
        ];
    }
}
