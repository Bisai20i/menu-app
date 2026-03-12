<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;
 
    public string $filter = 'all'; // all | unread | read
    public string $typeFilter = 'all'; // all | order | waiter
 
    protected $queryString = ['filter', 'typeFilter'];
 
    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
        $this->resetPage();
    }
 
    public function setTypeFilter(string $type): void
    {
        $this->typeFilter = $type;
        $this->resetPage();
    }
 
    public function markAsRead(string $notificationId): void
    {
        $admin = Auth::guard('admin')->user();
        if ($admin) {
            $notification = $admin->notifications()->find($notificationId);
            if ($notification && ! $notification->read_at) {
                $notification->markAsRead();
            }
        }
    }
 
    public function markAllAsRead(): void
    {
        $admin = Auth::guard('admin')->user();
        if ($admin) {
            $admin->unreadNotifications->markAsRead();
        }
    }
 
    public function render()
    {
        $admin = Auth::guard('admin')->user();
 
        $query = $admin->notifications();
 
        // Read/unread filter
        if ($this->filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($this->filter === 'read') {
            $query->whereNotNull('read_at');
        }
 
        // Type filter
        if ($this->typeFilter === 'order') {
            $query->where('type', 'App\Notifications\NewOrderNotification');
        } elseif ($this->typeFilter === 'waiter') {
            $query->where('type', 'App\Notifications\WaiterCallNotification');
        }
 
        $notifications = $query->latest()->paginate(15);
        $unreadCount   = $admin->unreadNotifications()->count();
 
        return $this->view([
            'notifications' => $notifications,
            'unreadCount'   => $unreadCount,
        ]);
    }
};
?>

{{-- resources/views/livewire/admin/all-notifications.blade.php --}}
<div>
    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h5 class="mb-0 fw-semibold">Notifications</h5>
            <p class="text-muted mb-0 mt-1" style="font-size: 0.85rem;">
                @if($unreadCount > 0)
                    You have <strong>{{ $unreadCount }}</strong> unread notification{{ $unreadCount > 1 ? 's' : '' }}
                @else
                    All caught up &mdash; no unread notifications
                @endif
            </p>
        </div>
        @if($unreadCount > 0)
            <button wire:click="markAllAsRead"
                    class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1">
                <i class="bx bx-check-double"></i>
                Mark all as read
            </button>
        @endif
    </div>

    {{-- Filters --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-2 px-3">
            <div class="d-flex flex-wrap align-items-center gap-3">

                {{-- Read status filter --}}
                <div class="d-flex align-items-center gap-1">
                    <span class="text-muted me-1" style="font-size: 0.8rem;">Status:</span>
                    <button wire:click="setFilter('all')"
                            class="btn btn-sm {{ $filter === 'all' ? 'btn-primary' : 'btn-outline-secondary' }}"
                            style="font-size: 0.78rem; padding: 0.2rem 0.6rem;">
                        All
                    </button>
                    <button wire:click="setFilter('unread')"
                            class="btn btn-sm {{ $filter === 'unread' ? 'btn-primary' : 'btn-outline-secondary' }}"
                            style="font-size: 0.78rem; padding: 0.2rem 0.6rem;">
                        Unread
                        @if($unreadCount > 0)
                            <span class="badge bg-danger rounded-pill ms-1" style="font-size: 0.65rem;">{{ $unreadCount }}</span>
                        @endif
                    </button>
                    <button wire:click="setFilter('read')"
                            class="btn btn-sm {{ $filter === 'read' ? 'btn-primary' : 'btn-outline-secondary' }}"
                            style="font-size: 0.78rem; padding: 0.2rem 0.6rem;">
                        Read
                    </button>
                </div>

                <div class="vr d-none d-sm-block"></div>

                {{-- Type filter --}}
                <div class="d-flex align-items-center gap-1">
                    <span class="text-muted me-1" style="font-size: 0.8rem;">Type:</span>
                    <button wire:click="setTypeFilter('all')"
                            class="btn btn-sm {{ $typeFilter === 'all' ? 'btn-dark' : 'btn-outline-secondary' }}"
                            style="font-size: 0.78rem; padding: 0.2rem 0.6rem;">
                        All
                    </button>
                    <button wire:click="setTypeFilter('order')"
                            class="btn btn-sm {{ $typeFilter === 'order' ? 'btn-success' : 'btn-outline-secondary' }}"
                            style="font-size: 0.78rem; padding: 0.2rem 0.6rem;">
                        <i class="bx bx-cart me-1"></i>Orders
                    </button>
                    <button wire:click="setTypeFilter('waiter')"
                            class="btn btn-sm {{ $typeFilter === 'waiter' ? 'btn-warning' : 'btn-outline-secondary' }}"
                            style="font-size: 0.78rem; padding: 0.2rem 0.6rem;">
                        <i class="bx bx-bell-ring me-1"></i>Waiter Calls
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- Notification List --}}
    <div class="card border-0 shadow-sm">
        @forelse($notifications as $notification)
            <div @if(!$notification->read_at) wire:click="markAsRead('{{ $notification->id }}')" @endif
                 wire:key="notif-{{ $notification->id }}"
                 class="d-flex align-items-start gap-3 p-3 border-bottom notification-row {{ $notification->read_at ? '' : 'unread' }}"
                 style="cursor: pointer;">

                {{-- Icon --}}
                <div class="flex-shrink-0 mt-1 text-white">
                    @if($notification->type === 'App\Notifications\NewOrderNotification')
                        <span class="d-flex align-items-center justify-content-center rounded-circle bg-success"
                              style="width: 42px; height: 42px;">
                            <i class="bx bxs-cart fs-5"></i>
                        </span>
                    @else
                        <span class="d-flex align-items-center justify-content-center rounded-circle bg-warning"
                              style="width: 42px; height: 42px;">
                            <i class="bx bx-bell fs-5"></i>
                        </span>
                    @endif
                </div>

                {{-- Body --}}
                <div class="flex-grow-1">
                    <div class="d-flex align-items-start justify-content-between gap-2">
                        <div>
                            @if($notification->type === 'App\Notifications\NewOrderNotification')
                                <p class="mb-1 fw-semibold text-dark" style="font-size: 0.88rem;">New Order Received</p>
                                <p class="mb-1 text-muted" style="font-size: 0.82rem;">
                                    <span class="me-3">
                                        <i class="bx bx-table me-1"></i>Table {{ $notification->data['table_number'] ?? 'N/A' }}
                                    </span>
                                    <span>
                                        <i class="bx bx-rupee me-1"></i>Rs. {{ number_format($notification->data['total_amount'] ?? 0, 2) }}
                                    </span>
                                </p>
                            @elseif($notification->type === 'App\Notifications\WaiterCallNotification')
                                <p class="mb-1 fw-semibold text-dark" style="font-size: 0.88rem;">Waiter Requested</p>
                                <p class="mb-1 text-muted" style="font-size: 0.82rem;">
                                    <i class="bx bx-table me-1"></i>Table {{ $notification->data['table_number'] ?? 'N/A' }} needs assistance
                                </p>
                            @else
                                <p class="mb-1 fw-semibold text-dark" style="font-size: 0.88rem;">Notification</p>
                                <p class="mb-1 text-muted" style="font-size: 0.82rem;">No details available.</p>
                            @endif
                            <span class="text-muted" style="font-size: 0.75rem;">
                                <i class="bx bx-time-five me-1"></i>{{ $notification->created_at->diffForHumans() }}
                                <span class="text-muted mx-1">&middot;</span>
                                {{ $notification->created_at->format('d M Y, h:i A') }}
                            </span>
                        </div>

                        {{-- Status badge --}}
                        <div class="flex-shrink-0">
                            @if($notification->read_at)
                                <span class="badge bg-secondary border border-secondary border-opacity-25"
                                      style="font-size: 0.7rem;">Read</span>
                            @else
                                <span class="badge bg-primary border border-primary border-opacity-25"
                                      style="font-size: 0.7rem;">Unread</span>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        @empty
            <div class="text-center py-5">
                <i class="bx bx-bell-off text-muted d-block mb-3" style="font-size: 3rem;"></i>
                <h6 class="text-muted mb-1">No notifications found</h6>
                <p class="text-muted mb-0" style="font-size: 0.82rem;">
                    @if($filter !== 'all' || $typeFilter !== 'all')
                        Try adjusting the filters above.
                    @else
                        You're all caught up!
                    @endif
                </p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($notifications->hasPages())
        <div class="mt-3 d-flex justify-content-center">
            {{ $notifications->links() }}
        </div>
    @endif

    {{-- Wire loading overlay --}}
    <div wire:loading.flex class="position-fixed top-0 start-0 w-100 h-100 align-items-center justify-content-center"
         style="background: rgba(255,255,255,0.5); z-index: 9999;">
        <div class="spinner-border text-primary spinner-border-sm" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>

<style>
    .notification-row {
        transition: background-color 0.15s ease;
    }
    .notification-row:hover {
        background-color: #f8f9fa;
    }
    .notification-row.unread {
        background-color: #f0f5ff;
        border-left: 3px solid var(--primary-color) !important;
    }
    .notification-row.unread:hover {
        background-color: #e6efff;
    }
    .notification-row:last-child {
        border-bottom: none !important;
    }
</style>