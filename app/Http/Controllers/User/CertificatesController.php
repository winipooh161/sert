<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificatesController extends Controller
{
    /**
     * Создание нового экземпляра контроллера.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:user']);
    }

    /**
     * Отображение списка сертификатов пользователя.
     */
    public function index(Request $request)
    {
        $query = Certificate::query()
            ->with('template')
            ->where(function($q) {
                // Пользователь видит сертификаты, где он получатель (приоритет телефону)
                $user = Auth::user();
                if ($user->phone) {
                    $q->where('recipient_phone', $user->phone);
                }
                // Если есть email, добавим как альтернативный поиск
                if ($user->email) {
                    $q->orWhere('recipient_email', $user->email);
                }
            });
        
        // Фильтр по статусу
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Поиск по номеру
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('certificate_number', 'like', "%{$search}%");
        }
        
        // Получение результатов с пагинацией и сохранение параметров запроса
        $certificates = $query->latest()->paginate(10)->withQueryString();
        
        // Дополнительная статистика
        $activeCount = $query->where('status', 'active')->count();
        $totalAmount = $query->where('status', 'active')->sum('amount');
        
        return view('user.certificates.index', compact('certificates', 'activeCount', 'totalAmount'));
    }

    // Метод show удален, т.к. пользователи больше не могут видеть детали сертификата через LK

    /**
     * Метод markAsUsed также удален, т.к. пользователи больше не могут самостоятельно
     * активировать сертификат. Это может делать только предприниматель.
     */
}
