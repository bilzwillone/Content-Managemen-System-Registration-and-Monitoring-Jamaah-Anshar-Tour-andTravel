
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jamaah_groups', function (Blueprint $table) {
            $table->id();

            // Relasi dengan jadwal keberangkatan
            $table->foreignId('schedule_id')
                ->constrained('schedules')
                ->restrictOnDelete();

            // Identitas kelompok
            $table->string('name');
            $table->string('group_code')->unique();

            // Status kelompok
            $table->string('status')->default('persiapan');

            // Catatan tambahan
            $table->text('notes')->nullable();

            // Karyawan yang membuat kelompok
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['schedule_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jamaah_groups');
    }
};
