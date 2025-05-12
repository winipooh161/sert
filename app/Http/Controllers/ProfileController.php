<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
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
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'company' => ['nullable', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);
        
        // Обновляем основные данные
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->company = $request->company;
        $user->position = $request->position;
        $user->bio = $request->bio;
        
        // Загрузка аватара
        if ($request->hasFile('avatar')) {
            // Удаляем старый аватар, если он был
            if ($user->avatar) {
                Storage::delete('public/' . $user->avatar);
            }
            
            // Сохраняем новый аватар
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
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
