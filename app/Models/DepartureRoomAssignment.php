<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DepartureRoomAssignment extends Model
{
    protected $fillable = [
        'departure_preparation_id',
        'jamaah_id',
        'city',
        'room_number',
        'room_type',
        'notes',
        'assigned_by',
    ];

    public function preparation(): BelongsTo
    {
        return $this->belongsTo(
            DeparturePreparation::class,
            'departure_preparation_id'
        );
    }

    public function jamaah(): BelongsTo
    {
        return $this->belongsTo(Jamaah::class);
    }

    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
