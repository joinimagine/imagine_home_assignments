<?php

namespace App\Http\Controllers\V1;

use App\Enums\OrderStatus;
use App\Http\Resources\V1\OrderResource;
use App\Models\Order;
use App\Services\OrderItemsService;
use App\Services\OrdersService;
use Illuminate\Http\Request;
use App\Models\Book;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Order::class);

        return response()->json([

            'success' => true,
            'previous_orders' => OrderResource::collection($request->user()->orders()->paginate(30))

        ], 201);
    }

    public function store(Request $request, OrdersService $ordersService, OrderItemsService $orderItemsService)
    {

        // Re-check the stock quantity beacuse of the elapsed time between insrting into shopping cart and making the order

        $ordered_books = $request->user()->orderItems()
            ->whereNull('order_id')
            ->get();

        $ordered_quantities =  $ordered_books->pluck('quantity', 'book_id');

        $stock_books = Book::find($ordered_quantities->keys());

        $stock_quantities = $stock_books->pluck('stock_quantity');
        $book_prices = $ordered_books->pluck('book_price');

        if ($ordersService->checkPreOrderStockQuantity($ordered_quantities, $stock_quantities)) {
            $total_amount =  $orderItemsService->calculateTotalAmount($book_prices);
        }


        // create collection contatins the ordered quantity and the corresponding book id

        $order =  $request->user()->orders()->create([

            'order_date' => now(),
            'total_amount' =>  $total_amount,
            'payment_status' => OrderStatus::PENDING
        ]);

        $ordersService->attatchOrderItems($order, $ordered_books);

        $ordersService->updateStockQuantity($ordered_quantities, $stock_books);

        return response()->json([

            'success' => true,
            'message' => 'Your Order has been placed successfully'
        ], 201);
    }
}
