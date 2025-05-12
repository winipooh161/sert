<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateTemplate extends Model
{
    use HasFactory;

    /**
     * Атрибуты, которые можно массово назначать.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'description', 
        'image', 
        'html_template', 
        'is_premium',
        'is_active',
        'fields'
    ];

    /**
     * Атрибуты, которые нужно приводить к определённому типу.
     *
     * @var array
     */
    protected $casts = [
        'fields' => 'array',
        'is_premium' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Аксессор для получения URL изображения.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        
        return asset('images/default-template.jpg');
    }

    /**
     * Связь с сертификатами.
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'template_id');
    }
}
