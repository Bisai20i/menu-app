<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Admin;
use App\Notifications\NewOrderNotification;
use Illuminate\Support\Facades\Notification;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        // Get all admins for the restaurant
        $admins = Admin::where('restaurant_id', $order->restaurant_id)->get();

        if ($admins->isNotEmpty()) {
            Notification::send($admins, new NewOrderNotification($order));
        }
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
