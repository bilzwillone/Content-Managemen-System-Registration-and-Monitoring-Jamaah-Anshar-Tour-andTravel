<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JamaahDocument extends Model
{
    protected $fillable = [
        'jamaah_id',
        'document_type',
        'document_name',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
        'status',
        'notes',
        'uploaded_by',
        'verified_by',
        'uploaded_at',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
            'uploaded_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function jamaah(): BelongsTo
    {
        return $this->belongsTo(Jamaah::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }


    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
