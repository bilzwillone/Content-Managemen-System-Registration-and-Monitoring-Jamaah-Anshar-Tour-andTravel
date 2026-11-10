<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_KARYAWAN = 'karyawan';
    public const ROLE_MUTHAWIF = 'muthawif';
    public const ROLE_JAMAAH = 'jamaah';

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(self::ROLE_ADMIN);
    }

    public function isKaryawan(): bool
    {
        return $this->hasRole(self::ROLE_KARYAWAN);
    }

    public function isMuthawif(): bool
    {
        return $this->hasRole(self::ROLE_MUTHAWIF);
    }

    public function isJamaah(): bool
    {
        return $this->hasRole(self::ROLE_JAMAAH);
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
