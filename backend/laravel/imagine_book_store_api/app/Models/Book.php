<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'author', 'price', 'quantity', 'book_genre_id'];

    public function bookGenre(): BelongsTo {

        return $this->belongsTo(BookGenre::class);
    }

    public function setPriceAttribute($value) {

        $this->attributes['price'] = $value / 100;
    }

    public function getPriceAttribute($value) {

        return $value * 100;
    }

    public static function getAllowedFilters() {

        return [
            'title',
            'author',
            'bookGenre.name'
        ];
    }

    public static function getAllowedIncludes() {

        return ['bookGenre'];
    }

    public function updateQuantity($quantity) {

        $this->quantity -= $quantity;

        $this->save();
    }
}
