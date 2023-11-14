<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\OrderItems\CreateOrderItemsRequest;
use App\Http\Requests\OrderItems\GetOrderItemsRequest;
use App\Models\Book;
use App\Http\Resources\V1\BookResource;
use App\Http\Resources\V1\OrderItemsResource;
use App\Services\OrderItemsService;
use Illuminate\Http\Request;

class ShoppingCartController extends Controller
{
    public function getCartItems(GetOrderItemsRequest $request)
    {

        $books_ids = array_column($request->validated('added_books'), 'book_id');
        $shopping_cart_books = Book::find($books_ids);

        return response()->json([

            'success' => true,
            'shopping_cart_items' => OrderItemsResource::collection($request->user()->orderItems),
            'list_of_added books' => BookResource::collection($shopping_cart_books)

        ], 201);
    }


    public function addCartItem(CreateOrderItemsRequest $request, OrderItemsService $order_items_service)
    {

        $added_book = $request->validated('added_books');
        //Check if the orderd quantity is less or equal than the stock quantity of the book

        $ordered_quantity = $added_book[0]['quantity'];

        $book_to_check = Book::findOrFail($added_book[0]['book_id']);

        $order_items_service->checkStockQuantity($ordered_quantity, $book_to_check);

        // Check if book existed in user's shopping cart

        $order_item = $order_items_service->checkExistingBookItem($request->user(), $book_to_check, $ordered_quantity);


        // Check if order item quantity is less or equal than the book stock quntity


        return response()->json([

            'success' => true,
            'ordered_book' => new OrderItemsResource($order_item),
            'message' => 'Book has been added to the Cart'

        ]);
    }

    public function removeCartItem(Book $book, Request $request)
    {

        $book_to_delete = $request->user()->orderItems()->where('book_id', $book->id)->first();
        $book_to_delete->delete();

        return response()->json([
            'success' => true,
            'updated_shopping_cart' => OrderItemsResource::collection($request->user()->orderItems)
        ], 201);
    }
}
