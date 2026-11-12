<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MuthawifActivity extends Model
{
    protected $fillable = [
        'jamaah_group_id',
        'activity_type',
        'activity_name',
        'activity_date',
        'start_time',
        'end_time',
        'location',
        'description',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'activity_date' => 'date',
        ];
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(JamaahGroup::class, 'jamaah_group_id');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(MuthawifActivityJamaah::class, 'muthawif_activity_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function report(): HasOne
    {
        return $this->hasOne(MuthawifActivityReport::class, 'muthawif_activity_id');
    }
}
