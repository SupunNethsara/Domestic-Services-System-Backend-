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
        Schema::create('workers_bank_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')->constrained('users');
            $table->string('account_holder_name');
            $table->string('account_number');
            $table->string('bank_name');
            $table->string('branch_name');
            $table->string('branch_code');
            $table->string('account_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workers_bank_details');
    }
};
