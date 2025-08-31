<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'table_number',
        'status',
        'sub_total',
        'service_charges',
        'discount_amount',
        'total_amount',
        'paid_amount',
        'balance_amount',
        'return_amount'
    ];

     protected $casts = [
        'sub_total' => 'decimal:2',
        'service_charges' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_amount' => 'decimal:2',
        'return_amount' => 'decimal:2',
    ];
    
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    
    public function stockLogs(): HasMany
    {
        return $this->hasMany(StockLog::class);
    }
}