<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voters', function (Blueprint $table) {
            $table->id();

            $table->string('nid')->unique();
            $table->string('pin')->nullable();
            $table->string('formNo')->nullable();
            $table->string('sl_no')->nullable();

            $table->string('father_nid')->nullable();
            $table->string('mother_nid')->nullable();

            $table->string('religion')->nullable();
            $table->string('mobile')->nullable();

            $table->string('voterNo')->nullable();
            $table->string('voterArea')->nullable();

            $table->string('education')->nullable();
            $table->string('occupation')->nullable();
            $table->string('status')->nullable();

            $table->string('nameBangla');
            $table->string('nameEnglish')->nullable();

            $table->date('dateOfBirth')->nullable();
            $table->string('birthPlace')->nullable();

            $table->string('fatherName')->nullable();
            $table->string('motherName')->nullable();
            $table->string('spouseName')->nullable();

            $table->string('gender')->nullable();
            $table->string('bloodGroup')->nullable();

            $table->text('presentAddress')->nullable();
            $table->text('permanentAddress')->nullable();
            $table->text('address')->nullable();

            $table->string('image_photo')->nullable();
            $table->string('image_sign')->nullable();

            $table->date('issueDate')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voters');
    }
};
