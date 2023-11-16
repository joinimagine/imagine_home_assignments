<?php

namespace App\Services;

use App\Exceptions\OutOfStockException;
use Illuminate\Support\Collection;
use App\Models\Book;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class OrdersService
{

    public function checkPreOrderStockQuantity(Collection $orderedQuantitiesMap, Collection $stockQuantitiesMap)
    {
        $iterator = 0;

        foreach ($orderedQuantitiesMap as $book_id => $ordered_quantity) {
            if ($ordered_quantity > $stockQuantitiesMap[$iterator]) {
                $outOfStockBook = Book::find($book_id);
                throw new OutOfStockException('Sorry, the ordered quantity for the book "' . $outOfStockBook->title . '" is out of stock', 401);
            }

            $iterator++;
        }

        return true;
    }

    public function attatchOrderItems(Order $order, $ordered_books)
    {

        $order->orderItems()->saveMany($ordered_books);
    }


    public function updateStockQuantity(Collection $ordered_quantities, EloquentCollection $stock_books)
    {

        foreach ($stock_books as $stock_book) {

            $stock_book->update(['stock_quantity' => $stock_book->stock_quantity - $ordered_quantities[$stock_book->id]]);
        }
    }
}
