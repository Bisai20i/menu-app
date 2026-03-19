<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\TableSession;
use App\Models\RestaurantTable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

new class extends Component {
    public string $filter = 'active_today';
    public array $selectedSessions = [];
    public ?string $mergeTarget = null;

    public ?string $selectedTableId = null;
    public int $guestCount = 1;

    // ── Computed: sessions ────────────────────────────────────────────────
    // #[Computed] caches the result for the lifetime of the request.
    // Livewire will NOT serialize this into its snapshot — it is re-derived
    // on each render from the DB, so we never bloat the payload with
    // full Eloquent models sitting in $sessions.
    #[Computed]
    public function sessions()
    {
        $restaurantId = Auth::user()->restaurant_id;

        $query = TableSession::query()
            ->select([
                'table_sessions.*',
                // Avoid loading all order rows just for count/sum.
                DB::raw('(SELECT COUNT(*) FROM orders WHERE orders.table_session_id = table_sessions.id) as orders_count'),
                DB::raw('(SELECT COALESCE(SUM(orders.total_amount), 0) FROM orders WHERE orders.table_session_id = table_sessions.id) as grand_total'),
            ])
            ->with(['table:id,table_number,section', 'openedBy:id,name'])
            ->where('restaurant_id', $restaurantId);

        match ($this->filter) {
            'active_today' => $query->active()->whereDate('opened_at', today()),
            'closed' => $query->whereIn('status', ['paid', 'cancelled']),
            default => null,
        };

        return $query->orderBy('opened_at', 'desc')->get();
    }

    // ── Computed: tables (only needed when create modal is open) ──────────
    // Kept separate so it only runs when accessed from the view.
    #[Computed]
    public function tables()
    {
        return RestaurantTable::query()
            ->select('id', 'table_number', 'section')
            ->where('restaurant_id', Auth::user()->restaurant_id)
            ->where('is_active', true)
            ->orderBy('table_number')
            ->get();
    }

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
        $this->selectedSessions = [];
        $this->mergeTarget = null;
        unset($this->sessions); // bust the computed cache
    }

    public function mergeSessions(): void
    {
        if (count($this->selectedSessions) < 2 || !$this->mergeTarget) {
            $this->addError('merge', 'Select at least 2 sessions and a target to merge into.');
            return;
        }

        $sessions = TableSession::whereIn('id', $this->selectedSessions)
            ->where('restaurant_id', Auth::user()->restaurant_id)
            ->get()
            ->keyBy('id');

        $targetSession = $sessions->get($this->mergeTarget);

        if (!$targetSession) {
            $this->addError('merge', 'Invalid target session.');
            return;
        }

        DB::transaction(function () use ($sessions, $targetSession) {
            foreach ($sessions as $id => $session) {
                if ($id == $this->mergeTarget) {
                    continue;
                }
                $targetSession->mergeFrom($session);
            }
        });

        $this->selectedSessions = [];
        $this->mergeTarget = null;
        $this->dispatch('close-merge-modal');
        unset($this->sessions);
        session()->flash('success', 'Sessions merged successfully.');
    }

    public function closeSession(string $sessionId): void
    {
        $session = TableSession::find($sessionId);

        if (!$session || !$session->isActive()) {
            session()->flash('error', 'Session not found or not active.');
            return;
        }

        if (Carbon::parse($session->opened_at)->diffInDays(now()) > 5) {
            session()->flash('error', 'Cannot close session older than 5 days.');
            return;
        }

        $session->table->closeSession($session, Auth::id());
        unset($this->sessions);
        session()->flash('success', 'Session closed successfully.');
    }

    public function openNewSession(): void
    {
        $this->validate([
            'selectedTableId' => 'required|exists:restaurant_tables,id',
            'guestCount' => 'required|integer|min:1|max:50',
        ]);

        $table = RestaurantTable::find($this->selectedTableId);
        $table->openSession(Auth::user()->restaurant_id, Auth::id(), $this->guestCount);

        $this->dispatch('close-create-modal');
        $this->reset(['selectedTableId', 'guestCount']);
        unset($this->sessions);
        session()->flash('success', 'New session opened successfully.');
    }

    public function render()
    {
        return $this->view([])->layout('layouts.order-management-layout')->title('Manage Table Sessions');
    }
};
?>

<div x-data="{
    selected: @entangle('selectedSessions'),
    mergeTarget: @entangle('mergeTarget'),
    showMergeModal: false,
    showCreateModal: false,
    sessionData: @js(
    $this->sessions->mapWithKeys(
        fn($s) => [
            (string) $s->id => [
                'table' => 'Table ' . $s->table->table_number,
                'total' => 'Rs. ' . number_format($s->grand_total, 2),
                'opened_at' => $s->opened_at->format('h:i A'),
                'orders' => $s->orders_count,
            ],
        ],
    ),
),
}" x-on:close-merge-modal.window="showMergeModal = false"
    x-on:close-create-modal.window="showCreateModal = false" class="table-sessions-wrap">

    {{-- ── Flash Messages ───────────────────────────────────────────────── --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible d-flex align-items-center gap-2 mb-3" role="alert">
            <i class="bx bx-check-circle"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible d-flex align-items-center gap-2 mb-3" role="alert">
            <i class="bx bx-error-circle"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Page Header & Filters ────────────────────────────────────────── --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap-reverse gap-3 mb-4">

        <ul class="nav nav-pills" role="tablist">
            @foreach (['active_today' => ['bx-calendar-check', 'Active Today'], 'all' => ['bx-list-ul', 'All Sessions'], 'closed' => ['bx-check-double', 'Closed']] as $key => [$icon, $label])
                <li class="nav-item">
                    <button class="nav-link d-flex align-items-center gap-2 {{ $filter === $key ? 'active' : '' }}"
                        wire:click="setFilter('{{ $key }}')" wire:loading.class="disabled"
                        wire:target="setFilter">
                        <i class="bx {{ $icon }}"></i>{{ $label }}
                    </button>
                </li>
            @endforeach
        </ul>

        <div class="d-flex gap-2">
            <button x-show="selected.length > 1" x-cloak @click="showMergeModal = true"
                class="btn btn-warning d-flex align-items-center gap-2 shadow-sm pulse-animation">
                <i class="bx bx-git-merge bx-sm"></i>
                <span>Merge (<span x-text="selected.length"></span>)</span>
            </button>

            <button @click="showCreateModal = true" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
                <i class="bx bx-plus-circle bx-sm"></i>
                <span>New Session</span>
            </button>
        </div>
    </div>

    {{-- ── Sessions Table ───────────────────────────────────────────────── --}}
    <div class="card border-0 shadow-sm overflow-hidden" wire:loading.class="opacity-50"
        wire:target="setFilter, closeSession, mergeSessions, openNewSession">
        @if ($this->sessions->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40px;"><i class="bx bx-checkbox text-muted fs-4"></i></th>
                            <th><i class="bx bx-table me-1"></i>Table</th>
                            <th>Status</th>
                            <th><i class="bx bx-time me-1"></i>Opened</th>
                            <th>By</th>
                            <th><i class="bx bx-receipt me-1"></i>Orders</th>
                            <th>Total</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($this->sessions as $session)
                            @php
                                $isClosable =
                                    $session->isActive() && Carbon::parse($session->opened_at)->diffInDays(now()) <= 5;
                            @endphp
                            <tr :class="selected.includes('{{ $session->id }}') ? 'table-selected' : ''">
                                <td>
                                    <div class="form-check ms-1">
                                        <input class="form-check-input session-check" type="checkbox" x-model="selected"
                                            value="{{ $session->id }}">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        
                                        <div>
                                            <span class="fw-bold">{{ $session->table->table_number }}</span>
                                            @if ($session->table->section)
                                                <div class="text-muted small">{{ $session->table->section }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if ($session->status === 'active')
                                        <span class="badge bg-label-success rounded-pill px-3">
                                            <i class="bx bxs-circle me-1 small"></i>Active
                                        </span>
                                    @else
                                        <span class="badge bg-label-secondary rounded-pill px-3">Closed</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span
                                            class="text-dark small fw-medium">{{ $session->opened_at->format('d M') }}</span>
                                        <span class="text-muted"
                                            style="font-size: 0.75rem;">{{ $session->opened_at->format('h:i A') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted small">
                                        {{ $session->openedBy?->name ?? 'Customer' }}
                                    </span>
                                </td>
                                <td>
                                    {{-- orders_count comes from the DB subquery — no extra query --}}
                                    <span class="badge bg-label-dark">{{ $session->orders_count }} items</span>
                                </td>
                                <td>
                                    {{-- grand_total comes from the DB subquery — no extra query --}}
                                    <span class="fw-bold text-primary">Rs.
                                        {{ number_format($session->grand_total, 2) }}</span>
                                </td>
                                <td class="text-end">
                                    @if ($isClosable)
                                        <button wire:click="closeSession('{{ $session->id }}')"
                                            wire:loading.attr="disabled"
                                            wire:target="closeSession('{{ $session->id }}')"
                                            class="btn btn-icon btn-sm btn-outline-danger" title="Close Session">
                                            <i class="bx bx-power-off" wire:loading.remove
                                                wire:target="closeSession('{{ $session->id }}')"></i>
                                            <span class="spinner-border spinner-border-sm" role="status" wire:loading
                                                wire:target="closeSession('{{ $session->id }}')"></span>
                                        </button>
                                    @else
                                        <button class="btn btn-icon btn-sm btn-outline-secondary" disabled
                                            title="Locked">
                                            <i class="bx bx-lock-alt"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="card-body text-center py-5">
                <div class="mb-3">
                    <i class="bx bx-coffee-togo text-muted opacity-25" style="font-size: 5rem;"></i>
                </div>
                <h5 class="text-muted">No sessions found</h5>
                <p class="text-muted small mx-auto" style="max-width: 300px;">
                    All tables are currently free. Scanned QR codes or manual check-ins will appear here.
                </p>
            </div>
        @endif
    </div>

    {{-- ── Merge Modal ─────────────────────────────────────────────────── --}}
    <div x-show="showMergeModal" class="modal-backdrop-custom" x-cloak>
        <div class="modal d-block" tabindex="-1" @click.self="showMergeModal = false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header border-bottom py-3">
                        <h5 class="modal-title fw-bold">
                            <i class="bx bx-git-merge text-warning me-2"></i>Consolidate Sessions
                        </h5>
                        <button type="button" class="btn-close" @click="showMergeModal = false"></button>
                    </div>
                    <div class="modal-body py-4">
                        <div
                            class="d-flex align-items-center mb-4 p-3 rounded bg-label-warning text-dark border-start border-warning border-4">
                            <i class="bx bx-error-circle fs-2 me-3"></i>
                            <div class="small">
                                You are merging <strong x-text="selected.length"></strong> sessions.
                                All orders will be moved to the target session. The others will be permanently deleted.
                            </div>
                        </div>

                        <label class="form-label fw-bold mb-3">Select Primary Session (Merge Into)</label>

                        @error('merge')
                            <div class="alert alert-danger py-2 small mb-3">
                                <i class="bx bx-error-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror

                        <div class="d-flex flex-column gap-2">
                            <template x-for="id in selected" :key="id">
                                <div @click="mergeTarget = id"
                                    :class="mergeTarget === id ?
                                        'bg-label-primary border-primary shadow-sm' :
                                        'bg-light border'"
                                    class="p-3 border rounded cursor-pointer d-flex align-items-center gap-3">
                                    <div class="form-check mb-0" style="pointer-events:none;">
                                        <input class="form-check-input" type="radio" :checked="mergeTarget === id">
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-bold text-dark" x-text="sessionData[id]?.table"></span>
                                            <span class="badge bg-primary" x-text="sessionData[id]?.total"></span>
                                        </div>
                                        <div class="text-muted small mt-1">
                                            <i class="bx bx-time-five me-1"></i>
                                            <span
                                                x-text="'Opened ' + sessionData[id]?.opened_at + ' · ' + sessionData[id]?.orders + ' orders'"></span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="modal-footer border-top py-3">
                        <button type="button" class="btn btn-label-secondary"
                            @click="showMergeModal = false">Cancel</button>
                        <button type="button" class="btn btn-primary" wire:click="mergeSessions"
                            wire:loading.attr="disabled" wire:target="mergeSessions" :disabled="!mergeTarget">
                            <span wire:loading.remove wire:target="mergeSessions">Merge Now</span>
                            <span wire:loading wire:target="mergeSessions">
                                <span class="spinner-border spinner-border-sm me-1" role="status"></span>Merging...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Create Session Modal ─────────────────────────────────────────── --}}
    <div x-show="showCreateModal" class="modal-backdrop-custom" x-cloak>
        <div class="modal d-block" tabindex="-1" @click.self="showCreateModal = false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header border-bottom py-3">
                        <h5 class="modal-title fw-bold">
                            <i class="bx bx-plus-circle text-primary me-2"></i>Open New Session
                        </h5>
                        <button type="button" class="btn-close" @click="showCreateModal = false"></button>
                    </div>
                    <form wire:submit.prevent="openNewSession">
                        <div class="modal-body py-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Select Table</label>
                                <select wire:model="selectedTableId"
                                    class="form-select form-select-lg @error('selectedTableId') is-invalid @enderror">
                                    <option value="">Choose a table...</option>
                                    @foreach ($this->tables as $table)
                                        <option value="{{ $table->id }}">
                                            Table {{ $table->table_number }} ({{ $table->section ?? 'No Section' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('selectedTableId')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-0">
                                <label class="form-label fw-bold">Guest Count</label>
                                <input type="number" wire:model="guestCount"
                                    class="form-control form-control-lg @error('guestCount') is-invalid @enderror"
                                    min="1" max="50">
                                @error('guestCount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text mt-2 small">Starting a session will mark the table as occupied.
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-top py-3">
                            <button type="button" class="btn btn-label-secondary"
                                @click="showCreateModal = false">Cancel</button>
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled"
                                wire:target="openNewSession">
                                <span wire:loading.remove wire:target="openNewSession">Open Session</span>
                                <span wire:loading wire:target="openNewSession">Processing...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

@push('styles')
    <style>
        .table-selected {
            background-color: rgba(105, 108, 255, 0.05) !important;
        }

        .modal-backdrop-custom {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 2000;
        }

        .table-sessions-wrap .card {
            border-radius: 0.75rem;
        }

        .pulse-animation {
            animation: pulse-border 2s infinite;
        }

        @keyframes pulse-border {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 171, 0, .4);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(255, 171, 0, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(255, 171, 0, 0);
            }
        }

        .session-check {
            width: 1.25rem;
            height: 1.25rem;
            cursor: pointer;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .opacity-50 {
            opacity: 0.5;
            transition: opacity 0.15s;
        }
    </style>
@endpush
