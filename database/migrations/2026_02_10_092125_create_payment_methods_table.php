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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
        
            $table->string('name'); // bKash, Nagad, Bank, USDT
            $table->enum('type', ['mobile','bank','crypto','card']);
        
            $table->string('label'); // Personal / Company
            $table->string('address'); // number / wallet / account
        
            $table->string('account_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('branch')->nullable();
            $table->string('routing_number')->nullable();
        
            $table->string('wallet_network')->nullable(); // TRC20, ERC20
        
            $table->text('note')->nullable();
        
            $table->enum('status', ['active','inactive'])->default('active');
        
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
