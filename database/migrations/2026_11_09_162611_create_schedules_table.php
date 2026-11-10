
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('type', 20);
            $table->date('departure_date');
            $table->date('return_date');
            $table->string('airline')->nullable();

            $table->unsignedInteger('total_seats')->default(1);
            $table->unsignedInteger('booked_seats')->default(0);

            $table->text('description')->nullable();

            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->string('status')->default('draft');

            $table->timestamps();

            $table->index(['type', 'status']);
            $table->index('departure_date');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
