<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jamaah extends Model
{
    protected $fillable = [
        // Relasi utama
        'prospect_id',
        'user_id',
        'package_id',

        // Informasi tagihan
        'total_bill',

        // Informasi dasar
        'name',
        'phone',
        'email',
        'address',
        'registration_date',
        'status',

        // Informasi registrasi
        'branch_office',
        'registration_number',

        // Pilihan paket dan keberangkatan
        'umrah_package_type',
        'room_type',
        'planned_departure_date',

        // Identitas pribadi
        'national_id',
        'birth_place',
        'birth_date',
        'father_name',

        'gender',
        'marital_status',

        'home_phone',
        'emergency_contact_phone',

        'umrah_experience',
        'education_level',
        'occupation',

        'medical_history',
    ];

    protected function casts(): array
    {
        return [
            'total_bill' => 'decimal:2',
            'registration_date' => 'date',
            'planned_departure_date' => 'date',
            'birth_date' => 'date',
        ];
    }

    public function prospect(): BelongsTo
    {
        return $this->belongsTo(Prospect::class);
    }

    /**
     * Relasi dengan akun pengguna Jamaah.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi dengan paket perjalanan.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Seluruh dokumen milik Jamaah.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(JamaahDocument::class);
    }

    /**
     * Seluruh transaksi pembayaran milik Jamaah.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
