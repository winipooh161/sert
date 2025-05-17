<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\CertificateFolder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateFolderController extends Controller
{
    /**
     * Создание нового экземпляра контроллера.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:user']);
    }

    /**
     * Сохранить новую папку.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|in:primary,success,danger,warning,info,dark'
        ]);

        // Проверяем количество существующих папок у пользователя
        $userFoldersCount = CertificateFolder::where('user_id', Auth::id())->count();
        
        // Если у пользователя уже 5 папок, запрещаем создание новой
        if ($userFoldersCount >= 5) {
            return redirect()->back()->with('error', 'Вы не можете создать больше 5 папок. Пожалуйста, удалите существующие папки, прежде чем создавать новые.');
        }

        $folder = new CertificateFolder([
            'name' => $request->name,
            'color' => $request->color,
        ]);

        $folder->user()->associate(Auth::user());
        $folder->save();

        return redirect()->back()->with('success', 'Папка успешно создана');
    }

    /**
     * Добавить сертификат в папку.
     */
    public function addCertificate(Request $request, Certificate $certificate, CertificateFolder $folder)
    {
        // Проверка доступа
        if ($folder->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'У вас нет доступа к этой папке'
            ], 403);
        }

        // Проверка, принадлежит ли сертификат пользователю
        $userPhone = Auth::user()->phone;
        $userEmail = Auth::user()->email;
        
        $isUsersCertificate = ($userPhone && $certificate->recipient_phone === $userPhone) 
            || ($userEmail && $certificate->recipient_email === $userEmail);
            
        if (!$isUsersCertificate) {
            return response()->json([
                'success' => false,
                'message' => 'У вас нет доступа к этому сертификату'
            ], 403);
        }

        // Добавляем сертификат в папку
        $certificate->folders()->syncWithoutDetaching([$folder->id]);

        return response()->json([
            'success' => true,
            'message' => 'Сертификат добавлен в папку "' . $folder->name . '"'
        ]);
    }

    /**
     * Получить список папок, в которых находится сертификат
     */
    public function getCertificateFolders(Certificate $certificate)
    {
        try {
            // Проверка принадлежности сертификата пользователю
            $userPhone = Auth::user()->phone;
            $userEmail = Auth::user()->email;
            
            $isUsersCertificate = ($userPhone && $certificate->recipient_phone === $userPhone) 
                || ($userEmail && $certificate->recipient_email === $userEmail);
                
            if (!$isUsersCertificate) {
                return response()->json([
                    'success' => false,
                    'message' => 'У вас нет доступа к этому сертификату'
                ], 403);
            }

            // Исправляем неоднозначность id, явно указывая таблицу
            $folders = $certificate->folders()
                ->where('certificate_folders.user_id', Auth::id())
                ->select(['certificate_folders.id', 'certificate_folders.name', 'certificate_folders.color'])
                ->get();

            return response()->json([
                'success' => true,
                'folders' => $folders
            ]);
        } catch (\Exception $e) {
            \Log::error('Ошибка при получении папок сертификата: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Произошла ошибка при получении папок',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Удалить сертификат из папки.
     */
    public function removeCertificate(Certificate $certificate, CertificateFolder $folder)
    {
        // Проверка доступа
        if ($folder->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'У вас нет доступа к этой папке'
            ], 403);
        }

        // Проверяем принадлежность сертификата пользователю
        $userPhone = Auth::user()->phone;
        $userEmail = Auth::user()->email;
        
        $isUsersCertificate = ($userPhone && $certificate->recipient_phone === $userPhone) 
            || ($userEmail && $certificate->recipient_email === $userEmail);
            
        if (!$isUsersCertificate) {
            return response()->json([
                'success' => false,
                'message' => 'У вас нет доступа к этому сертификату'
            ], 403);
        }

        // Удаляем сертификат из папки
        $certificate->folders()->detach($folder->id);

        return response()->json([
            'success' => true,
            'message' => 'Сертификат удален из папки "' . $folder->name . '"'
        ]);
    }

    /**
     * Обновить папку.
     */
    public function update(Request $request, CertificateFolder $folder)
    {
        // Проверка доступа
        if ($folder->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'У вас нет доступа к этой папке');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|in:primary,success,danger,warning,info,dark'
        ]);

        $folder->update([
            'name' => $request->name,
            'color' => $request->color
        ]);

        return redirect()->back()->with('success', 'Папка успешно обновлена');
    }

    /**
     * Удалить папку.
     */
    public function destroy(CertificateFolder $folder)
    {
        // Проверка доступа
        if ($folder->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'У вас нет доступа к этой папке');
        }

        // Удаляем связи с сертификатами, а затем саму папку
        $folder->certificates()->detach();
        $folder->delete();

        return redirect()->back()->with('success', 'Папка успешно удалена');
    }
}
