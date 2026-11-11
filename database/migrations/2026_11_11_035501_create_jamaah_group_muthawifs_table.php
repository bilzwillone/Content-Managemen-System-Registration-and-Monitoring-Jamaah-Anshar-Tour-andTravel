
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jamaah_group_muthawifs', function (Blueprint $table) {
            $table->id();

            // Relasi dengan kelompok jamaah
            $table->foreignId('jamaah_group_id')
                ->constrained('jamaah_groups')
                ->cascadeOnDelete();

            // Relasi dengan akun Muthawif
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            // Posisi penugasan Muthawif
            $table->string('assignment_type', 20);

            // Karyawan yang menetapkan penugasan
            $table->foreignId('assigned_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Catatan penugasan
            $table->text('notes')->nullable();

            $table->timestamps();

            // Satu kelompok hanya memiliki satu Muthawif pada setiap posisi
            $table->unique(
                ['jamaah_group_id', 'assignment_type'],
                'group_muthawif_type_unique'
            );

            // Mencegah Muthawif yang sama ditugaskan dua kali dalam kelompok
            $table->unique(
                ['jamaah_group_id', 'user_id'],
                'group_muthawif_user_unique'
            );

            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jamaah_group_muthawifs');
    }
};
