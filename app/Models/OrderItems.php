<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Book;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItems extends Model
{
    use HasFactory;


    protected $fillable = ['book_id', 'quantity', 'book_price', 'payment_status'];

    public function book(): BelongsTo
    {


        return $this->belongsTo(Book::class);
    }
}
