<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'jamaah_id',
        'order_id',
        'payment_type',
        'amount',
        'currency',
        'gateway_provider',
        'gateway_transaction_id',
        'payment_method',
        'payment_url',
        'status',
        'due_date',
        'expires_at',
        'paid_at',
        'notes',
        'gateway_response',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'due_date' => 'date',
            'expires_at' => 'datetime',
            'paid_at' => 'datetime',
            'gateway_response' => 'array',
        ];
    }

    public function jamaah(): BelongsTo
    {
        return $this->belongsTo(Jamaah::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
