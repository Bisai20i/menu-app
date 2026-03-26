<?php

namespace App\Events;

use App\Models\RestaurantTable;
use App\Models\TableSession;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TableStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public RestaurantTable $table;
    public ?TableSession $session;

    public function __construct(RestaurantTable $table, ?TableSession $session = null)
    {
        $this->table   = $table;
        $this->session = $session;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('restaurant.' . $this->table->restaurant_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'TableStatusUpdated';
    }

    public function broadcastWith(): array
    {
        return [
            'table' => [
                'id'           => $this->table->id,
                'uuid'         => $this->table->uuid,
                'table_number' => $this->table->table_number,
                'status'       => $this->table->status,
                'is_active'    => $this->table->is_active,
            ],
            'session' => $this->session ? [
                'id'         => $this->session->id,
                'uuid'       => $this->session->uuid,
                'status'     => $this->session->status,
                'opened_at'  => $this->session->opened_at?->toISOString(),
                'closed_at'  => $this->session->closed_at?->toISOString(),
                'guest_count'=> $this->session->guest_count,
            ] : null,
        ];
    }
}