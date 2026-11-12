<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MuthawifActivityJamaah extends Model
{
    protected $table = 'muthawif_activity_jamaahs';

    protected $fillable = [
        'muthawif_activity_id',
        'jamaah_id',
        'status',
        'assistance_notes',
        'updated_by',
    ];

    public function activity(): BelongsTo
    {
        return $this->belongsTo(MuthawifActivity::class, 'muthawif_activity_id');
    }

    public function jamaah(): BelongsTo
    {
        return $this->belongsTo(Jamaah::class);
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
