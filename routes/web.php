<?php

use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebController;

require __DIR__ . '/admin.php';



Route::get('/', [WebController::class , 'index']);
Route::get('/contact', [WebController::class, 'index'])->name('contact');
Route::post('/contact', [WebController::class, 'storeContact'])->name('contact.store');

Route::get('/articles', [WebController::class, 'articles'])->name('articles.index');
Route::get('/articles/{slug}', [WebController::class, 'showArticle'])->name('articles.show');


Route::get('/restaurant/{any?}', [MenuController::class, 'index'])->where('any', '.*');

Route::get('/{slug}', [MenuController::class , 'show'])->name('public.menu');
// Route::get('/{slug}/{uuid}', [MenuController::class, 'show'])->name('public.menu.table');