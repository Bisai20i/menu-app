<?php

use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebController;

require __DIR__ . '/admin.php';



Route::get('/', [WebController::class , 'index']);


Route::get('/app/{any?}', function () {
    return view('app');
})->where('any', '.*');

Route::get('/{slug}', [MenuController::class , 'show'])->name('public.menu');
// Route::get('/{slug}/{uuid}', [MenuController::class, 'show'])->name('public.menu.table');