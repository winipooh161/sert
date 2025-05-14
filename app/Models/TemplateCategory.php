<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateCategory extends Model
{
    use HasFactory;

    /**
     * Атрибуты, которые можно массово назначать.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'directory_name',
        'description',
        'sort_order',
        'is_active'
    ];

    /**
     * Атрибуты, которые нужно приводить к определённому типу.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Шаблоны, принадлежащие к данной категории
     */
    public function templates()
    {
        return $this->hasMany(CertificateTemplate::class, 'category_id');
    }

    /**
     * Получить полный путь к директории категории
     */
    public function getDirectoryPathAttribute()
    {
        return public_path('templates/' . $this->directory_name);
    }
}
