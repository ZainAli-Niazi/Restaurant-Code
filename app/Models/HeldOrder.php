<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HeldOrder extends Model
{
    protected $fillable = [
        'hold_reference', 'table_id', 'order_type', 'sub_total', 'discount_amount', 
        'service_charge', 'total_amount', 'customer_note', 'user_id', 'shift_id', 
        'held_at', 'resumed_at'
    ];
    
    protected $casts = [
        'sub_total' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'service_charge' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'held_at' => 'datetime',
        'resumed_at' => 'datetime',
    ];
    
    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }
    
}