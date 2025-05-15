<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;

class RoleSwitcherController extends Controller
{
    /**
     * Обработка запроса на переключение роли пользователя.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function switchRole(Request $request)
    {
        $request->validate([
            'role' => 'required|string|in:predprinimatel,user',
        ]);

        $requestedRole = $request->role;
        $user = Auth::user();
        
        // Проверяем, что у пользователя есть роль для переключения
        // или это администратор (у которого есть доступ ко всем ролям)
        if ($user->hasRole($requestedRole) || $user->hasRole('admin')) {
            // Устанавливаем активную роль в сессии
            session(['active_role' => $requestedRole]);
            
            // Перенаправляем на соответствующую домашнюю страницу роли
            if ($requestedRole == 'predprinimatel') {
                return redirect()->route('entrepreneur.certificates.index');
            } elseif ($requestedRole == 'user') {
                return redirect()->route('user.certificates.index');
            }
        } else {
            // Если у пользователя нет запрошенной роли, добавляем ее автоматически
            $role = Role::where('slug', $requestedRole)->first();
            
            if ($role) {
                // Добавляем новую роль пользователю
                $user->roles()->attach($role->id);
                
                // Устанавливаем активную роль в сессии
                session(['active_role' => $requestedRole]);
                
                // Перенаправляем на соответствующую домашнюю страницу
                if ($requestedRole == 'predprinimatel') {
                    return redirect()->route('entrepreneur.certificates.index')
                        ->with('success', 'Вам добавлена роль предпринимателя. Теперь вы можете создавать и управлять сертификатами.');
                } elseif ($requestedRole == 'user') {
                    return redirect()->route('user.certificates.index')
                        ->with('success', 'Вам добавлена роль пользователя. Теперь вы можете просматривать полученные сертификаты.');
                }
            }
            
            // Если роль не найдена
            return back()->with('error', 'Запрашиваемая роль не существует в системе');
        }
        
        // Если переключение не произошло, возвращаемся к предыдущей странице
        return back()->with('error', 'Произошла ошибка при переключении роли');
    }
}
