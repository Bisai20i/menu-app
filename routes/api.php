<?php

use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;

// Public API routes for menu data
Route::get('/menu/{restaurant_slug}/{table_uuid}', [MenuController::class, 'apiShow'])
    ->name('api.menu.show');

// Customer calls waiter to their table
Route::post('/waiter/call', [MenuController::class, 'callWaiter'])
    ->name('api.waiter.call');

// Customer places an order
Route::post('/orders', [MenuController::class, 'placeOrder'])
    ->name('api.orders.place');

Route::get('/sessions/{session_uuid}/orders', [MenuController::class, 'getSessionOrders'])
    ->name('api.sessions.orders');

Route::post('/orders/{order_uuid}/cancel', [MenuController::class, 'cancelOrder'])
    ->name('api.orders.cancel');
// Authenticated user route
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
