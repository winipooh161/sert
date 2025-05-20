<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Folder;
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

        // Получаем папки пользователя
        $folders = Folder::where('user_id', Auth::id())->orderBy('name')->get();

        // Если выбрана папка, получаем только сертификаты из этой папки
        $currentFolder = null;
        if ($request->has('folder') && !empty($request->folder)) {
            $currentFolder = Folder::where('id', $request->folder)
                              ->where('user_id', Auth::id())
                              ->first();

            if ($currentFolder) {
                $certificateIds = $currentFolder->certificates->pluck('id')->toArray();
                $query->whereIn('id', $certificateIds);
            }
        }

        $certificates = $query->paginate(12);

        return view('user.certificates.index', compact('certificates', 'folders', 'currentFolder'));
    }

    // Метод show удален, т.к. пользователи больше не могут видеть детали сертификата через LK

    /**
     * Метод markAsUsed также удален, т.к. пользователи больше не могут самостоятельно
     * активировать сертификат. Это может делать только предприниматель.
     */
}
