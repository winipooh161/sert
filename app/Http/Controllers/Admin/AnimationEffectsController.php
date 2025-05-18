<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnimationEffect;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AnimationEffectsController extends Controller
{
    /**
     * Создание нового экземпляра контроллера.
     */
    public function __construct()
    {
        // Исключаем метод getEffects из проверки авторизации
        $this->middleware(['auth', 'role:admin'])->except(['getEffects']);
    }

    /**
     * Отображение списка анимационных эффектов.
     */
    public function index()
    {
        $animationEffects = AnimationEffect::orderBy('sort_order')->paginate(10);
        return view('admin.animation-effects.index', compact('animationEffects'));
    }

    /**
     * Показать форму для создания анимационного эффекта.
     */
    public function create()
    {
        return view('admin.animation-effects.create');
    }

    /**
     * Сохранить новый анимационный эффект.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'particles' => 'required|string',
            'description' => 'nullable|string',
            'direction' => 'required|string',
            'speed' => 'required|string',
            'color' => 'nullable|string',
            'size_min' => 'required|integer|min:8',
            'size_max' => 'required|integer|min:8',
            'quantity' => 'required|integer|min:10',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'sometimes|boolean'
        ]);

        $validatedData['slug'] = Str::slug($request->name);
        $validatedData['is_active'] = $request->has('is_active') ? true : false;
        $validatedData['particles'] = explode(',', $request->particles);

        AnimationEffect::create($validatedData);

        return redirect()->route('admin.animation-effects.index')
            ->with('success', 'Анимационный эффект успешно создан');
    }

    /**
     * Показать форму для редактирования анимационного эффекта.
     */
    public function edit(AnimationEffect $animationEffect)
    {
        return view('admin.animation-effects.edit', compact('animationEffect'));
    }

    /**
     * Обновить анимационный эффект.
     */
    public function update(Request $request, AnimationEffect $animationEffect)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'particles' => 'required|string',
            'description' => 'nullable|string',
            'direction' => 'required|string',
            'speed' => 'required|string',
            'color' => 'nullable|string',
            'size_min' => 'required|integer|min:8',
            'size_max' => 'required|integer|min:8',
            'quantity' => 'required|integer|min:10',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'sometimes|boolean'
        ]);

        $validatedData['is_active'] = $request->has('is_active') ? true : false;
        $validatedData['particles'] = explode(',', $request->particles);

        $animationEffect->update($validatedData);

        return redirect()->route('admin.animation-effects.index')
            ->with('success', 'Анимационный эффект успешно обновлен');
    }

    /**
     * Удалить эффект.
     */
    public function destroy(AnimationEffect $animationEffect)
    {
        $animationEffect->delete();
        
        return redirect()->route('admin.animation-effects.index')
            ->with('success', 'Анимационный эффект успешно удален');
    }

    /**
     * Изменить статус (активировать/деактивировать) эффекта.
     */
    public function toggleStatus(AnimationEffect $animationEffect)
    {
        $animationEffect->is_active = !$animationEffect->is_active;
        $animationEffect->save();
        
        return redirect()->route('admin.animation-effects.index')
            ->with('success', 'Статус эффекта успешно изменен');
    }

    /**
     * Предварительный просмотр анимационного эффекта.
     */
    public function preview(AnimationEffect $animationEffect)
    {
        return view('admin.animation-effects.preview', compact('animationEffect'));
    }
    
    /**
     * Возвращает список анимационных эффектов в формате JSON.
     * Этот метод доступен публично и не требует авторизации.
     */
    public function getEffects()
    {
        try {
            // Пробуем получить эффекты из базы
            $effects = AnimationEffect::where('is_active', true)
                ->orderBy('sort_order')
                ->get(['id', 'name', 'slug', 'type', 'description', 'particles', 'direction', 'speed']);
                
            // Если эффектов нет, возвращаем демонстрационные
            if ($effects->isEmpty()) {
                $effects = AnimationEffect::getDefaultEffects();
            }
            
            // Обрабатываем каждый эффект для обеспечения корректности данных
            $processedEffects = $effects->map(function($effect) {
                // Проверяем наличие всех необходимых полей
                return [
                    'id' => $effect['id'] ?? 1,
                    'name' => $effect['name'] ?? 'Эффект',
                    'slug' => $effect['slug'] ?? 'effect',
                    'description' => $effect['description'] ?? 'Описание эффекта',
                    'type' => $effect['type'] ?? 'confetti',
                    'particles' => is_array($effect['particles']) ? $effect['particles'] : ['✨', '🎉', '🎊'],
                    'direction' => $effect['direction'] ?? 'center',
                    'speed' => $effect['speed'] ?? 'normal',
                ];
            });
            
            return response()->json($processedEffects);
        } catch (\Exception $e) {
            // В случае любой ошибки возвращаем демонстрационные эффекты
            $fallbackEffects = [
                [
                    'id' => 1,
                    'name' => 'Конфетти',
                    'slug' => 'confetti',
                    'description' => 'Красочные частицы конфетти',
                    'type' => 'confetti',
                    'particles' => ['🎉', '🎊', '✨', '🎁', '💫'],
                    'direction' => 'center',
                    'speed' => 'normal',
                ],
                [
                    'id' => 2,
                    'name' => 'Фейерверк',
                    'slug' => 'fireworks',
                    'description' => 'Яркие вспышки фейерверка',
                    'type' => 'fireworks',
                    'particles' => ['💥', '🎆', '✨'],
                    'direction' => 'center',
                    'speed' => 'fast',
                ],
                [
                    'id' => 3,
                    'name' => 'Снег',
                    'slug' => 'snow',
                    'description' => 'Падающие снежинки',
                    'type' => 'snow',
                    'particles' => ['❄️', '❄', '❅', '❆'],
                    'direction' => 'bottom',
                    'speed' => 'slow',
                ]
            ];
            
            return response()->json($fallbackEffects);
        }
    }
}
