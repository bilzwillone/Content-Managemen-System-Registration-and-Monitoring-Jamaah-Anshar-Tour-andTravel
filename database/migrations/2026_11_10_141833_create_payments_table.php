
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('jamaah_id')
                ->constrained('jamaahs')
                ->restrictOnDelete();

            $table->string('order_id')->unique();

            $table->string('payment_type', 30);

            $table->decimal('amount', 15, 2);
            $table->char('currency', 3)->default('IDR');

            $table->string('gateway_provider', 50)->nullable();
            $table->string('gateway_transaction_id')->nullable();
            $table->string('payment_method', 50)->nullable();
            $table->text('payment_url')->nullable();

            $table->string('status', 30)->default('pending');

            $table->date('due_date')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->text('notes')->nullable();
            $table->json('gateway_response')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['jamaah_id', 'status']);
            $table->index('payment_type');
            $table->index('gateway_provider');

            $table->unique(
                ['gateway_provider', 'gateway_transaction_id'],
                'payments_gateway_transaction_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
