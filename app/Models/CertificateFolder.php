<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CertificateFolder extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'user_id'
    ];

    /**
     * Пользователь, которому принадлежит папка
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Сертификаты в папке
     */
    public function certificates(): BelongsToMany
    {
        return $this->belongsToMany(Certificate::class, 'certificate_folder', 'certificate_folder_id', 'certificate_id')
            ->withTimestamps();
    }
}
