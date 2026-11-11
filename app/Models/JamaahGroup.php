<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class JamaahGroup extends Model
{
    protected $fillable = [
        'schedule_id',
        'name',
        'group_code',
        'status',
        'notes',
        'created_by',
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(
            Jamaah::class,
            'jamaah_group_members',
            'jamaah_group_id',
            'jamaah_id'
        )
        ->withPivot([
            'status',
            'notes',
            'added_by',
        ])
        ->withTimestamps();
    }

    /**
     * Relasi dengan seluruh Muthawif yang ditugaskan.
     */
    public function muthawifs(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'jamaah_group_muthawifs',
            'jamaah_group_id',
            'user_id'
        )
        ->where('users.role', 'muthawif')
        ->withPivot([
            'assignment_type',
            'assigned_by',
            'notes',
        ])
        ->withTimestamps();
    }

    public function primaryMuthawif(): BelongsToMany
    {
        return $this->muthawifs()
            ->wherePivot('assignment_type', 'utama');
    }

    public function assistantMuthawif(): BelongsToMany
    {
        return $this->muthawifs()
            ->wherePivot('assignment_type', 'pendamping');
    }

    public function memberRecords(): HasMany
    {
        return $this->hasMany(
            JamaahGroupMember::class,
            'jamaah_group_id'
        );
    }

    public function muthawifAssignments(): HasMany
    {
        return $this->hasMany(
            JamaahGroupMuthawif::class,
            'jamaah_group_id'
        );
    }
}
