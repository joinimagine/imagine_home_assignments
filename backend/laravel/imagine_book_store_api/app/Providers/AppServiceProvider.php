<?php

namespace App\Providers;

use App\Http\Controllers\Api\V1\Admin\AdminBookController;
use App\Http\Controllers\Api\V1\Admin\AdminOrderController;
use App\Http\Controllers\Api\V1\BookController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Services\BookGenres\BookGenreService;
use App\Http\Services\BookGenres\EloquentBookGenreService;
use App\Http\Services\Books\BookModificationService;
use App\Http\Services\Books\BookQueryService;
use App\Http\Services\Books\EloquentAdminBookService;
use App\Http\Services\Books\EloquentUserBookService;
use App\Http\Services\Cart\CartService;
use App\Http\Services\Cart\EloquentCartService;
use App\Http\Services\Orders\EloquentAdminOrderService;
use App\Http\Services\Orders\EloquentUserOrderService;
use App\Http\Services\Orders\OrderQueryService;
use App\Http\Services\Orders\OrderStoreService;
use App\Http\Services\Users\EloquentUserService;
use App\Http\Services\Users\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        /* Bind User Service */
        $this->app->bind(UserService::class, EloquentUserService::class);

        /* Bind Book Genre Service */
        $this->app->bind(BookGenreService::class, EloquentBookGenreService::class);

        /* Bind Cart Service */
        $this->app->bind(CartService::class, EloquentCartService::class);


        /* Bind Book Services */
        $this->app->bind(BookModificationService::class, EloquentAdminBookService::class);

        $this->app->when(AdminBookController::class)
                    ->needs(BookQueryService::class)
                    ->give(EloquentAdminBookService::class);

        $this->app->when(BookController::class)
                    ->needs(BookQueryService::class)
                    ->give(EloquentUserBookService::class);


        /* Bind Order Services */

        $this->app->bind(OrderStoreService::class, EloquentUserOrderService::class);


        $this->app->when(AdminOrderController::class)
            ->needs(OrderQueryService::class)
            ->give(EloquentAdminOrderService::class);

        $this->app->when(OrderController::class)
            ->needs(OrderQueryService::class)
            ->give(EloquentUserOrderService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
