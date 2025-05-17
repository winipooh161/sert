<?php

namespace App\Http\Controllers;

use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    protected $imageService;
    
    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }
    
    /**
     * Показать страницу профиля пользователя.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    /**
     * Обновить общую информацию о пользователе.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'company' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|max:2048',
            'company_logo' => 'nullable|image|max:2048',
        ]);
        
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->company = $validated['company'] ?? null;
        
        // Обработка аватара с использованием сервиса сжатия изображений
        if ($request->hasFile('avatar')) {
            // Удаляем старый аватар, если он существует
            if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
                Storage::delete('public/' . $user->avatar);
            }
            
            // Сжимаем и сохраняем новый аватар
            $user->avatar = $this->imageService->createAvatar($request->file('avatar'), 'avatars');
        }
        
        // Обработка логотипа компании
        if ($request->hasFile('company_logo')) {
            // Удаляем старый логотип, если он существует
            if ($user->company_logo && Storage::exists('public/' . $user->company_logo)) {
                Storage::delete('public/' . $user->company_logo);
            }
            
            // Сжимаем и сохраняем новый логотип
            $user->company_logo = $this->imageService->createLogo($request->file('company_logo'), 'company_logos');
        }
        
        $user->save();
        
        return back()->with('success', 'Профиль успешно обновлен.');
    }

    /**
     * Обновить пароль пользователя.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function password(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('Текущий пароль указан неверно.');
                }
            }],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        
        $user->password = Hash::make($request->password);
        $user->save();
        
        return back()->with('success', 'Пароль успешно изменен.');
    }

    /**
     * Обновить настройки уведомлений.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function notifications(Request $request)
    {
        $user = Auth::user();
        
        // Получаем настройки уведомлений из формы
        $notificationPreferences = $request->input('notification_preferences', []);
        
        // Сохраняем настройки уведомлений в JSON формате
        $user->notification_preferences = json_encode($notificationPreferences);
        $user->save();
        
        return back()->with('success', 'Настройки уведомлений обновлены.');
    }

    /**
     * Показать страницу настроек пользователя.
     *
     * @return \Illuminate\View\View
     */
    public function settings()
    {
        $user = Auth::user();
        return view('profile.settings', compact('user'));
    }
}
