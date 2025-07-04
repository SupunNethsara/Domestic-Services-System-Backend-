<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('worker_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users');
            $table->unsignedBigInteger('worker_id');
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending');
            $table->string('stripe_payment_id')->nullable();
            $table->string('transfer_id')->nullable();
            $table->decimal('net_amount', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('worker_id')->references('id')->on('users');


            $table->index('client_id');
            $table->index('worker_id');
            $table->index('stripe_payment_id');
            $table->index(['status', 'updated_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worker_payments');
    }
};
