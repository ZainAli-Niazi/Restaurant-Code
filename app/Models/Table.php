<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Table extends Model
{
    protected $fillable = ['name', 'hall_id', 'capacity', 'status'];
    
    public function hall(): BelongsTo
    {
        return $this->belongsTo(Hall::class);
    }
    
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    
    public function heldOrders(): HasMany
    {
        return $this->hasMany(HeldOrder::class);
    }
}
