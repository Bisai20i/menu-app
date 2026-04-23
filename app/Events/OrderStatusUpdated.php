<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Broadcast on the device-specific public channel so the customer's Vue app
     * can receive the update without authentication, and also on the private
     * restaurant channel for the admin Livewire dashboard.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('orders.' . $this->order->session->device_id),
            new PrivateChannel('restaurant.' . $this->order->restaurant_id),
        ];
    }

    /**
     * Broadcast under a clean, predictable event name that Vue Echo can listen for.
     */
    public function broadcastAs(): string
    {
        return 'OrderStatusUpdated';
    }

    /**
     * Data payload sent to the client.
     */
    public function broadcastWith(): array
    {
        return [
            'order' => [
                'uuid'         => $this->order->uuid,
                'status'       => $this->order->status,
                'is_paid'      => $this->order->is_paid,
                'total_amount' => $this->order->total_amount,
                'needs_user_confirmation' => $this->order->needs_user_confirmation,
            ],
        ];
    }
}
