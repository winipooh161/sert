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
        'category_id', 
        'image', 
        'template_path',  // Путь к HTML файлу шаблона
        'html_template',  // Добавляем поле для хранения HTML-контента шаблона
        'is_premium',
        'is_active',
        'fields',
        'sort_order'
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
        'sort_order' => 'integer',
    ];

    /**
     * Категория шаблона
     */
    public function category()
    {
        return $this->belongsTo(TemplateCategory::class, 'category_id');
    }

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
     * Аксессор для получения полного пути к файлу шаблона
     *
     * @return string
     */
    public function getTemplateFilePathAttribute()
    {
        return public_path($this->template_path);
    }

    /**
     * Аксессор для получения HTML содержимого шаблона
     *
     * @return string
     */
    public function getHtmlTemplateAttribute()
    {
        if (file_exists($this->template_file_path)) {
            return file_get_contents($this->template_file_path);
        }
        
        return '<div class="alert alert-danger">Шаблон не найден</div>';
    }

    /**
     * Связь с сертификатами.
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'certificate_template_id');
    }
}
