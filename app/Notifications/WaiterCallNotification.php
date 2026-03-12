<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\WaiterCall;

class WaiterCallNotification extends Notification
{
    use Queueable;

    public $waiterCall;

    /**
     * Create a new notification instance.
     */
    public function __construct(WaiterCall $waiterCall)
    {
        $this->waiterCall = $waiterCall;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'waiter_call_id' => $this->waiterCall->id,
            'table_number'   => $this->waiterCall->table->table_number ?? 'N/A',
            'session_uuid'   => $this->waiterCall->session->uuid ?? null,
        ];
    }
}
