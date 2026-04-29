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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->unique()->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method', 50)->nullable();
            $table->string('card_number', 20)->nullable();
            $table->string('card_holder', 100)->nullable();
            $table->string('card_expiry', 10)->nullable();
            $table->enum('payment_status', ['pending', 'completed', 'failed'])->default('pending');
            $table->string('transaction_id', 100)->nullable();
            $table->timestamp('payment_date')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
