<?php

namespace App\Services;

use App\Exceptions\OutOfStockException;
use App\Http\Requests\CreateOrderItemsRequest;
use App\Models\Book;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection as SupportCollection;

class OrderItemsService
{

    public function calculateTotalAmount(SupportCollection $books_prices)
    {

        return $books_prices->sum();
    }


    public function checkStockQuantity(int $ordered_quantity, Book $book)
    {

        if ($ordered_quantity > $book->stock_quantity) {

            throw new OutOfStockException("Sorry, The Ordered quantity of this book" .  " $book->title " . "is more than the stock quantity", 401);
        } else return true;
    }


    public function checkExistingBookItem(User $user, Book $book_to_check, int $ordered_quantity):OrderItems
    {
        $existingOrderItem = $user->orderItems()
            ->whereNull('order_id')
            ->where('book_id', $book_to_check->id)
            ->first();

        if ($existingOrderItem) {
            $existingOrderItem->update(['quantity' => $existingOrderItem->quantity + $ordered_quantity]);
            return $existingOrderItem;
        } else {
            try {
                $orderItem = $user->orderItems()->create([
                    'book_id' => $book_to_check->id,
                    'book_price' => $book_to_check->price,
                    'quantity' => $ordered_quantity
                ]);

                return $orderItem;
            } catch (\Exception $e) {
                // Handle the exception (log, notify, etc.)
                // For example:
                Log::error('Error creating order item: ' . $e->getMessage());
                throw new \RuntimeException('Error processing order.');
            }
        }
    }
}
