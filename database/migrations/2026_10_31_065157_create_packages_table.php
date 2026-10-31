<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique();

            $table->text('description')->nullable();

            $table->decimal('price', 15, 2)->nullable();
            $table->unsignedInteger('duration_days')->nullable();

            $table->date('departure_date')->nullable();
            $table->string('airline')->nullable();
            $table->string('hotel')->nullable();

            $table->string('image_path')->nullable();

            $table->boolean('is_featured')->default(false);
            $table->boolean('is_visible')->default(true);

            $table->string('status')->default('draft');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
