
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departure_room_assignments', function (Blueprint $table) {
            $table->id();

            // Relasi dengan persiapan keberangkatan
            $table->foreignId('departure_preparation_id')
                ->constrained('departure_preparations')
                ->cascadeOnDelete();

            // Relasi dengan jamaah
            $table->foreignId('jamaah_id')
                ->constrained('jamaahs')
                ->restrictOnDelete();

            // Lokasi hotel
            $table->string('city', 20);

            // Informasi kamar
            $table->string('room_number', 50);
            $table->string('room_type', 20)->nullable();

            // Catatan tambahan
            $table->text('notes')->nullable();

            // Karyawan yang mengatur kamar
            $table->foreignId('assigned_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            // Satu jamaah hanya memiliki satu penempatan kamar
            // untuk masing-masing kota dalam satu keberangkatan.
            $table->unique(
                ['departure_preparation_id', 'jamaah_id', 'city'],
                'departure_room_assignment_unique'
            );

            $table->index(['city', 'room_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departure_room_assignments');
    }
};
