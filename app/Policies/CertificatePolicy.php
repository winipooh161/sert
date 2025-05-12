<?php

namespace App\Policies;

use App\Models\Certificate;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CertificatePolicy
{
    use HandlesAuthorization;

    /**
     * Определяет, может ли пользователь просматривать список всех сертификатов.
     */
    public function viewAny(User $user)
    {
        return $user->hasRole('admin') || $user->hasRole('predprinimatel');
    }

    /**
     * Определяет, может ли пользователь просматривать сертификат.
     */
    public function view(User $user, Certificate $certificate)
    {
        return $user->hasRole('admin') || $certificate->user_id === $user->id;
    }

    /**
     * Определяет, может ли пользователь создавать сертификаты.
     */
    public function create(User $user)
    {
        return $user->hasRole('admin') || $user->hasRole('predprinimatel');
    }

    /**
     * Определяет, может ли пользователь обновлять сертификат.
     */
    public function update(User $user, Certificate $certificate)
    {
        return $user->hasRole('admin') || $certificate->user_id === $user->id;
    }

    /**
     * Определяет, может ли пользователь удалять сертификат.
     */
    public function delete(User $user, Certificate $certificate)
    {
        return $user->hasRole('admin') || $certificate->user_id === $user->id;
    }
}
