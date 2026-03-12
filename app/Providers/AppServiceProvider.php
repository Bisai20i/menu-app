<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Order;
use App\Models\WaiterCall;
use App\Observers\OrderObserver;
use App\Observers\WaiterCallObserver;
use App\Policies\AdminPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * We use Gate::define() instead of Gate::policy() because this app uses
     * a custom 'admin' guard. Laravel's @can / Gate resolves the user from the
     * DEFAULT guard ('web'), which is null here — silently denying everything.
     * By defining gates manually and fetching the admin from auth('admin'), we
     * ensure the correct authenticated user is used for every ability check.
     */
    public function boot(): void
    {
        Order::observe(OrderObserver::class);
        WaiterCall::observe(WaiterCallObserver::class);

        $policy = new AdminPolicy();

        // Super admins bypass all gate checks
        Gate::before(function ($user, $ability) {
            // $user here comes from the default guard — we ignore it and check admin guard
            $admin = auth('admin')->user();
            if ($admin && $admin->is_super_admin) {
                return true;
            }
        });

        Gate::define('accessCreateMenu', function ($user = null) use ($policy) {
            $admin = auth('admin')->user();
            return $admin ? $policy->accessCreateMenu($admin) : false;
        });

        Gate::define('accessCreateGallery', function ($user = null) use ($policy) {
            $admin = auth('admin')->user();
            return $admin ? $policy->accessCreateGallery($admin) : false;
        });

        Gate::define('accessCreateRestaurantTable', function ($user = null) use ($policy) {
            $admin = auth('admin')->user();
            return $admin ? $policy->accessCreateRestaurantTable($admin) : false;
        });

        Gate::define('deleteRecord', function ($user = null, $record = null) use ($policy) {
            $admin = auth('admin')->user();
            return $admin && $record ? $policy->deleteRecord($admin, $record) : false;
        });

        Gate::define('updateRecord', function ($user = null, $record = null) use ($policy) {
            $admin = auth('admin')->user();
            return $admin && $record ? $policy->updateRecord($admin, $record) : false;
        });
    }
}
