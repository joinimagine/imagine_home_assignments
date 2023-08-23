<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use \App\Http\Controllers\Api\V1\Admin\AdminBookController;
use \App\Http\Controllers\Api\V1\Admin\BookGenreController;
use \App\Http\Controllers\Api\V1\BookController;
use \App\Http\Controllers\Api\V1\OrderController;
use \App\Http\Controllers\Api\V1\Admin\AdminOrderController;
use \App\Http\Controllers\Api\V1\CartController;
use \App\Models\Role;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(static function() {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::middleware('auth:sanctum')->group(static function () {


        /* Book Resource */
        Route::get('books', [BookController::class, 'index']);
        Route::get('books/{id}', [BookController::class, 'show']);

        /* Cart Resource */
        Route::get('cart', [CartController::class, 'get']);
        Route::post('cart', [CartController::class, 'add']);
        Route::delete('cart', [CartController::class, 'remove']);

        /* Order Resource */
        Route::get('orders', [OrderController::class, 'index']);
        Route::get('orders/{id}', [OrderController::class, 'show']);
        Route::post('orders', [OrderController::class, 'store']);

        Route::post('logout', [AuthController::class, 'logout']);

        /* ADMIN ROUTES */
        Route::prefix('admin')->middleware('role:' . Role::getAdminRole())->group(static function() {

            Route::resource('book-genres', BookGenreController::class);
            Route::resource('books', AdminBookController::class);
            Route::get('orders', [AdminOrderController::class, 'index']);
            Route::get('orders/{id}', [AdminOrderController::class, 'show']);
        });
    });
});

Route::fallback(function() {
    return response()->json([
        'message' => 'Hm, how did you get here anyway !',
    ], 404);
});
