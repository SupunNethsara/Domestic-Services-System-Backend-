<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('username')->nullable();
            $table->text('about')->nullable();
            $table->string('first_name')->nullable();;
            $table->string('last_name')->nullable();;
            $table->string('email')->unique();
            $table->string('country')->nullable();;
            $table->string('address')->nullable();;
            $table->string('city')->nullable();;
            $table->string('province')->nullable();;
            $table->string('profile_image')->nullable();
            $table->string('cover_image')->nullable();
            $table->timestamps();


        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
