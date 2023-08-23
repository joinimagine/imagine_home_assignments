<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'user_id'];
    public $timestamps = false;

    public static function boot() {

        parent::boot();

        self::creating(function($order) {

            $order->date = Carbon::now();
            $order->user_id = auth()->id();
            $order->total_price = 0;
        });
    }

    public function user(): BelongsTo {

        return $this->belongsTo(User::class);
    }

    public function books(): BelongsToMany {

        return $this->belongsToMany(Book::class)->withPivot(['quantity', 'unit_price']);
    }

    public function setTotalPriceAttribute($value) {

        $this->attributes['total_price'] = $value / 100;
    }

    public function getTotalPriceAttribute($value) {

        return $value * 100;
    }

    public static function getUserAllowedFilters() {

        return ['date'];
    }

    public static function getAdminAllowedFilters() {

        return ['date', 'user.name'];
    }


    public static function getUserAllowedIncludes() {

        return ['books'];
    }

    public static function getAdminAllowedIncludes() {

        return ['books', 'user'];
    }
}
