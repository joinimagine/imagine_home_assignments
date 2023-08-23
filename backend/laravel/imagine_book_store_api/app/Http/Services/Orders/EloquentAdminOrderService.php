<?php


namespace App\Http\Services\Orders;


use App\Models\Order;
use Illuminate\Support\Facades\Config;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EloquentAdminOrderService implements OrderQueryService
{
    public function index($perPage, $page) {

        return QueryBuilder::for(Order::class)
                    ->allowedIncludes(Order::getAdminAllowedIncludes())
                    ->allowedFilters(Order::getAdminAllowedFilters())
                    ->defaultSort('-id')
                    ->paginate($perPage, ['*'], 'page', $page);
    }

    public function show($id) {

        $order = Order::find($id);

        if(!$order) {

            throw new NotFoundHttpException(Config::get('messages.api.orders.not_found'));
        }

        return $order->load(Order::getAdminAllowedIncludes());
    }
}
