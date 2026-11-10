
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jamaah_documents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('jamaah_id')
                ->constrained('jamaahs')
                ->cascadeOnDelete();

            $table->string('document_type', 50);
            $table->string('document_name')->nullable();

            $table->string('file_path')->nullable();
            $table->string('original_name')->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('file_size')->nullable();

            $table->string('status', 30)->default('belum_diupload');

            $table->text('notes')->nullable();

            $table->foreignId('uploaded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('uploaded_at')->nullable();
            $table->timestamp('verified_at')->nullable();

            $table->timestamps();

            $table->unique(
                ['jamaah_id', 'document_type'],
                'jamaah_document_type_unique'
            );

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jamaah_documents');
    }
};
