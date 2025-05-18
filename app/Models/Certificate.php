<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Certificate extends Model
{
    use HasFactory;

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

    protected $casts = [
        'custom_fields' => 'array',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'used_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($certificate) {
            // Автоматически генерируем UUID при создании сертификата, если его нет
            if (!$certificate->uuid) {
                $certificate->uuid = (string) Str::uuid();
            }
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
        // Явно указываем столбцы связующей таблицы
        return $this->belongsToMany(
            CertificateFolder::class, 
            'certificate_folder', 
            'certificate_id', 
            'certificate_folder_id'
        )
        ->withTimestamps();
    }

    /**
     * Анимационный эффект сертификата
     */
    public function animationEffect()
    {
        return $this->belongsTo(AnimationEffect::class);
    }
}
