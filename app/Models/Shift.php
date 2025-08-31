<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'starting_cash',
        'ending_cash',
        'start_time',
        'end_time',
        'notes'
    ];

    protected $casts = [
        'starting_cash' => 'decimal:2',
        'ending_cash' => 'decimal:2',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
     public function isActive()
    {
        return is_null($this->end_time);
    }
}
