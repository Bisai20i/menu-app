<?php

use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;


require __DIR__.'/admin.php';

Route::get('/', function () {
    return view('main');
});

Route::get('/simple-menu', function () {
    return view('menus.simple-menu');
});

Route::get('/{slug}', [MenuController::class, 'show'])->name('public.menu');

Route::view('detailed-menu', 'menus.detailed-menu');