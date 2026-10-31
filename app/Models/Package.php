<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'duration_days',
        'departure_date',
        'airline',
        'hotel',
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
            'is_featured' => 'boolean',
            'is_visible' => 'boolean',
        ];
    }
}