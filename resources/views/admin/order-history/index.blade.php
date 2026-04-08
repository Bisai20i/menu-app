@extends('layouts.admin-layout')

@push('title')
    Order History
@endpush

@section('content')
<div x-data="{
    orderDetail: null,
    showDetail: false,
    openOrder(order) {
        this.orderDetail = order;
        this.showDetail = true;
    },
    closeDetail() {
        this.showDetail = false;
        setTimeout(() => this.orderDetail = null, 300);
    }
}">

    {{-- ── Header & Filters ────────────────────────────────────────────────── --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Order History</h4>
                    <p class="text-muted mb-0">Search and filter through past restaurant orders.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('master.orders.index') }}" class="btn btn-primary d-flex align-items-center gap-2">
                        <i class="bx bx-list-ul"></i>
                        <span>Active Orders</span>
                    </a>
                </div>
            </div>

            <form action="{{ route('master.order-history.index') }}" method="GET" class="row g-3">
                {{-- Search --}}
                <div class="col-12 col-md-4">
                    <label class="form-label small fw-bold">Search Order or Table</label>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="bx bx-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="ID or Table #" value="{{ request('search') }}">
                    </div>
                </div>

                {{-- Status --}}
                <div class="col-6 col-md-2">
                    <label class="form-label small fw-bold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="served" {{ request('status') == 'served' ? 'selected' : '' }}>Served</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                {{-- Date From --}}
                <div class="col-6 col-md-2">
                    <label class="form-label small fw-bold">Date From</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>

                {{-- Date To --}}
                <div class="col-6 col-md-2">
                    <label class="form-label small fw-bold">Date To</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>

                {{-- Buttons --}}
                <div class="col-6 col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-outline-primary flex-grow-1">Filter</button>
                    <a href="{{ route('master.order-history.index') }}" class="btn btn-outline-secondary" title="Reset">
                        <i class="bx bx-refresh"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Desktop Table ───────────────────────────────────────────────────── --}}
    <div class="card border-0 shadow-sm d-none d-lg-block">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Order #</th>
                        <th>Table</th>
                        <th>Items</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Date & Time</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold text-dark">#{{ strtoupper(substr($order->uuid, 0, 8)) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-label-info">Table {{ $order->table?->table_number ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="text-muted">{{ $order->items->count() }} item(s)</span>
                            </td>
                            <td>
                                <span class="fw-bold text-dark">Rs. {{ number_format($order->total_amount, 2) }}</span>
                            </td>
                            <td>
                                @php
                                    $statusClass = match($order->status) {
                                        'pending' => 'bg-label-warning',
                                        'confirmed' => 'bg-label-info',
                                        'served' => 'bg-label-primary',
                                        'paid' => 'bg-label-success',
                                        'cancelled' => 'bg-label-danger',
                                        default => 'bg-label-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }} text-capitalize">{{ $order->status }}</span>
                            </td>
                            <td>
                                <div class="small">
                                    <div class="text-dark">{{ $order->created_at->format('d M, Y') }}</div>
                                    <div class="text-muted">{{ $order->created_at->format('g:i A') }}</div>
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <button type="button" class="btn btn-sm btn-label-primary"
                                    @click="openOrder({{ \Illuminate\Support\Js::from([
                                        'uuid' => $order->uuid,
                                        'table' => $order->table?->table_number,
                                        'note' => $order->note,
                                        'total' => $order->total_amount,
                                        'status' => $order->status,
                                        'created_at' => $order->created_at->format('d M Y, g:i A'),
                                        'items' => $order->items->map(fn($i) => [
                                            'name' => $i->menuItem?->name ?? 'Deleted Item',
                                            'qty' => $i->quantity,
                                            'price' => $i->unit_price,
                                            'subtotal' => $i->subtotal,
                                            'note' => $i->special_request,
                                            'is_cancelled' => $i->is_cancelled,
                                            'cancellation_note' => $i->cancellation_note,
                                        ])->toArray(),
                                    ]) }})">
                                    View Details
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bx bx-receipt fs-1 text-muted opacity-25 mb-3 d-block"></i>
                                <p class="text-muted">No orders found matching your criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->count() > 0)
            <div class="card-footer border-0 bg-transparent py-4">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

    {{-- ── Mobile Cards ────────────────────────────────────────────────────── --}}
    <div class="d-lg-none">
        @forelse($orders as $order)
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="fw-bold d-block">#{{ strtoupper(substr($order->uuid, 0, 8)) }}</span>
                            <span class="text-muted small">{{ $order->created_at->format('d M, g:i A') }}</span>
                        </div>
                        @php
                            $statusClass = match($order->status) {
                                'pending' => 'bg-label-warning',
                                'confirmed' => 'bg-label-info',
                                'served' => 'bg-label-primary',
                                'paid' => 'bg-label-success',
                                'cancelled' => 'bg-label-danger',
                                default => 'bg-label-secondary'
                            };
                        @endphp
                        <span class="badge {{ $statusClass }} text-capitalize">{{ $order->status }}</span>
                    </div>

                    <div class="d-flex gap-3 mb-3 border-top border-bottom py-2 my-2">
                        <div class="small">
                            <span class="text-muted d-block">Table</span>
                            <span class="fw-semibold">Table {{ $order->table?->table_number ?? 'N/A' }}</span>
                        </div>
                        <div class="small">
                            <span class="text-muted d-block">Total</span>
                            <span class="fw-semibold text-primary">Rs. {{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>

                    <button type="button" class="btn btn-outline-primary w-100 btn-sm"
                        @click="openOrder({{ \Illuminate\Support\Js::from([
                            'uuid' => $order->uuid,
                            'table' => $order->table?->table_number,
                            'note' => $order->note,
                            'total' => $order->total_amount,
                            'status' => $order->status,
                            'created_at' => $order->created_at->format('d M Y, g:i A'),
                            'items' => $order->items->map(fn($i) => [
                                'name' => $i->menuItem?->name ?? 'Deleted Item',
                                'qty' => $i->quantity,
                                'price' => $i->unit_price,
                                'subtotal' => $i->subtotal,
                                'note' => $i->special_request,
                                'is_cancelled' => $i->is_cancelled,
                                'cancellation_note' => $i->cancellation_note,
                            ])->toArray(),
                        ]) }})">
                        Details & Items
                    </button>
                </div>
            </div>
        @empty
            <div class="card border-0 shadow-sm p-5 text-center">
                <i class="bx bx-receipt fs-1 text-muted opacity-25 mb-2 d-block"></i>
                <p class="text-muted mb-0">No orders found.</p>
            </div>
        @endforelse

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>

    {{-- ── Details Modal ───────────────────────────────────────────────────── --}}
    <div x-show="showDetail" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="modal-backdrop-custom"
        @click.self="closeDetail()" style="display:none;">

        <div x-show="showDetail" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95" class="order-detail-modal card shadow-lg p-0">

            <template x-if="orderDetail">
                <div>
                    <div class="card-header d-flex align-items-center justify-content-between border-0 pb-2">
                        <div>
                            <h6 class="fw-bold mb-0">Order Summary</h6>
                            <span class="text-muted small" x-text="'#' + orderDetail.uuid?.substring(0, 8).toUpperCase()"></span>
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
                        </div>

                        {{-- Items Table --}}
                        <div class="table-responsive rounded-3 border">
                            <table class="table table-sm align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Item</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end pe-3">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="item in orderDetail.items" :key="item.name + item.qty">
                                        <tr>
                                            <td class="ps-3">
                                                <div class="fw-semibold small" :class="item.is_cancelled ? 'text-decoration-line-through text-muted' : ''" x-text="item.name"></div>
                                                <div class="text-muted xsmall" x-text="item.note" x-show="item.note"></div>
                                                <div class="text-danger xsmall fw-bold" x-text="'🚫 ' + item.cancellation_note" x-show="item.is_cancelled && item.cancellation_note"></div>
                                            </td>
                                            <td class="text-center" :class="item.is_cancelled ? 'text-decoration-line-through text-muted' : ''" x-text="item.qty"></td>
                                            <td class="text-end pe-3 small fw-semibold" :class="item.is_cancelled ? 'text-decoration-line-through text-muted' : ''" x-text="'Rs. ' + parseFloat(item.subtotal).toFixed(2)"></td>
                                        </tr>
                                    </template>
                                </tbody>
                                <tfoot>
                                    <tr class="table-light">
                                        <td colspan="2" class="text-end fw-bold ps-3">Total</td>
                                        <td class="text-end fw-bold text-primary pe-3" x-text="'Rs. ' + parseFloat(orderDetail.total).toFixed(2)"></td>
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
        max-width: 500px;
        max-height: 85vh;
        overflow-y: auto;
        border-radius: 0.75rem;
    }
    .info-pill {
        display: inline-flex;
        align-items: center;
        background: #f1f2f4;
        border-radius: 100px;
        padding: 0.2rem 0.75rem;
        font-size: 0.8rem;
        color: #566a7f;
    }
    .xsmall { font-size: 0.75rem; }
    .pagination { margin-bottom: 0; justify-content: flex-end; }
</style>
@endpush
@endsection
