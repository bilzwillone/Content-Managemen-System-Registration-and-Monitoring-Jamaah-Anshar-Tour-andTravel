<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Jamaah extends Model
{
    protected $fillable = [
        'prospect_id',
        'user_id',
        'package_id',

        'total_bill',

        'name',
        'phone',
        'email',
        'address',
        'registration_date',
        'status',

        'branch_office',
        'registration_number',

        'umrah_package_type',
        'room_type',
        'planned_departure_date',

        'national_id',
        'birth_place',
        'birth_date',
        'father_name',

        'gender',
        'marital_status',

        'home_phone',
        'emergency_contact_phone',

        'umrah_experience',
        'education_level',
        'occupation',

        'medical_history',
    ];

    protected function casts(): array
    {
        return [
            'total_bill' => 'decimal:2',
            'registration_date' => 'date',
            'planned_departure_date' => 'date',
            'birth_date' => 'date',
        ];
    }

    public function prospect(): BelongsTo
    {
        return $this->belongsTo(Prospect::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(JamaahDocument::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(
            JamaahGroup::class,
            'jamaah_group_members',
            'jamaah_id',
            'jamaah_group_id'
        )
        ->withPivot([
            'status',
            'notes',
            'added_by',
        ])
        ->withTimestamps();
    }

    public function groupMemberships(): HasMany
    {
        return $this->hasMany(
            JamaahGroupMember::class,
            'jamaah_id'
        );
    }

    public function departureRoomAssignments(): HasMany
    {
        return $this->hasMany(
            DepartureRoomAssignment::class,
            'jamaah_id'
        );
    }

    public function departureChecklists(): HasMany
    {
        return $this->hasMany(
            DepartureChecklist::class,
            'jamaah_id'
        );
    }
}
