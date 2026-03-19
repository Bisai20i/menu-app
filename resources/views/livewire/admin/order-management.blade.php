<div x-data="{
    activeTab: @entangle('activeTab'),
    orderDetail: null,
    showDetail: false,
    openOrder(order) {
        this.orderDetail = order;
        this.showDetail = true;
    },
    closeDetail() {
        this.showDetail = false;
        setTimeout(() => this.orderDetail = null, 300);
    },
    formatCurrency(amount) {
        return 'Rs. ' + parseFloat(amount).toFixed(2);
    }
}" class="order-management-wrap">

    {{-- ── Page Header ──────────────────────────────────────────────────── --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap-reverse gap-2 mb-3">
        
        {{-- ── Status Tabs ──────────────────────────────────────────────────── --}}
        <ul class="nav nav-pills" role="tablist">
            <li class="nav-item">
                <button class="nav-link d-flex align-items-center gap-2" :class="activeTab === 'pending' ? 'active' : ''"
                    @click="activeTab = 'pending'">
                    <i class="bx bx-time-five"></i>
                    Pending
                    @if ($pending->count())
                        <span class="badge bg-danger rounded-pill">{{ $pending->count() }}</span>
                    @endif
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link d-flex align-items-center gap-2"
                    :class="activeTab === 'confirmed' ? 'active' : ''" @click="activeTab = 'confirmed'">
                    <i class="bx bx-check-circle"></i>
                    Confirmed
                    @if ($confirmed->count())
                        <span class="badge bg-warning text-dark rounded-pill">{{ $confirmed->count() }}</span>
                    @endif
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link d-flex align-items-center gap-2"
                    :class="activeTab === 'served' ? 'active' : ''" @click="activeTab = 'served'">
                    <i class="bx bx-dish"></i>
                    Served
                    @if ($served->count())
                        <span class="badge bg-info rounded-pill">{{ $served->count() }}</span>
                    @endif
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link d-flex align-items-center gap-2"
                    :class="activeTab === 'paid' ? 'active' : ''" @click="activeTab = 'paid'">
                    <i class="bx bx-money-withdraw"></i>
                    Paid
                    @if ($paidOrders->count())
                        <span class="badge bg-success rounded-pill">{{ $paidOrders->count() }}</span>
                    @endif
                </button>
            </li>
        </ul>

        <div class="d-flex gap-2 align-items-center">
            {{-- Table Filter --}}
            <select wire:model.live="filterTableId" class="form-select form-select-sm" style="width:auto">
                <option value="0">All Tables</option>
                @foreach ($tables as $t)
                    <option value="{{ $t->id }}">Table {{ $t->table_number }}
                        @if ($t->section)
                            ({{ $t->section }})
                        @endif
                    </option>
                @endforeach
            </select>
        </div>
    </div>


    {{-- ── Order Cards per Tab ──────────────────────────────────────────── --}}

    {{-- Pending --}}
    <div x-show="activeTab === 'pending'" x-transition>
        @if ($pending->isEmpty())
            @include('livewire.admin.partials.empty-state', [
                'icon' => 'bx-time-five',
                'label' => 'No pending orders',
            ])
        @else
            <div class="row g-3">
                @foreach ($pending as $order)
                    @include('livewire.admin.partials.order-card', [
                        'order' => $order,
                        'action' => 'confirm',
                        'btnClass' => 'btn-success',
                        'btnLabel' => 'Confirm Order',
                        'wireAction' => "confirmOrder({$order->id})",
                    ])
                @endforeach
            </div>
        @endif
    </div>

    {{-- Confirmed --}}
    <div x-show="activeTab === 'confirmed'" x-transition>
        @if ($confirmed->isEmpty())
            @include('livewire.admin.partials.empty-state', [
                'icon' => 'bx-check-circle',
                'label' => 'No confirmed orders',
            ])
        @else
            <div class="row g-3">
                @foreach ($confirmed as $order)
                    @include('livewire.admin.partials.order-card', [
                        'order' => $order,
                        'action' => 'serve',
                        'btnClass' => 'btn-info',
                        'btnLabel' => 'Mark as Served',
                        'wireAction' => "serveOrder({$order->id})",
                    ])
                @endforeach
            </div>
        @endif
    </div>

    {{-- Served --}}
    <div x-show="activeTab === 'served'" x-transition>
        @if ($served->isEmpty())
            @include('livewire.admin.partials.empty-state', [
                'icon' => 'bx-dish',
                'label' => 'No served orders awaiting payment',
            ])
        @else
            <div class="row g-3">
                @foreach ($served as $order)
                    @include('livewire.admin.partials.order-card', [
                        'order' => $order,
                        'action' => 'pay',
                        'btnClass' => 'btn-primary',
                        'btnLabel' => 'Mark as Paid',
                        'wireAction' => "markAsPaid({$order->id})",
                    ])
                @endforeach
            </div>
        @endif
    </div>

    {{-- Paid History --}}
    <div x-show="activeTab === 'paid'" x-transition>
        @if ($paidOrders->isEmpty())
            @include('livewire.admin.partials.empty-state', [
                'icon' => 'bx-receipt',
                'label' => 'No paid orders yet',
            ])
        @else
            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Order #</th>
                                <th>Table</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Paid At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($paidOrders as $order)
                                <tr>
                                    <td><span
                                            class="fw-semibold text-muted small">{{ strtoupper(substr($order->uuid, 0, 8)) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-secondary">
                                            Table {{ $order->table?->table_number ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td><span class="text-muted">{{ $order->items->count() }} item(s)</span></td>
                                    <td><strong>Rs. {{ number_format($order->total_amount, 2) }}</strong></td>
                                    <td><span
                                            class="text-muted small">{{ $order->paid_at?->format('d M, g:i A') }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                @click="openOrder({{ \Illuminate\Support\Js::from([
                                                    'id' => $order->id,
                                                    'uuid' => $order->uuid,
                                                    'table' => $order->table?->table_number,
                                                    'note' => $order->note,
                                                    'total' => $order->total_amount,
                                                    'status' => 'paid',
                                                    'created_at' => $order->created_at->format('d M Y, g:i A'),
                                                    'items' => $order->items->map(
                                                            fn($i) => [
                                                                'name' => $i->menuItem?->name ?? 'Deleted Item',
                                                                'qty' => $i->quantity,
                                                                'price' => $i->unit_price,
                                                                'subtotal' => $i->subtotal,
                                                                'note' => $i->special_request,
                                                            ],
                                                        )->toArray(),
                                                ]) }})">
                                                <i class="bx bx-list-ul me-1"></i> Details
                                            </button>

                                            @if ($order->session && $order->session->status === 'active')
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    wire:click="closeTableSession({{ $order->session->id }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="closeTableSession({{ $order->session->id }})">
                                                    <span wire:loading.remove
                                                        wire:target="closeTableSession({{ $order->session->id }})">
                                                        <i class="bx bx-power-off me-1"></i> Close Session
                                                    </span>
                                                    <span wire:loading
                                                        wire:target="closeTableSession({{ $order->session->id }})">
                                                        <span class="spinner-border spinner-border-sm"></span>
                                                    </span>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    {{-- ── Order Detail Modal (Alpine-driven, zero Livewire roundtrip) ──── --}}
    <div x-show="showDetail" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="modal-backdrop-custom"
        @click.self="closeDetail()" style="display:none;">

        <div x-show="showDetail" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95" class="order-detail-modal card shadow-lg">

            <template x-if="orderDetail">
                <div>
                    <div class="card-header d-flex align-items-center justify-content-between border-0 pb-2">
                        <div>
                            <h6 class="fw-bold mb-0">Order Details</h6>
                            <span class="text-muted small"
                                x-text="'#' + orderDetail.uuid?.substring(0, 8).toUpperCase()"></span>
                        </div>
                        <button type="button" class="btn-close" @click="closeDetail()"></button>
                    </div>
                    <div class="card-body pt-1">
                        <div class="d-flex gap-3 mb-3 flex-wrap">
                            <div class="info-pill">
                                <i class="bx bx-table me-1"></i>
                                <span>Table <strong x-text="orderDetail.table ?? 'N/A'"></strong></span>
                            </div>
                            <div class="info-pill">
                                <i class="bx bx-time me-1"></i>
                                <span x-text="orderDetail.created_at"></span>
                            </div>
                            <div class="info-pill">
                                <i class="bx bx-tag me-1"></i>
                                <span class="text-capitalize" x-text="orderDetail.status"></span>
                            </div>
                        </div>

                        {{-- Note --}}
                        <template x-if="orderDetail.note">
                            <div class="alert alert-warning py-2 px-3 small mb-3">
                                <i class="bx bx-note me-1"></i>
                                <strong>Note:</strong> <span x-text="orderDetail.note"></span>
                            </div>
                        </template>

                        {{-- Items Table --}}
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Item</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Price</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="item in orderDetail.items" :key="item.name + item.qty">
                                        <tr>
                                            <td>
                                                <div class="fw-semibold small" x-text="item.name"></div>
                                                <div class="text-muted" style="font-size:0.75rem;"
                                                    x-text="item.note ? '📝 ' + item.note : ''" x-show="item.note">
                                                </div>
                                            </td>
                                            <td class="text-center" x-text="item.qty"></td>
                                            <td class="text-end small"
                                                x-text="'Rs. ' + parseFloat(item.price).toFixed(2)"></td>
                                            <td class="text-end small fw-semibold"
                                                x-text="'Rs. ' + parseFloat(item.subtotal).toFixed(2)"></td>
                                        </tr>
                                    </template>
                                </tbody>
                                <tfoot>
                                    <tr class="table-light">
                                        <td colspan="3" class="text-end fw-bold">Total</td>
                                        <td class="text-end fw-bold text-primary"
                                            x-text="'Rs. ' + parseFloat(orderDetail.total).toFixed(2)"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

</div>

@push('styles')
    <style>
        .order-management-wrap {
            position: relative;
        }

        .modal-backdrop-custom {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            z-index: 2028;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .order-detail-modal {
            width: 100%;
            max-width: 560px;
            max-height: 85vh;
            overflow-y: auto;
            border-radius: 0.75rem;
        }

        .info-pill {
            display: inline-flex;
            align-items: center;
            background: var(--bs-light);
            border-radius: 100px;
            padding: 0.2rem 0.75rem;
            font-size: 0.82rem;
            color: var(--bs-secondary-color);
        }

        .order-status-badge {
            font-size: 0.7rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
    </style>
@endpush
