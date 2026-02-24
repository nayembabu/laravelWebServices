<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();

            $table->string('gateway')->default('bkash');
            $table->string('payment_id')->nullable();     // bKash paymentID
            $table->string('trx_id')->nullable();         // bKash trxID
            $table->decimal('amount', 10, 2);

            $table->string('status')->default('initiated'); // initiated|success|failed|cancelled
            $table->json('create_response')->nullable();
            $table->json('execute_response')->nullable();
            $table->string('error_message')->nullable();

            $table->timestamps();

            $table->index(['payment_id', 'trx_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
