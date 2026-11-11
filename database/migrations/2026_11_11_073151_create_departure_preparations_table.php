
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departure_preparations', function (Blueprint $table) {
            $table->id();

            // Jadwal keberangkatan
            $table->foreignId('schedule_id')
                ->unique()
                ->constrained('schedules')
                ->restrictOnDelete();

            // Informasi penerbangan keberangkatan
            $table->string('departure_flight_number')->nullable();
            $table->string('departure_airport')->nullable();
            $table->string('arrival_airport')->nullable();
            $table->dateTime('departure_at')->nullable();
            $table->dateTime('arrival_at')->nullable();

            // Informasi penerbangan kepulangan
            $table->string('return_flight_number')->nullable();
            $table->dateTime('return_departure_at')->nullable();
            $table->dateTime('return_arrival_at')->nullable();

            // Hotel Makkah
            $table->string('makkah_hotel_name')->nullable();
            $table->text('makkah_hotel_address')->nullable();
            $table->date('makkah_check_in')->nullable();
            $table->date('makkah_check_out')->nullable();

            // Hotel Madinah
            $table->string('madinah_hotel_name')->nullable();
            $table->text('madinah_hotel_address')->nullable();
            $table->date('madinah_check_in')->nullable();
            $table->date('madinah_check_out')->nullable();

            // Transportasi
            $table->text('transportation_details')->nullable();

            // Perlengkapan jamaah
            $table->text('equipment_notes')->nullable();

            // Jadwal manasik
            $table->dateTime('manasik_at')->nullable();
            $table->string('manasik_location')->nullable();
            $table->text('manasik_notes')->nullable();

            // Jadwal briefing
            $table->dateTime('briefing_at')->nullable();
            $table->string('briefing_location')->nullable();
            $table->text('briefing_notes')->nullable();

            // Status persiapan
            $table->string('status')->default('draft');

            // Catatan umum
            $table->text('notes')->nullable();

            // Karyawan pembuat data
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departure_preparations');
    }
};
