<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hall extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'status'];

    public function tables() 
    {
        return $this->hasMany(Table::class);
    }
}
