<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\AddRequest;
use App\Http\Resources\CartResource;
use App\Http\Services\Cart\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(Request $request, CartService $cartService)
    {
        $this->setConstruct($request, CartResource::class);
        $this->cartService = $cartService;
    }

    public function get() {

        return CartResource::collection($this->cartService->get());
    }

    public function add(AddRequest $request) {

        return CartResource::collection($this->cartService->add($request->get('book_id'), $request->get('quantity')));
    }

    public function remove(Request $request) {

        return CartResource::collection($this->cartService->remove($request->get('book_id')));
    }
}
