
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jamaah_group_members', function (Blueprint $table) {
            $table->id();

            // Relasi dengan kelompok jamaah
            $table->foreignId('jamaah_group_id')
                ->constrained('jamaah_groups')
                ->cascadeOnDelete();

            // Relasi dengan data jamaah
            $table->foreignId('jamaah_id')
                ->constrained('jamaahs')
                ->cascadeOnDelete();

            // Status keanggotaan
            $table->string('status')->default('aktif');

            // Catatan tambahan
            $table->text('notes')->nullable();

            // Karyawan yang menambahkan jamaah
            $table->foreignId('added_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            // Mencegah jamaah ditambahkan dua kali ke kelompok yang sama
            $table->unique(
                ['jamaah_group_id', 'jamaah_id'],
                'jamaah_group_member_unique'
            );

            $table->index('jamaah_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jamaah_group_members');
    }
};
