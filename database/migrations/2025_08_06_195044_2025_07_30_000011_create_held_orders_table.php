<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('held_orders', function (Blueprint $table) {
            $table->id();
            $table->string('hold_reference')->unique(); // e.g. HOLD-ABC123
            $table->foreignId('table_id')->nullable()->constrained();
            $table->enum('order_type', ['dine-in', 'takeaway', 'delivery'])->default('dine-in');
            $table->decimal('sub_total', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('service_charge', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->text('customer_note')->nullable();
            $table->foreignId('user_id')->constrained(); // Staff who held the order
            $table->foreignId('shift_id')->constrained();
            $table->timestamp('held_at')->useCurrent();
            $table->timestamp('resumed_at')->nullable();
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('hold_reference');
            $table->index('user_id');
            $table->index('held_at');
        });

        Schema::create('held_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('held_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('total_price', 10, 2);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('held_order_items');
        Schema::dropIfExists('held_orders');
    }
};