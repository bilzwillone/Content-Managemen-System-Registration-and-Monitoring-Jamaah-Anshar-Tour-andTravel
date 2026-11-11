<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    /**
     * Calon jamaah yang menjadi tanggung jawab Karyawan.
     */
    public function assignedProspects(): HasMany
    {
        return $this->hasMany(Prospect::class, 'pic_id');
    }

    /**
     * Calon jamaah yang dicatat oleh pengguna.
     */
    public function createdProspects(): HasMany
    {
        return $this->hasMany(Prospect::class, 'created_by');
    }

    /**
     * Riwayat follow-up yang dilakukan pengguna.
     */
    public function prospectFollowUps(): HasMany
    {
        return $this->hasMany(ProspectFollowUp::class, 'user_id');
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function muthawifGroups(): BelongsToMany
    {
        return $this->belongsToMany(
            JamaahGroup::class,
            'jamaah_group_muthawifs',
            'user_id',
            'jamaah_group_id'
        )
        ->withPivot([
            'assignment_type',
            'assigned_by',
            'notes',
        ])
        ->withTimestamps();
    }

    public function muthawifAssignments(): HasMany
    {
        return $this->hasMany(
            JamaahGroupMuthawif::class,
            'user_id'
        );
    }

    public function assignedGroupMuthawifs(): HasMany
    {
        return $this->hasMany(
            JamaahGroupMuthawif::class,
            'assigned_by'
        );
    }

    public function createdJamaahGroups(): HasMany
    {
        return $this->hasMany(
            JamaahGroup::class,
            'created_by'
        );
    }


}
