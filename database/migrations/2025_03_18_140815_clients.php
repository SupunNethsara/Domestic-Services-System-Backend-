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
        Schema::create('clients' , function (Blueprint $table){
            $table->id();
            $table->foreignId('user_id')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('mobile');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('client');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
