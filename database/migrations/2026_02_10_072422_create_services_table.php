<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');                       // Service name
            $table->decimal('rate', 12, 2);              // Service price/rate
            $table->enum('status', ['active','inactive'])->default('active'); // Service availability
            $table->string('category')->nullable();      // Optional category
            $table->text('description')->nullable();     // Optional description
            $table->integer('min_qty')->default(1);      // Minimum order quantity
            $table->integer('max_qty')->nullable();      // Maximum order quantity
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
