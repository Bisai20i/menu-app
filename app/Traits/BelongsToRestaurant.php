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
        static::addGlobalScope('restaurant', function (Builder $builder) {
            if (Auth::guard('admin')->check()) {
                $user = Auth::guard('admin')->user();

                // If user is a regular admin, only show data for their restaurant
                if (isset($user->restaurant_id)) {
                    $builder->where('restaurant_id', $user->restaurant_id);
                }
                
                // Note: Superadmins see everything because we don't apply the scope for them
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