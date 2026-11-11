
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departure_checklists', function (Blueprint $table) {
            $table->id();

            $table->foreignId('departure_preparation_id')
                ->constrained('departure_preparations')
                ->cascadeOnDelete();

            $table->foreignId('jamaah_id')
                ->constrained('jamaahs')
                ->restrictOnDelete();

            $table->string('category', 50);
            $table->string('item_name');
            $table->boolean('is_required')->default(true);

            $table->string('status', 30)->default('belum');

            $table->text('notes')->nullable();

            $table->foreignId('checked_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->dateTime('checked_at')->nullable();

            $table->timestamps();

            $table->unique(
                ['departure_preparation_id', 'jamaah_id', 'item_name'],
                'departure_checklist_item_unique'
            );

            $table->index(['category', 'status']);
            $table->index('jamaah_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departure_checklists');
    }
};
