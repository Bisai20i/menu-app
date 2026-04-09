<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminManagementController;
use App\Http\Controllers\AdminSubscriptionController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\MenuCategoryController;
use App\Http\Controllers\MenuImageController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\OrderHistoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RestaurantTableController;
use App\Http\Controllers\SubscriptionPlanController;
use App\Http\Controllers\TableViewController;
use App\Http\Controllers\TestimonialController;
use App\Livewire\Admin\OrderManagement;
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
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

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

        // Review Management
        Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');

        // Table QR Generator
        Route::get('table-qr', function () {
            return view('admin.pages.qr-generator');
        })->name('table-qr');

        // Admin Resource
        Route::resource('admins', AdminController::class);

        // System Management (Super Admin only)
        Route::group(['middleware' => [function ($request, $next) {
            if (!auth('admin')->user()->is_super_admin) {
                abort(403);
            }
            return $next($request);
        }]], function () {
            Route::resource('testimonials', TestimonialController::class);
            Route::resource('faqs', FaqController::class);
            Route::resource('articles', ArticleController::class);
            Route::resource('subscription-plans', SubscriptionPlanController::class);
            
            // Contact Messages
            Route::patch('contacts/{id}/toggle-read', [ContactController::class, 'toggleRead'])->name('contacts.toggle-read');
            Route::resource('contacts', ContactController::class)->only(['index', 'show', 'destroy']);

            // Admin Subscriptions
            Route::get('admin-subscriptions', [AdminSubscriptionController::class, 'index'])->name('admin-subscriptions.index');
            Route::post('admin-subscriptions/assign', [AdminSubscriptionController::class, 'assign'])->name('admin-subscriptions.assign');
            Route::delete('admin-subscriptions/{adminId}/remove', [AdminSubscriptionController::class, 'remove'])->name('admin-subscriptions.remove');

            // Reports
            Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
            Route::get('reports/system', [ReportController::class, 'system'])->name('reports.system');
            Route::get('reports/{restaurant_id}', [ReportController::class, 'show'])->name('reports.show');
        });

        // Admin Management
        Route::resource('admin-management', AdminManagementController::class);

        // Order Management
        Route::get('orders', OrderManagement::class)->name('orders.index');
        Route::get('order-history', [OrderHistoryController::class, 'index'])->name('order-history.index');
        Route::livewire('table/sessions', 'pages::admin.table-sessions')->name('table-sessions');

        // Billing (for current logged-in admin)
        Route::get('billing', [AdminSubscriptionController::class, 'billing'])->name('billing');

        // Profile Management
        Route::get('profile', [AdminManagementController::class, 'profile'])->name('profile');
        Route::post('profile', [AdminManagementController::class, 'updateProfile'])->name('profile.update');

        //get all notifications
        Route::livewire('/notifications', 'pages::admin.all-notifications')->name('notifications.index');

        //table lists and table session management routes
        Route::get('/tables', [TableViewController::class, 'index'])
            ->name('tables.index');
        // Fetch live sessions for a table (sidebar)
        Route::get('/tables/{uuid}/sessions', [TableViewController::class, 'showSession'])->name('tables.sessions');

        // Close a session
        Route::post('/sessions/{uuid}/close', [TableViewController::class, 'closeSession'])
            ->name('sessions.close');
    });
});
