<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DepartureChecklist extends Model
{
    protected $fillable = [
        'departure_preparation_id',
        'jamaah_id',
        'category',
        'item_name',
        'is_required',
        'status',
        'notes',
        'checked_by',
        'checked_at',
    ];

    /**
     * Konversi tipe data.
     */
    protected function casts(): array
    {
        return [
            'is_required' => 'boolean',
            'checked_at' => 'datetime',
        ];
    }

    /**
     * Relasi dengan persiapan keberangkatan.
     */
    public function preparation(): BelongsTo
    {
        return $this->belongsTo(
            DeparturePreparation::class,
            'departure_preparation_id'
        );
    }

    /**
     * Relasi dengan data jamaah.
     */
    public function jamaah(): BelongsTo
    {
        return $this->belongsTo(Jamaah::class);
    }

    /**
     * Relasi dengan karyawan yang melakukan pemeriksaan.
     */
    public function checker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_by');
    }
}
