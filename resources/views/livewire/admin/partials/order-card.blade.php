{{--
    Reusable Order Card Partial
    Variables: $order, $btnClass, $btnLabel, $wireAction
--}}
<div class="col-md-6 col-xl-4">
    <div class="card border-0 shadow-sm h-100 order-card">
        {{-- Card Header --}}
        <div class="card-header border-0 d-flex align-items-center justify-content-between py-2 px-3"
             style="background: #f8f9fa;">
            <div class="d-flex align-items-center gap-2">
                <span class="fw-bold text-muted small">Table</span>
                <span class="badge bg-label-dark fw-bold">{{ $order->table?->table_number ?? 'N/A' }}</span>
                @if($order->table?->section)
                    <span class="text-muted" style="font-size:0.72rem;">({{ $order->table->section }})</span>
                @endif
            </div>
            <span class="text-muted" style="font-size:0.72rem;">
                {{ $order->created_at->format('g:i A') }}
            </span>
        </div>

        {{-- Card Body --}}
        <div class="card-body px-3 py-2">
            {{-- Items Preview --}}
            <ul class="list-unstyled mb-2" style="max-height: 140px; overflow-y:auto;">
                @foreach($order->items as $item)
                    <li class="d-flex justify-content-between align-items-start py-1 border-bottom">
                        <div>
                            <span class="fw-semibold small">{{ $item->menuItem?->name ?? 'Deleted Item' }}</span>
                            @if($item->special_request)
                                <div class="text-warning" style="font-size:0.72rem;">📝 {{ $item->special_request }}</div>
                            @endif
                        </div>
                        <div class="text-end ms-2 flex-shrink-0">
                            <span class="badge bg-label-secondary">× {{ $item->quantity }}</span>
                        </div>
                    </li>
                @endforeach
            </ul>

            {{-- Order Note --}}
            @if($order->note)
                <div class="alert alert-warning py-1 px-2 mb-2" style="font-size:0.78rem;">
                    <i class="bx bx-note me-1"></i>{{ $order->note }}
                </div>
            @endif

            {{-- Total --}}
            <div class="d-flex justify-content-between align-items-center mt-1">
                <span class="text-muted small">Total</span>
                <span class="fw-bold text-primary">Rs. {{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>

        {{-- Card Footer --}}
        <div class="card-footer border-0 bg-transparent px-3 pt-0 pb-3 d-flex gap-2">
            {{-- Details (Alpine — no Livewire roundtrip) --}}
            <button type="button" class="btn btn-sm btn-outline-secondary flex-shrink-0"
                @click="openOrder({{ json_encode([
                    'id'         => $order->id,
                    'uuid'       => $order->uuid,
                    'table'      => $order->table?->table_number,
                    'note'       => $order->note,
                    'total'      => $order->total_amount,
                    'status'     => $order->status,
                    'created_at' => $order->created_at->format('d M Y, g:i A'),
                    'items'      => $order->items->map(fn($i) => [
                        'name'     => $i->menuItem?->name ?? 'Deleted Item',
                        'qty'      => $i->quantity,
                        'price'    => $i->unit_price,
                        'subtotal' => $i->subtotal,
                        'note'     => $i->special_request,
                    ])->toArray(),
                ]) }})">
                <i class="bx bx-list-ul"></i>
            </button>

            {{-- Primary Action --}}
            <button type="button"
                    class="btn btn-sm {{ $btnClass }} flex-grow-1 fw-semibold"
                    wire:click="{{ $wireAction }}"
                    wire:loading.attr="disabled"
                    wire:target="{{ $wireAction }}">
                <span wire:loading.remove wire:target="{{ $wireAction }}">{{ $btnLabel }}</span>
                <span wire:loading wire:target="{{ $wireAction }}">
                    <span class="spinner-border spinner-border-sm me-1"></span> Processing...
                </span>
            </button>
        </div>
    </div>
</div>
