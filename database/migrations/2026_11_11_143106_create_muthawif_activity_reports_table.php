<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('muthawif_activity_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('muthawif_activity_id')
                ->unique('muthawif_activity_report_activity_unique')
                ->constrained('muthawif_activities')
                ->cascadeOnDelete();
            $table->unsignedInteger('present_count')->nullable();
            $table->unsignedInteger('absent_count')->nullable();
            $table->text('implementation_notes')->nullable();
            $table->text('issues')->nullable();
            $table->text('follow_up')->nullable();
            $table->string('status', 20)->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamps();

            $table->index('status');
            $table->index('submitted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('muthawif_activity_reports');
    }
};
