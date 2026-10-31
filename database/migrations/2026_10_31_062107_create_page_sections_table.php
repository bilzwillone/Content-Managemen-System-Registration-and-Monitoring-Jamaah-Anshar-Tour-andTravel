
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
        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();

            // Relasi dengan halaman
            $table->foreignId('page_id')
                ->constrained('pages')
                ->cascadeOnDelete();

            // Identitas section
            $table->string('section_type');
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();

            // Konten dan media
            $table->json('content')->nullable();
            $table->string('image_path')->nullable();

            // Tombol atau call to action
            $table->string('button_text')->nullable();
            $table->string('button_url')->nullable();

            // Pengaturan tampilan
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);

            // Status konten
            $table->string('status')->default('draft');

            $table->timestamps();

            // Index untuk pengurutan section
            $table->index(['page_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_sections');
    }
};
