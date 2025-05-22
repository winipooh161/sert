<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;

class PublicCertificateController extends Controller
{
    /**
     * Отображает публичную страницу сертификата по UUID.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $certificate = Certificate::where('uuid', $uuid)->firstOrFail();
        
        // Загружаем связанные данные для эффективности
        $certificate->load(['user', 'template']);
        
        // Если у сертификата нет обложки, используем запасное изображение
        if (!$certificate->cover_image || !file_exists(public_path('storage/' . $certificate->cover_image))) {
            // Устанавливаем запасное изображение обложки
            $certificate->cover_image = 'default_certificate_cover.jpg';
        }
        
        return view('certificates.public', compact('certificate'));
    }
}
