<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CertificateTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
        return view('admin.templates.create');
    }

    /**
     * Сохранить новый шаблон.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'html_template' => ['required', 'string'],
            'is_premium' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        try {
            $data = [
                'name' => $validatedData['name'],
                'description' => $validatedData['description'] ?? null,
                'html_template' => $validatedData['html_template'],
                'is_premium' => $request->has('is_premium') ? 1 : 0,
                'is_active' => $request->has('is_active') ? 1 : 0,
            ];
            
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('templates', 'public');
                $data['image'] = $path;
            }

            CertificateTemplate::create($data);

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
        return view('admin.templates.edit', compact('template'));
    }

    /**
     * Обновить шаблон.
     */
    public function update(Request $request, CertificateTemplate $template)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'html_template' => ['required', 'string'],
            'is_premium' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        try {
            $data = [
                'name' => $validatedData['name'],
                'description' => $validatedData['description'] ?? null,
                'html_template' => $validatedData['html_template'],
                'is_premium' => $request->has('is_premium') ? 1 : 0,
                'is_active' => $request->has('is_active') ? 1 : 0,
            ];
            
            if ($request->hasFile('image')) {
                if ($template->image) {
                    Storage::disk('public')->delete($template->image);
                }
                
                $path = $request->file('image')->store('templates', 'public');
                $data['image'] = $path;
            }

            $template->update($data);

            return redirect()->route('admin.templates.index')
                ->with('success', 'Шаблон сертификата успешно обновлен.');
        } catch (\Exception $e) {
            Log::error('Error updating certificate template: ' . $e->getMessage());
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
}
