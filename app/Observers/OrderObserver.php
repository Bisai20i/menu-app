<?php

namespace App\Observers;

use App\Models\Admin;
use App\Models\DailyRestaurantStat;
use App\Models\Order;
use App\Notifications\NewOrderNotification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     *
     * Increments the total_orders counter for the day the order was placed.
     */
    public function created(Order $order): void
    {
        // Notify all restaurant admins about the new order
        $admins = Admin::where('restaurant_id', $order->restaurant_id)->get();

        if ($admins->isNotEmpty()) {
            Notification::send($admins, new NewOrderNotification($order));
        }

        // Track total orders for today
        DailyRestaurantStat::updateOrCreate(
            [
                'restaurant_id' => $order->restaurant_id,
                'date'          => now()->toDateString(),
            ],
            []
        )->increment('total_orders');
    }

    /**
     * Handle the Order "updated" event.
     *
     * When an order transitions to paid, credit the revenue against the
     * payment date (paid_at) — not the order creation date — so reports
     * reflect the actual day cash was collected.
     */
    public function updated(Order $order): void
    {
        // Only act when is_paid just flipped from false → true
        if (! $order->wasChanged('is_paid') || ! $order->is_paid) {
            return;
        }

        // Use paid_at date for the revenue credit; fall back to today
        $paymentDate = $order->paid_at
            ? $order->paid_at->toDateString()
            : now()->toDateString();

        DailyRestaurantStat::updateOrCreate(
            [
                'restaurant_id' => $order->restaurant_id,
                'date'          => $paymentDate,
            ],
            []
        )->increment('total_revenue', (float) $order->total_amount);

        // Bust the dashboard cache so admins see updated revenue immediately
        Cache::forget("dashboard_stats_res_{$order->restaurant_id}");
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
