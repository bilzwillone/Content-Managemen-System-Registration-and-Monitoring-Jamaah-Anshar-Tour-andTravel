
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jamaahs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('prospect_id')
                ->unique()
                ->constrained('prospects')
                ->restrictOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->unique()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('package_id')
                ->nullable()
                ->constrained('packages')
                ->nullOnDelete();

            $table->string('name');
            $table->string('phone', 30);
            $table->string('email')->nullable();
            $table->text('address')->nullable();

            $table->date('registration_date');

            $table->string('status', 30)->default('terdaftar');

            $table->timestamps();

            $table->index('status');
            $table->index('registration_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jamaahs');
    }
};
