<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MuthawifActivityReport extends Model
{
    protected $fillable = [
        'muthawif_activity_id',
        'present_count',
        'absent_count',
        'implementation_notes',
        'issues',
        'follow_up',
        'status',
        'submitted_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'present_count' => 'integer',
            'absent_count' => 'integer',
            'submitted_at' => 'datetime',
        ];
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(MuthawifActivity::class, 'muthawif_activity_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
