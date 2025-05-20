<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    /**
     * Добавляет сертификат в папку
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $certificateId
     * @param  int  $folderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function addCertificateToFolder(Request $request, $certificateId, $folderId)
    {
        try {
            $user = Auth::user();
            
            // Проверяем, что папка и сертификат принадлежат пользователю
            $folder = Folder::where('user_id', $user->id)
                ->where('id', $folderId)
                ->first();
                
            $certificate = Certificate::where('id', $certificateId)
                ->first();
                
            if (!$folder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Папка не найдена или не принадлежит текущему пользователю'
                ], 404);
            }
            
            if (!$certificate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Сертификат не найден'
                ], 404);
            }
            
            // Проверяем, не находится ли сертификат уже в этой папке
            if ($certificate->folders()->where('folder_id', $folder->id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Сертификат уже находится в этой папке'
                ], 200);
            }
            
            // Добавляем сертификат в папку
            $certificate->folders()->attach($folder->id);
            
            return response()->json([
                'success' => true,
                'message' => 'Сертификат успешно добавлен в папку'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при добавлении сертификата в папку: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Удаляет сертификат из папки
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $certificateId
     * @param  int  $folderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeCertificateFromFolder(Request $request, $certificateId, $folderId)
    {
        try {
            $user = Auth::user();
            
            // Проверяем, что папка принадлежит пользователю
            $folder = Folder::where('user_id', $user->id)
                ->where('id', $folderId)
                ->first();
                
            $certificate = Certificate::where('id', $certificateId)
                ->first();
                
            if (!$folder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Папка не найдена или не принадлежит текущему пользователю'
                ], 404);
            }
            
            if (!$certificate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Сертификат не найден'
                ], 404);
            }
            
            // Удаляем сертификат из папки
            $certificate->folders()->detach($folder->id);
            
            return response()->json([
                'success' => true,
                'message' => 'Сертификат успешно удален из папки'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при удалении сертификата из папки: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Получить папки, в которых находится сертификат
     *
     * @param  int  $certificateId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCertificateFolders($certificateId)
    {
        try {
            $user = Auth::user();
            
            $certificate = Certificate::findOrFail($certificateId);
            
            // Получаем все папки пользователя
            $allFolders = Folder::where('user_id', $user->id)->get();
            
            // Для каждой папки проверяем, содержит ли она сертификат
            $folders = $allFolders->map(function($folder) use ($certificate) {
                $hasThisCertificate = $certificate->folders()->where('folder_id', $folder->id)->exists();
                
                return [
                    'id' => $folder->id,
                    'name' => $folder->name,
                    'color' => $folder->color,
                    'has_certificate' => $hasThisCertificate
                ];
            });
            
            return response()->json([
                'success' => true,
                'folders' => $folders
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при получении папок сертификата: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Создать новую папку
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string',
        ]);
        
        $folder = new Folder();
        $folder->name = $validated['name'];
        $folder->color = $validated['color'];
        $folder->user_id = Auth::id();
        $folder->save();
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Папка успешно создана',
                'folder' => $folder
            ]);
        }
        
        return redirect()->route('user.certificates.index')
                       ->with('success', 'Папка успешно создана');
    }
    
    /**
     * Удаляет папку и все связи сертификатов с ней
     *
     * @param int $id ID папки для удаления
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Находим папку по ID, убеждаемся что она принадлежит текущему пользователю
        $folder = Folder::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        // Отсоединяем все сертификаты от папки (удаляем связи)
        $folder->certificates()->detach();
        
        // Удаляем саму папку
        $folder->delete();
        
        // Проверяем, является ли запрос AJAX-запросом
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Папка \"{$folder->name}\" успешно удалена"
            ]);
        }
        
        // Для обычного HTML-запроса возвращаем редирект
        return redirect()
            ->route('user.certificates.index')
            ->with('success', "Папка \"{$folder->name}\" успешно удалена");
    }
}
