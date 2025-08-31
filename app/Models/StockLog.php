<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockLog extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'order_id',
        'shift_id',
        'quantity',
        'unit_cost',
        'total_cost',
        'reference',
        'action_type',
        'notes'
    ];
    
    protected $casts = [
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}