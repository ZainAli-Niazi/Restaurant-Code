<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stock_logs', function (Blueprint $table) {
            $table->id();
            
            // Relationships
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained(); // Who performed the action
            $table->foreignId('order_id')->nullable()->constrained(); // If related to an order
            $table->foreignId('shift_id')->nullable()->constrained(); // If related to a shift
            
            // Stock details
            $table->integer('quantity')->comment('Positive for additions, negative for deductions');
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->decimal('total_cost', 10, 2)->nullable();
            
            // Tracking
            $table->string('reference')->nullable()->comment('PO Number, Invoice, etc');
            $table->enum('action_type', [
                'purchase',
                'sale', 
                'adjustment',
                'wastage',
                'return',
                'transfer'
            ]);
            
            // Metadata
            $table->text('notes')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('device_info')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('product_id');
            $table->index('created_at');
            $table->index('action_type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_logs');
    }
};