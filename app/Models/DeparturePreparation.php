<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeparturePreparation extends Model
{
    protected $fillable = [
        'schedule_id',

        // Penerbangan keberangkatan
        'departure_flight_number',
        'departure_airport',
        'arrival_airport',
        'departure_at',
        'arrival_at',

        // Penerbangan kepulangan
        'return_flight_number',
        'return_departure_at',
        'return_arrival_at',

        // Hotel Makkah
        'makkah_hotel_name',
        'makkah_hotel_address',
        'makkah_check_in',
        'makkah_check_out',

        // Hotel Madinah
        'madinah_hotel_name',
        'madinah_hotel_address',
        'madinah_check_in',
        'madinah_check_out',

        // Transportasi dan perlengkapan
        'transportation_details',
        'equipment_notes',

        // Manasik
        'manasik_at',
        'manasik_location',
        'manasik_notes',

        // Briefing
        'briefing_at',
        'briefing_location',
        'briefing_notes',

        // Status dan catatan
        'status',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'departure_at' => 'datetime',
            'arrival_at' => 'datetime',
            'return_departure_at' => 'datetime',
            'return_arrival_at' => 'datetime',

            'makkah_check_in' => 'date',
            'makkah_check_out' => 'date',

            'madinah_check_in' => 'date',
            'madinah_check_out' => 'date',

            'manasik_at' => 'datetime',
            'briefing_at' => 'datetime',
        ];
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function roomAssignments(): HasMany
    {
        return $this->hasMany(
            DepartureRoomAssignment::class,
            'departure_preparation_id'
        );
    }

    public function checklists(): HasMany
    {
        return $this->hasMany(
            DepartureChecklist::class,
            'departure_preparation_id'
        );
    }
}
