<?php
namespace App\Livewire\Admin;

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

    public function placeholder()
    {
        return view('livewire.placeholders.notification-bell-placeholder');
    }

    public function loadNotifications()
    {
        // Get the currently authenticated admin
        $admin = Auth::guard('admin')->user();

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

    public function getListeners()
    {
        return [
            // If reverb/websockets are added later, listen here
            // 'echo-private:App.Models.Admin.'.$adminId.',.Illuminate\Notifications\Events\BroadcastNotificationCreated' => 'loadNotifications'
        ];
    }

    public function render()
    {
        return view('livewire.admin.notification-bell');
    }
}
