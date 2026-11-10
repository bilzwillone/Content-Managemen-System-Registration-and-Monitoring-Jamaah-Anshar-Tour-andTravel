
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prospect_follow_ups', function (Blueprint $table) {
            $table->id();

            $table->foreignId('prospect_id')
                ->constrained('prospects')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('channel', 30)->default('whatsapp');
            $table->dateTime('activity_at');
            $table->text('summary');
            $table->text('notes')->nullable();
            $table->dateTime('next_follow_up_at')->nullable();
            $table->timestamps();
            $table->index('activity_at');
            $table->index('next_follow_up_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prospect_follow_ups');
    }
};
