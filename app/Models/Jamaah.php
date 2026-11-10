<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jamaah extends Model
{
    protected $fillable = [
        'prospect_id',
        'user_id',
        'package_id',
        'name',
        'phone',
        'email',
        'address',
        'registration_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'registration_date' => 'date',
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
}
