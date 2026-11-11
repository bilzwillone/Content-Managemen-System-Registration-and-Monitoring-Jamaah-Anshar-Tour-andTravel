<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JamaahGroupMuthawif extends Model
{
    protected $fillable = [
        'jamaah_group_id',
        'user_id',
        'assignment_type',
        'assigned_by',
        'notes',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(
            JamaahGroup::class,
            'jamaah_group_id'
        );
    }

    public function muthawif(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
