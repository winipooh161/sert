<?php

namespace App\Services;

use Intervention\Image\Facades\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Сжимает и сохраняет изображение
     *
     * @param UploadedFile $file Загружаемый файл
     * @param string $path Путь для сохранения
     * @param int $width Максимальная ширина (0 - без изменений)
     * @param int $height Максимальная высота (0 - без изменений)
     * @param int $quality Качество сжатия (1-100)
     * @return string Путь к сохраненному файлу
     */
    public function compressAndSave(UploadedFile $file, string $path, int $width = 0, int $height = 0, int $quality = 80): string
    {
        // Генерируем уникальное имя файла с оригинальным расширением
        $extension = $file->getClientOriginalExtension();
        if (empty($extension)) {
            // Если расширение пустое, пытаемся определить из MIME-типа
            $mime = $file->getMimeType();
            $extension = $this->getExtensionFromMime($mime);
        }
        
        $filename = Str::uuid() . '.' . $extension;
        $fullPath = $path . '/' . $filename;
        
        // Создаем экземпляр изображения
        $image = Image::make($file);
        
        // Изменяем размер, сохраняя пропорции, если заданы размеры
        if ($width > 0 && $height > 0) {
            $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        } elseif ($width > 0) {
            $image->resize($width, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        } elseif ($height > 0) {
            $image->resize(null, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }
        
        // Создаем директорию для хранения, если её нет
        $directory = storage_path('app/public/' . $path);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        // Сохраняем непосредственно в нужную директорию с явным указанием формата
        $format = $this->getFormatFromExtension($extension);
        $storagePath = storage_path('app/public/' . $fullPath);
        
        // Используем явный формат при сохранении
        if ($format === 'jpg' || $format === 'jpeg') {
            $image->encode('jpg', $quality)->save($storagePath);
        } elseif ($format === 'png') {
            // PNG игнорирует параметр качества, используем уровень сжатия 9
            $image->encode('png')->save($storagePath);
        } elseif ($format === 'gif') {
            $image->encode('gif')->save($storagePath);
        } elseif ($format === 'webp') {
            $image->encode('webp', $quality)->save($storagePath);
        } else {
            // Для остальных форматов используем auto
            $image->save($storagePath, $quality);
        }
        
        return $fullPath;
    }
    
    /**
     * Создает квадратный аватар из загруженного изображения
     *
     * @param UploadedFile $file Загружаемый файл
     * @param string $path Путь для сохранения
     * @param int $size Размер аватара
     * @return string Путь к сохраненному аватару
     */
    public function createAvatar(UploadedFile $file, string $path, int $size = 300): string
    {
        return $this->compressAndSave($file, $path, $size, $size, 90);
    }
    
    /**
     * Создает логотип для сертификата
     *
     * @param UploadedFile $file Загружаемый файл
     * @param string $path Путь для сохранения
     * @return string Путь к сохраненному логотипу
     */
    public function createLogo(UploadedFile $file, string $path): string
    {
        return $this->compressAndSave($file, $path, 400, 150, 90);
    }
    
    /**
     * Создает обложку для сертификата
     *
     * @param UploadedFile $file Загружаемый файл
     * @param string $path Путь для сохранения
     * @return string Путь к сохраненной обложке
     */
    public function createCover(UploadedFile $file, string $path): string
    {
        return $this->compressAndSave($file, $path, 800, 500, 85);
    }
    
    /**
     * Получает расширение файла из MIME-типа
     *
     * @param string $mime MIME-тип файла
     * @return string Расширение файла
     */
    private function getExtensionFromMime(string $mime): string
    {
        $map = [
            'image/jpeg' => 'jpg',
            'image/jpg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'image/bmp' => 'bmp',
            'image/x-ms-bmp' => 'bmp',
            'image/svg+xml' => 'svg',
            'image/tiff' => 'tiff',
        ];
        
        return $map[$mime] ?? 'jpg';
    }
    
    /**
     * Получает формат для Intervention Image из расширения файла
     *
     * @param string $extension Расширение файла
     * @return string Формат для Intervention Image
     */
    private function getFormatFromExtension(string $extension): string
    {
        $extension = strtolower($extension);
        
        if ($extension === 'jpg' || $extension === 'jpeg') {
            return 'jpg';
        }
        
        if (in_array($extension, ['png', 'gif', 'webp', 'bmp'])) {
            return $extension;
        }
        
        return 'jpg'; // По умолчанию jpg
    }
}
