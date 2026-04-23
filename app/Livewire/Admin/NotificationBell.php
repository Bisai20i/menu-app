<?php
namespace App\Livewire\Admin;

use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationBell extends Component
{
    public $notifications;
    public $unreadCount;

    public function mount()
    {
        $this->loadNotifications();
    }

    public function getListeners()
    {

        // info("regestring event:" . "echo-notification:App.Models.Admin." . auth('admin')->id());
        return [
            // The key is the listener, the value is the method to call

            "echo-notification:App.Models.Admin." . auth('admin')->id() => 'handleBroadcastedNotification',

        ];
    }

    // #[On('echo-notification:App.Models.Admin.{admin.id},notification')]
    public function handleBroadcastedNotification($event)
    {
        $this->unreadCount++;
        $this->loadNotifications();
        $this->dispatch('play-notification-sound');
    }

    public function placeholder()
    {
        return view('livewire.placeholders.notification-bell-placeholder');
    }

    public function loadNotifications()
    {
        $admin = auth('admin')->user();
        if ($admin) {
            $this->notifications = $admin->notifications()->take(5)->get();
            $this->unreadCount   = $admin->unreadNotifications()->count();
        } else {
            $this->notifications = collect();
            $this->unreadCount   = 0;
        }
    }

    public function markAsRead($notificationId)
    {
        $admin = Auth::guard('admin')->user();
        if ($admin) {
            $notification = $admin->notifications()->find($notificationId);
            if ($notification) {
                $notification->markAsRead();
                $this->loadNotifications();
            }
        }
    }

    public function markAllAsRead()
    {
        $admin = Auth::guard('admin')->user();
        if ($admin) {
            $admin->unreadNotifications->markAsRead();
            $this->loadNotifications();
        }
    }

    public function render()
    {
        return view('livewire.admin.notification-bell');
    }
}
