<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{


    use HasFactory;
    
    protected $fillable = ['title', 'author', 'genre', 'price'];


    public function orderItems(): HasMany
    {

        return $this->hasMany(OrderItems::class);
    }
}
