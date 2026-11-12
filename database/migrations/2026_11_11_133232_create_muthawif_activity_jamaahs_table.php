<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('muthawif_activity_jamaahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('muthawif_activity_id')
                ->constrained('muthawif_activities')
                ->cascadeOnDelete();
            $table->foreignId('jamaah_id')
                ->constrained('jamaahs')
                ->cascadeOnDelete();
            $table->string('status', 40)->default('belum_dilaksanakan');
            $table->text('assistance_notes')->nullable();
            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamps();

            $table->unique(
                ['muthawif_activity_id', 'jamaah_id'],
                'muthawif_activity_jamaah_unique'
            );
            $table->index(['muthawif_activity_id', 'status'], 'muthawif_activity_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('muthawif_activity_jamaahs');
    }
};
