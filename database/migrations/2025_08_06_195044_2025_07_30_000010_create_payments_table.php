<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method')->default('cash'); // cash, card, mobile, etc.
            $table->string('transaction_id')->nullable(); // For card/mobile payments
            $table->string('payment_status')->default('completed'); // completed, pending, failed
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->constrained(); // Staff who processed payment
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('order_id');
            $table->index('payment_method');
            $table->index('payment_status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};