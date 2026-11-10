<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProspectFollowUp extends Model
{
    protected $fillable = [
        'prospect_id',
        'user_id',
        'channel',
        'activity_at',
        'summary',
        'notes',
        'next_follow_up_at',
    ];

    protected function casts(): array
    {
        return [
            'activity_at' => 'datetime',
            'next_follow_up_at' => 'datetime',
        ];
    }

    /**
     * Calon jamaah yang dihubungi.
     */
    public function prospect(): BelongsTo
    {
        return $this->belongsTo(Prospect::class);
    }

    /**
     * Karyawan yang melakukan follow-up.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
