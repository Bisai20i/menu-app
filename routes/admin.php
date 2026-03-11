<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\MenuCategoryController;
use App\Http\Controllers\MenuImageController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\RestaurantTableController;
use App\Http\Controllers\SubscriptionPlanController;
use App\Http\Controllers\AdminSubscriptionController;
use Illuminate\Support\Facades\Route;



Route::group(['prefix' => 'master', 'as' => 'master.'], function () {
    // Authentication Routes
    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])->name('login.submit');

    Route::get('/register', [AdminController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AdminController::class, 'register'])->name('register.submit');

    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    Route::get('/forget-password', [AdminController::class, 'forgetPassword'])->name('password.request');

    Route::middleware('auth:admin')->group(function () {

        // Dashboard
        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Menu Management
        Route::resource('menu-categories', MenuCategoryController::class);
        Route::resource('menu-items', MenuItemController::class);

        // Menu Gallery
        Route::get('menu-gallery', [MenuImageController::class, 'index'])->name('menu-gallery.index');
        Route::post('menu-gallery', [MenuImageController::class, 'store'])->name('menu-gallery.store');
        Route::patch('menu-gallery/{id}/status', [MenuImageController::class, 'updateStatus'])->name('menu-gallery.update-status');
        Route::patch('menu-gallery/{id}/order', [MenuImageController::class, 'updateOrder'])->name('menu-gallery.update-order');
        Route::delete('menu-gallery/{id}', [MenuImageController::class, 'destroy'])->name('menu-gallery.destroy');

        // Restaurant Tables
        Route::resource('restaurant-tables', RestaurantTableController::class);

        // Table QR Generator
        Route::get('table-qr', function () {
            return view('admin.pages.qr-generator');
        })->name('table-qr');

        // Admin Resource
        Route::resource('admins', AdminController::class);

        //Subscription Plans
        Route::resource('subscription-plans', SubscriptionPlanController::class);

        // Admin Management
        Route::resource('admin-management', \App\Http\Controllers\AdminManagementController::class);

        // Admin Subscriptions (Super Admin only)
        Route::get('admin-subscriptions', [AdminSubscriptionController::class, 'index'])->name('admin-subscriptions.index');
        Route::post('admin-subscriptions/assign', [AdminSubscriptionController::class, 'assign'])->name('admin-subscriptions.assign');
        Route::delete('admin-subscriptions/{adminId}/remove', [AdminSubscriptionController::class, 'remove'])->name('admin-subscriptions.remove');

        // Order Management
        Route::get('orders', \App\Livewire\Admin\OrderManagement::class)->name('orders.index');

        // Billing (for current logged-in admin)
        Route::get('billing', [AdminSubscriptionController::class, 'billing'])->name('billing');

        // Profile Management
        Route::get('profile', [\App\Http\Controllers\AdminManagementController::class, 'profile'])->name('profile');
        Route::post('profile', [\App\Http\Controllers\AdminManagementController::class, 'updateProfile'])->name('profile.update');
    });
});
