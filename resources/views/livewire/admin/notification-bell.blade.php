<div class="dropdown d-flex align-items-center justify-content-center" style="height: 40px; width: 40px;">
    <a class="nav-link position-relative border border-primary rounded-circle p-2" href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bx {{ $unreadCount > 0 ?'bxs-bell' : 'bx-bell' }} fs-5 text-primary"></i>
        @if($unreadCount > 0)
            <span class="position-absolute top-0 mt-1 start-100 translate-middle badge rounded-pill bg-danger"
                  style="font-size: 0.8rem; padding: 0.25em 0.45em;">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </a>

    <div class="dropdown-menu dropdown-menu-end p-0 shadow border-0"
         style="width: 360px; max-width: calc(100vw - 1rem);">

        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom bg-light rounded-top">
            <div class="d-flex align-items-center gap-2">
                <i class="bx bxs-bell text-primary"></i>
                <span class="fw-semibold text-dark" style="font-size: 0.9rem;">Notifications</span>
                @if($unreadCount > 0)
                    <span class="badge bg-danger rounded-pill" style="font-size: 0.7rem;">{{ $unreadCount }}</span>
                @endif
            </div>
            @if($unreadCount > 0)
                <a href="#" wire:click.prevent="markAllAsRead"
                   class="text-primary text-decoration-none"
                   style="font-size: 0.78rem;">
                    Mark all read
                </a>
            @endif
        </div>

        {{-- Notification List --}}
        <ul class="list-unstyled mb-0" style="max-height: 320px; overflow-y: auto;">
            @forelse($notifications as $notification)
                <li>
                    <div @if(!$notification->read_at) wire:click="markAsRead('{{ $notification->id }}')" @endif
                         class="d-flex align-items-start gap-3 px-3 py-2 border-bottom notification-item {{ $notification->read_at ? '' : 'unread' }}"
                         style="cursor: pointer;">

                        {{-- Icon Badge --}}
                        <div class="flex-shrink-0 mt-1">
                            @if($notification->type === 'App\Notifications\NewOrderNotification')
                                <span class="d-flex align-items-center justify-content-center rounded-circle bg-success"
                                      style="width: 36px; height: 36px;">
                                    <i class="bx bx-cart text-white" style="font-size: 1rem;"></i>
                                </span>
                            @else
                                <span class="d-flex align-items-center justify-content-center rounded-circle bg-warning"
                                      style="width: 36px; height: 36px;">
                                    <i class="bx bx-bell text-white" style="font-size: 1rem;"></i>
                                </span>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div class="flex-grow-1 overflow-hidden">
                            @if($notification->type === 'App\Notifications\NewOrderNotification')
                                <p class="mb-0 fw-semibold text-dark lh-sm" style="font-size: 0.82rem;">New Order Received</p>
                                <p class="mb-1 text-muted text-truncate" style="font-size: 0.78rem;">
                                    Table {{ $notification->data['table_number'] ?? 'N/A' }}
                                    &mdash;
                                    Rs. {{ number_format($notification->data['total_amount'] ?? 0, 2) }}
                                </p>
                            @elseif($notification->type === 'App\Notifications\WaiterCallNotification')
                                <p class="mb-0 fw-semibold text-dark lh-sm" style="font-size: 0.82rem;">Waiter Requested</p>
                                <p class="mb-1 text-muted text-truncate" style="font-size: 0.78rem;">
                                    Table {{ $notification->data['table_number'] ?? 'N/A' }} needs assistance
                                </p>
                            @else
                                <p class="mb-0 fw-semibold text-dark lh-sm" style="font-size: 0.82rem;">Notification</p>
                                <p class="mb-1 text-muted" style="font-size: 0.78rem;">No details available.</p>
                            @endif
                            <span class="text-muted" style="font-size: 0.72rem;">
                                <i class="bx bx-time-five me-1"></i>{{ $notification->created_at->diffForHumans() }}
                            </span>
                        </div>

                        {{-- Unread dot --}}
                        @if(!$notification->read_at)
                            <div class="flex-shrink-0 mt-2">
                                <span class="d-block rounded-circle bg-primary"
                                      style="width: 7px; height: 7px;"></span>
                            </div>
                        @endif
                    </div>
                </li>
            @empty
                <li class="py-4 text-center">
                    <i class="bx bx-bell-off text-muted d-block mb-2" style="font-size: 2rem;"></i>
                    <p class="text-muted mb-0" style="font-size: 0.82rem;">No new notifications</p>
                </li>
            @endforelse
        </ul>

        {{-- Footer --}}
        <div class="border-top text-center pb-3  pt-2 bg-light rounded-bottom">
            <a href="{{ route('master.notifications.index') }}"
               class="text-primary text-decoration-none"
               style="font-size: 0.8rem;">
                View all notifications
            </a>
        </div>
    </div>
</div>

<style>
    .notification-item {
        transition: background-color 0.15s ease;
    }
    .notification-item:hover {
        background-color: #f8f9fa;
    }
    .notification-item.unread {
        background-color: #f0f5ff;
    }
    .notification-item.unread:hover {
        background-color: #e6efff;
    }
</style>