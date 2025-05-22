<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Certificate extends Model
{
    use HasFactory;

    // Явно указываем использование автоинкремента
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'certificate_number',
        'uuid',
        'user_id',
        'certificate_template_id',
        'recipient_name',
        'recipient_email',
        'recipient_phone',
        'amount',
        'message',
        'company_logo',
        'cover_image',
        'animation_effect_id', // Добавляем новое поле
        'custom_fields',
        'valid_from',
        'valid_until',
        'status',
        'used_at'
    ];

    // Даты для автоматического преобразования
    protected $dates = [
        'valid_from',
        'valid_until',
        'used_at',
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'used_at' => 'datetime',
        'is_percent' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Генерируем UUID при создании
        static::creating(function ($certificate) {
            $certificate->uuid = (string) Str::uuid();
        });
    }

    /**
     * Пользователь, создавший сертификат
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Шаблон, на основе которого создан сертификат
     */
    public function template()
    {
        return $this->belongsTo(CertificateTemplate::class, 'certificate_template_id');
    }

    /**
     * Получить URL изображения обложки сертификата.
     *
     * @return string
     */
    public function getCoverImageUrlAttribute()
    {
        if ($this->cover_image) {
            return asset('storage/' . $this->cover_image);
        }
        
        // Возвращаем URL изображения шаблона, если обложка не задана
        return $this->template && $this->template->image 
            ? asset('storage/' . $this->template->image) 
            : asset('images/certificate-placeholder.jpg');
    }

    /**
     * Папки, в которых находится сертификат
     */
    public function folders()
    {
        return $this->belongsToMany(Folder::class, 'certificate_folder', 'certificate_id', 'folder_id')
            ->withTimestamps();
    }

    /**
     * Анимационный эффект сертификата
     */
    public function animationEffect()
    {
        return $this->belongsTo(AnimationEffect::class);
    }

    /**
     * Форматирует дату с проверкой типа
     *
     * @param mixed $date Дата для форматирования
     * @param string $format Формат вывода
     * @return string Отформатированная дата
     */
    public function formatDate($date, $format = 'd.m.Y')
    {
        if (is_string($date)) {
            return $date;
        } elseif ($date instanceof \Carbon\Carbon) {
            return $date->format($format);
        } elseif (is_null($date)) {
            return '---';
        } else {
            return (string) $date;
        }
    }
}
