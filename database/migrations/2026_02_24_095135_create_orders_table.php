<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('invoice')->unique();          // merchantInvoiceNumber
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending'); // pending|paid|failed|cancelled
            $table->string('customer_name')->nullable();  // optional
            $table->string('customer_phone')->nullable(); // optional
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
