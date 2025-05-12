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
}
