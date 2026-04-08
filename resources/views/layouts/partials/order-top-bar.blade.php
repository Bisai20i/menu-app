@php
    $hasPendingOrders = \App\Models\Order::where('restaurant_id', auth('admin')->user()->restaurant_id)
        ->where('status', 'pending')
        ->exists();
@endphp
<div class="container-fluid bg-white border-bottom px-0">

    {{-- ── Top bar ── --}}
    <div class="d-flex align-items-center justify-content-between px-3 px-md-4 py-2 gap-2">

        {{-- Back + title --}}
        <div class="d-flex align-items-center gap-2 overflow-hidden">
            <a href="{{ route('master.dashboard') }}"
               class="d-flex align-items-center text-secondary text-decoration-none flex-shrink-0">
                <i class="bx bx-chevron-left fs-4"></i>
                <span class="d-none d-sm-inline small">Back</span>
            </a>
            <div class="overflow-hidden">
                <h4 class="fw-bold mb-0 text-truncate fs-6 fs-md-5">Order Management</h4>
                <p class="text-muted mb-0 small d-none d-md-block">Real-time overview of all table orders</p>
            </div>
        </div>

        {{-- Desktop links + bell --}}
        <div class="d-flex align-items-center gap-1 flex-shrink-0">
            <ul class="nav d-none d-lg-flex align-items-center">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('master.orders.index') ? 'active text-primary' : 'text-secondary' }} d-flex align-items-center position-relative"
                       href="{{ route('master.orders.index') }}">
                        Orders 
                        @if($hasPendingOrders)
                            <span class="pending-indicator-dot" title="There are pending orders"></span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('master.table-sessions') ? 'active text-primary' : 'text-secondary' }}"
                       href="{{ route('master.table-sessions') }}">
                        Table Sessions
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('master.order-history.index') ? 'active text-primary' : 'text-secondary' }} d-flex align-items-center"
                       href="{{ route('master.order-history.index') }}">
                        Order History
                    </a>
                </li>
            </ul>

            @if (!request()->routeIs('master.notifications.index'))
                <livewire:admin.notification-bell lazy />
            @endif
        </div>
    </div>

    {{-- ── Tab strip (below lg) ────────────────────────────────── --}}
    <div class="d-flex d-lg-none border-top overflow-auto" style="scrollbar-width: none;">
        <a href="{{ route('master.orders.index') }}"
           class="position-relative d-flex align-items-center gap-1 px-3 py-2 small fw-medium text-decoration-none flex-shrink-0
                  {{ request()->routeIs('master.orders.index') ? 'text-primary border-bottom border-2 border-primary' : 'text-secondary border-bottom border-2 border-transparent' }}">
            <i class="bx bx-receipt"></i>
            Orders 
            @if($hasPendingOrders)
                <span class="pending-indicator-dot" title="There are pending orders"></span>
            @endif
        </a>
        <a href="{{ route('master.table-sessions') }}"
           class="d-flex align-items-center gap-1 px-3 py-2 small fw-medium text-decoration-none flex-shrink-0
                  {{ request()->routeIs('master.table-sessions') ? 'text-primary border-bottom border-2 border-primary' : 'text-secondary border-bottom border-2 border-transparent' }}">
            <i class="bx bx-grid-alt"></i>
            Sessions
        </a>
        <a href="{{ route('master.tables.index') }}"
           class="d-flex align-items-center gap-1 px-3 py-2 small fw-medium text-decoration-none flex-shrink-0
                  {{ request()->routeIs('master.tables.index') ? 'text-primary border-bottom border-2 border-primary' : 'text-secondary border-bottom border-2 border-transparent' }}">
            <i class="bx bx-table"></i>
            Table List
        </a>
        <a href="{{ route('master.order-history.index') }}"
           class="d-flex align-items-center gap-1 px-3 py-2 small fw-medium text-decoration-none flex-shrink-0
                  {{ request()->routeIs('master.order-history.index') ? 'text-primary border-bottom border-2 border-primary' : 'text-secondary border-bottom border-2 border-transparent' }}">
            <i class="bx bx-history"></i>
            History
        </a>
    </div>

</div>

<style>
    .pending-indicator-dot {
        position: absolute;
        top: 8px;
        right:5%;
        transform: translateX(-50%);
        width: 8px;
        height: 8px;
        background-color: #ff3e1d;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 0 0 rgba(255, 62, 29, 0.7);
        animation: pulse-red-blink 1.5s infinite;
        vertical-align: middle;
    }

    @keyframes pulse-red-blink {
        0% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(255, 62, 29, 0.7);
        }
        70% {
            transform: scale(1);
            box-shadow: 0 0 0 6px rgba(255, 62, 29, 0);
        }
        100% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(255, 62, 29, 0);
        }
    }
</style>