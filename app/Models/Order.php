<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItems;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;


    protected $fillable = ['total_amount', 'order_date', 'payment_status',];


    public function orderItems():HasMany
    {

        return $this->hasMany(OrderItems::class);
    }
}
