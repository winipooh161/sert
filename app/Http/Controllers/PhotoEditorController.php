<?php

namespace App\Http\Controllers;

use App\Models\CertificateTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PhotoEditorController extends Controller
{
    /**
     * Показать страницу редактора фотографий
     */
    public function index(Request $request)
    {
        // Получаем ID шаблона из запроса, если он был передан
        $templateId = $request->query('template');
        
        return view('photo-editor.photo-editor', compact('templateId'));
    }

    /**
     * Сохраняет отредактированное изображение для дальнейшего использования при создании сертификата
     *
     * @param Request $request
     * @param int $templateId ID шаблона сертификата
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function saveToCertificate(Request $request, $templateId)
    {
        // Валидация запроса
        $request->validate([
            'image' => 'required|image|max:20480', // Максимальный размер 20MB
        ]);

        // Проверяем существование шаблона
        $template = CertificateTemplate::find($templateId);

        if (!$template) {
            return response()->json([
                'success' => false,
                'message' => 'Шаблон сертификата не найден'
            ], 404);
        }

        // Сохраняем изображение во временную директорию
        $imagePath = $request->file('image')->store('temp/certificates', 'public');

        // Сохраняем путь к изображению в сессии
        session(['temp_certificate_cover' => $imagePath]);

        // В зависимости от типа запроса, возвращаем JSON или редирект
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Изображение успешно сохранено',
                'redirect' => route('entrepreneur.certificates.create', $template)
            ]);
        }

        return redirect()->route('entrepreneur.certificates.create', $template)
            ->with('success', 'Изображение успешно сохранено');
    }

    /**
     * Загружает стикер, загруженный пользователем
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadSticker(Request $request)
    {
        $request->validate([
            'sticker' => 'required|image|max:2048', // Максимальный размер 2MB
        ]);

        $path = $request->file('sticker')->store('stickers', 'public');
        
        return response()->json([
            'success' => true,
            'url' => Storage::url($path)
        ]);
    }

    /**
     * Сохраняет проект для последующего редактирования
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveProject(Request $request)
    {
        $request->validate([
            'project_data' => 'required|string',
            'preview' => 'required|string'
        ]);

        $filename = 'project_' . Str::random(10) . '_' . time() . '.json';
        
        Storage::disk('public')->put('projects/' . $filename, $request->project_data);
        
        // Сохраняем превью проекта отдельно
        $previewData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->preview));
        $previewFilename = 'preview_' . pathinfo($filename, PATHINFO_FILENAME) . '.png';
        Storage::disk('public')->put('projects/previews/' . $previewFilename, $previewData);

        return response()->json([
            'success' => true,
            'filename' => $filename
        ]);
    }

    /**
     * Загружает ранее сохраненный проект
     *
     * @param string $filename
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadProject($filename)
    {
        if (!Storage::disk('public')->exists('projects/' . $filename)) {
            return response()->json([
                'success' => false,
                'message' => 'Проект не найден'
            ], 404);
        }

        $projectData = Storage::disk('public')->get('projects/' . $filename);

        return response()->json([
            'success' => true,
            'project_data' => $projectData
        ]);
    }

    /**
     * Возвращает список доступных фильтров
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFilters()
    {
        $filters = [
            'normal' => ['name' => 'Обычный', 'params' => []],
            'vintage' => ['name' => 'Винтаж', 'params' => ['brightness' => -0.1, 'contrast' => 0.1, 'sepia' => true]],
            'sepia' => ['name' => 'Сепия', 'params' => ['sepia' => true]],
            'grayscale' => ['name' => 'Ч/Б', 'params' => ['grayscale' => true]],
            'lomo' => ['name' => 'Ломо', 'params' => ['brightness' => 0.05, 'contrast' => 0.2, 'saturation' => 0.3]],
            'clarity' => ['name' => 'Четкость', 'params' => ['contrast' => 0.3, 'sharpen' => true]],
        ];

        return response()->json([
            'success' => true,
            'filters' => $filters
        ]);
    }
}
