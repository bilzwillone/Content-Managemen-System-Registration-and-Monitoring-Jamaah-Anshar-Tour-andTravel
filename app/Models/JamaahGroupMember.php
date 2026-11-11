<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JamaahGroupMember extends Model
{
    protected $fillable = [
        'jamaah_group_id',
        'jamaah_id',
        'status',
        'notes',
        'added_by',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(
            JamaahGroup::class,
            'jamaah_group_id'
        );
    }

    public function jamaah(): BelongsTo
    {
        return $this->belongsTo(Jamaah::class);
    }

    public function adder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
