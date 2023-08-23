<?php

namespace App\Http\Services\Cart;


class EloquentCartService implements CartService {

    public function get()
    {
        return auth()->user()->cart;
    }

    public function add($bookId, $quantity)
    {
        $cart = auth()->user()->cart();

        $cartBook = $cart->where('book_id', $bookId)->first();

        if($cartBook) {

            $cartBook->pivot->quantity = $quantity;
            $cartBook->pivot->save();
        }
        else {
            $cart->attach($bookId, [
                'quantity' => $quantity
            ]);
        }

        return auth()->user()->cart()->get();
    }

    public function remove($bookId)
    {
        auth()->user()->cart()->detach($bookId);

        return auth()->user()->cart()->get();
    }
}
