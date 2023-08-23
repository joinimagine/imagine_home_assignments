<?php

namespace App\Http\Services\Cart;


interface CartService {

    public function get();

    public function add($bookId, $quantity);

    public function remove($bookId);
}
