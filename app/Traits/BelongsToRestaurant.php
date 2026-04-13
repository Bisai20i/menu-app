<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait BelongsToRestaurant
{
    /**
     * The "booted" method of the model.
     */
    protected static function bootBelongsToRestaurant(): void
    {
        // 1. Automatically filter results for the logged-in Admin
        //    The auth check alone is sufficient — public/guest routes have no admin session.
        //    The previous route-path check ($request->is('master/*')) was fragile and
        //    silently skipped scope application on any non-matching route.
        static::addGlobalScope('restaurant', function (Builder $builder) {
            if (Auth::guard('admin')->check()) {
                $user = Auth::guard('admin')->user();

                // Always scope to the admin's restaurant_id
                $builder->where($builder->getModel()->getTable() . '.restaurant_id', $user->restaurant_id);
            }
        });

        // 2. Automatically assign the restaurant_id when creating a new record
        static::creating(function ($model) {
            if (Auth::guard('admin')->check()) {
                $user = Auth::guard('admin')->user();

                // If an admin is creating a record and it doesn't have a restaurant_id set yet
                if (isset($user->restaurant_id) && empty($model->restaurant_id)) {
                    $model->restaurant_id = $user->restaurant_id;
                }
            }
        });
    }

    /**
     * Relationship to the Restaurant
     */
    public function restaurant()
    {
        return $this->belongsTo(\App\Models\Restaurant::class);
    }
}