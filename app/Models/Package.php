<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'facilities',
        'price',
        'duration_days',
        'departure_date',
        'airline',
        'hotel',
        'hotel_makkah',
        'hotel_madinah',
        'flight_note',
        'image_path',
        'is_featured',
        'is_visible',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'duration_days' => 'integer',
            'departure_date' => 'date',
            'facilities' => 'array',
            'is_featured' => 'boolean',
            'is_visible' => 'boolean',
        ];
    }
}
