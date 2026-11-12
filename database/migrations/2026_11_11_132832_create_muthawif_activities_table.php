<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('muthawif_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jamaah_group_id')
                ->constrained('jamaah_groups')
                ->cascadeOnDelete();
            $table->string('activity_type', 50);
            $table->string('activity_name', 180);
            $table->date('activity_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamps();

            $table->index(['jamaah_group_id', 'activity_date'], 'muthawif_activity_group_date_idx');
            $table->index('activity_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('muthawif_activities');
    }
};
