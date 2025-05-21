<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'company',
        'position',
        'bio',
        'avatar',
        'notification_preferences',
        'company_logo', // Добавляем новое поле
        'phone_verification_code',
        'phone_verification_expires_at',
        'phone_pending_change',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'notification_preferences' => 'array',
        'phone_verification_expires_at' => 'datetime',
    ];

    /**
     * Роли пользователя
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Сертификаты, созданные пользователем
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Проверка наличия роли у пользователя
     */
    public function hasRole($roleName): bool
    {
        foreach ($this->roles as $role) {
            if ($role->slug === $roleName) {
                return true;
            }
        }
        return false;
    }

    /**
     * Проверка наличия любой из указанных ролей
     */
    public function hasAnyRole($roleNames): bool
    {
        if (is_string($roleNames)) {
            return $this->hasRole($roleNames);
        }

        foreach ($roleNames as $roleName) {
            if ($this->hasRole($roleName)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Получить URL логотипа компании.
     *
     * @return string
     */
    public function getCompanyLogoUrlAttribute()
    {
        if ($this->company_logo) {
            return asset('storage/' . $this->company_logo);
        }
        
        return asset('images/default-logo.png');
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForMail($notification)
    {
        // Используем email, если он есть, иначе используем заглушку с телефоном
        return $this->email ?? $this->phone . '@example.com';
    }
}
