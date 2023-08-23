<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookGenre extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function books() {

        return $this->hasMany(Book::class);
    }

    public static function getAllowedIncludes() {

        return ['books'];
    }

    public static function getAllowedFilters() {

        return ['name'];
    }
}
