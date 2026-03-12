<?php

namespace App\Observers;

use App\Models\WaiterCall;
use App\Models\Admin;
use App\Notifications\WaiterCallNotification;
use Illuminate\Support\Facades\Notification;

class WaiterCallObserver
{
    /**
     * Handle the WaiterCall "created" event.
     */
    public function created(WaiterCall $waiterCall): void
    {
        // Get all admins for the restaurant
        $admins = Admin::where('restaurant_id', $waiterCall->restaurant_id)->get();

        if ($admins->isNotEmpty()) {
            Notification::send($admins, new WaiterCallNotification($waiterCall));
        }
    }

    /**
     * Handle the WaiterCall "updated" event.
     */
    public function updated(WaiterCall $waiterCall): void
    {
        //
    }

    /**
     * Handle the WaiterCall "deleted" event.
     */
    public function deleted(WaiterCall $waiterCall): void
    {
        //
    }

    /**
     * Handle the WaiterCall "restored" event.
     */
    public function restored(WaiterCall $waiterCall): void
    {
        //
    }

    /**
     * Handle the WaiterCall "force deleted" event.
     */
    public function forceDeleted(WaiterCall $waiterCall): void
    {
        //
    }
}
