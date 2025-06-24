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
        Schema::create('workers_availability', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('worker_id');
            $table->string('name')->nullable();
            $table->json('services')->nullable();
            $table->string('availability_type')->default('weekly');
            $table->json('weekly_availability')->nullable();
            $table->json('locations')->nullable();
            $table->json('coordinates')->nullable();
            $table->text('preferences')->nullable();
            $table->json('expected_rate')->nullable();
            $table->timestamps();
        });

       ;

    }
    public function down(): void
    {
        Schema::dropIfExists('workers_availability');
    }
};
