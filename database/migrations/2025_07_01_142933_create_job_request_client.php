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
        Schema::create('job_request_client', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(\Illuminate\Support\Facades\DB::raw('(UUID())'));
            $table->foreignUuid('client_id')->constrained('users');
            $table->json('job_titles');
            $table->string('custom_job_title')->nullable();
            $table->string('location');
            $table->string('salary_range');
            $table->text('description');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('job_type', ['one-time', 'recurring']);
            $table->string('frequency')->nullable();
            $table->boolean('has_transportation')->default(false);
            $table->boolean('background_check')->default(false);
            $table->boolean('interview_required')->default(false);
            $table->enum('status', ['open', 'filled', 'cancelled'])->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_request_client');
    }
};
