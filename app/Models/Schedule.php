<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'name',
        'type',
        'departure_date',
        'return_date',
        'airline',
        'total_seats',
        'booked_seats',
        'description',
        'sort_order',
        'is_visible',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'departure_date' => 'date',
            'return_date' => 'date',
            'total_seats' => 'integer',
            'booked_seats' => 'integer',
            'sort_order' => 'integer',
            'is_visible' => 'boolean',
        ];
    }

    public function getAvailableSeatsAttribute(): int
    {
        return max(0, $this->total_seats - $this->booked_seats);
    }

    public function getDurationDaysAttribute(): int
    {
        if (!$this->departure_date || !$this->return_date) {
            return 0;
        }

        return $this->departure_date->diffInDays($this->return_date) + 1;
    }

    public function getDurationLabelAttribute(): string
    {
        $days = $this->duration_days;

        if ($days <= 0) {
            return '-';
        }

        return $days . ' Hari ' . max(0, $days - 1) . ' Malam';
    }

    public function getAvailabilityStatusAttribute(): string
    {
        if ($this->departure_date && $this->departure_date->lt(today())) {
            return 'Sudah Berangkat';
        }

        if ($this->available_seats <= 0) {
            return 'Penuh';
        }

        $almostFullThreshold = max(
            3,
            (int) ceil($this->total_seats * 0.15)
        );

        if ($this->available_seats <= $almostFullThreshold) {
            return 'Hampir Penuh';
        }

        return 'Tersedia';
    }

    public function getAvailabilityColorAttribute(): string
    {
        return match ($this->availability_status) {
            'Sudah Berangkat' => 'red',
            'Penuh' => 'red',
            'Hampir Penuh' => 'amber',
            default => 'green',
        };
    }
}
