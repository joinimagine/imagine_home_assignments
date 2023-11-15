<?php

use App\Http\Controllers\AuthinticationController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShoppingCartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', [AuthinticationController::class, 'register']);
Route::post('/login', [AuthinticationController::class, 'login']);



Route::middleware(['auth:sanctum'])->group(function () {


    Route::get('/books/search', [BookController::class, 'search']);
    Route::get('/cart', [ShoppingCartController::class, 'getCartItems']);
    Route::post('/cart/add', [ShoppingCartController::class, 'addCartItem']);
    Route::delete('/cart/remove/{book}', [ShoppingCartController::class, 'removeCartItem']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders/place', [OrderController::class, 'store']);
    Route::apiResource('books', BookController::class);
});
