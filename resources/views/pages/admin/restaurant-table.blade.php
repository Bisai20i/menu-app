@extends('layouts.order-layout')

@section('title', 'Restaurant Tables - Saral Menu')

@section('title', 'Table Management')

@push('styles')
    <style>
        /* ── Grid & Cards ─────────────────────────────────────────── */
        .tables-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        .table-card {
            position: relative;
            background: #fff;
            border: 2px solid #e9ecef;
            border-radius: 16px;
            padding: 1.25rem;
            cursor: pointer;
            transition: border-color 0.18s ease, box-shadow 0.18s ease, transform 0.15s ease;
            user-select: none;
            overflow: hidden;
        }

        .table-card:hover {
            border-color: var(--bs-primary);
            box-shadow: 0 4px 20px rgba(105, 108, 255, 0.12);
            transform: translateY(-1px);
        }

        .table-card.selected {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 3px rgba(255, 105, 105, 0.18);
            background: #fafafe;
        }

        .table-card.status-occupied {
            border-color: rgba(255, 62, 29, 0.13);
        }

        .table-card.status-occupied:hover,
        .table-card.status-occupied.selected {
            border-color: #ff3e1d;
            box-shadow: 0 0 0 3px rgba(255, 62, 29, 0.12);
        }

        .table-card.status-reserved {
            border-color: rgba(255, 171, 0, 0.13);
        }

        .table-card.status-reserved:hover,
        .table-card.status-reserved.selected {
            border-color: #ffab00;
            box-shadow: 0 0 0 3px rgba(255, 171, 0, 0.12);
        }

        .table-card.inactive {
            opacity: 0.45;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* Status dot */
        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            flex-shrink: 0;
        }

        .status-dot.occupied {
            background: #ff3e1d;
            animation: pulse-red 1.8s infinite;
        }

        .status-dot.available {
            background: #71dd37;
        }

        .status-dot.reserved {
            background: #ffab00;
        }

        @keyframes pulse-red {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 62, 29, .45);
            }

            70% {
                box-shadow: 0 0 0 7px rgba(255, 62, 29, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(255, 62, 29, 0);
            }
        }

        /* Table icon */
        .table-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            background: #f0f0ff;
            color: #696cff;
            flex-shrink: 0;
        }

        .table-icon.occupied {
            background: #fff0ee;
            color: #ff3e1d;
        }

        .table-icon.reserved {
            background: #fff8e6;
            color: #ffab00;
        }

        .table-icon.available {
            background: #f0fae8;
            color: #71dd37;
        }

        /* Selected strip */
        .selected-strip {
            display: none;
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--bs-primary);
            border-radius: 16px 0 0 16px;
        }

        .table-card.selected .selected-strip {
            display: block;
        }

        /* ── Sidebar ──────────────────────────────────────────────── */
        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.35);
            z-index: 1040;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s ease;
        }

        .sidebar-overlay.open {
            opacity: 1;
            pointer-events: all;
        }

        .table-sidebar {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            width: 420px;
            max-width: 100vw;
            background: #fff;
            z-index: 1050;
            display: flex;
            flex-direction: column;
            box-shadow: -8px 0 40px rgba(0, 0, 0, 0.12);
            transform: translateX(100%);
            transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .table-sidebar.open {
            transform: translateX(0);
        }

        .sidebar-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-shrink: 0;
        }

        .sidebar-body {
            flex: 1;
            overflow-y: auto;
            padding: 1.25rem 1.5rem;
        }

        .sidebar-body::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-body::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-body::-webkit-scrollbar-thumb {
            background: #dee2e6;
            border-radius: 4px;
        }

        /* Session card */
        .session-card {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .session-card-header {
            padding: 0.875rem 1rem;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
        }

        /* Order rows */
        .order-row {
            border: 1px solid #e9ecef;
            border-radius: 10px;
            margin-bottom: 0.5rem;
            overflow: hidden;
        }

        .order-row-header {
            padding: 0.6rem 0.875rem;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            gap: 0.5rem;
        }

        .order-items-list {
            padding: 0.5rem 0.875rem;
            border-top: 1px solid #f0f0f0;
            display: none;
        }

        .order-items-list.open {
            display: block;
        }

        .order-item-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.35rem 0;
            font-size: 0.82rem;
            border-bottom: 1px dashed #f0f0f0;
        }

        .order-item-row:last-child {
            border-bottom: none;
        }

        /* Grand total bar */
        .grand-total-bar {
            padding: 1rem 1.5rem;
            border-top: 1px solid #e9ecef;
            background: #fafafa;
            flex-shrink: 0;
            display: none;
        }

        .grand-total-bar.visible {
            display: block;
        }

        /* Skeleton */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.4s infinite;
            border-radius: 6px;
            display: block;
        }

        @keyframes shimmer {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        /* Flash on real-time update */
        @keyframes flash-update {
            0% {
                background: #fff;
            }

            30% {
                background: #f0f0ff;
            }

            100% {
                background: #fff;
            }
        }

        .table-card.just-updated {
            animation: flash-update 1s ease;
        }

        /* Empty state */
        .tables-empty {
            grid-column: 1 / -1;
            text-align: center;
            padding: 4rem 1rem;
        }

        @media (max-width: 1080px) {
            .tables-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .tables-grid {
                grid-template-columns: 1fr;
            }

            .table-sidebar {
                width: 100vw;
            }
        }
    </style>
@endpush

@section('content')
    <div>

        {{-- ── Page Header ─────────────────────────────────────────── --}}
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
            <div>
                <h4 class="fw-bold mb-1">
                    <i class="bx bx-table me-2 text-primary"></i>Table Overview
                </h4>
                <p class="text-muted mb-0 small">
                    <span id="stat-total">{{ $tables->count() }}</span> tables &nbsp;·&nbsp;
                    <span id="stat-occupied"
                        class="text-danger fw-semibold">{{ $tables->where('status', 'occupied')->count() }}</span> occupied
                    &nbsp;·&nbsp;
                    <span id="stat-available"
                        class="text-success fw-semibold">{{ $tables->where('status', 'available')->count() }}</span> available
                </p>
            </div>

            {{-- Filters --}}
            <div class="d-flex gap-2 align-items-center flex-wrap">
                @php $sections = $tables->pluck('section')->filter()->unique()->values(); @endphp
                @if ($sections->isNotEmpty())
                    <div class="btn-group" id="section-filters">
                        <button class="btn btn-sm btn-primary" data-section="">All</button>
                        @foreach ($sections as $sec)
                            <button class="btn btn-sm btn-outline-primary"
                                data-section="{{ $sec }}">{{ $sec }}</button>
                        @endforeach
                    </div>
                @endif

                <div class="btn-group" id="status-filters">
                    <button class="btn btn-sm btn-dark" data-status="">All Status</button>
                    <button class="btn btn-sm btn-outline-secondary" data-status="available">Free</button>
                    <button class="btn btn-sm btn-outline-secondary" data-status="occupied">Occupied</button>
                </div>
            </div>
        </div>

        {{-- ── Tables Grid ── --}}
        <div class="tables-grid" id="tables-grid">

            @forelse ($tables as $table)
                <div class="table-card status-{{ $table->status }}{{ !$table->is_active ? ' inactive' : '' }}"
                    id="table-card-{{ $table->id }}" data-id="{{ $table->id }}" data-uuid="{{ $table->uuid }}"
                    data-number="{{ $table->table_number }}" data-section="{{ $table->section }}"
                    data-capacity="{{ $table->capacity }}" data-status="{{ $table->status }}"
                    data-sessions="{{ $table->activeSessions->count() }}">
                    <div class="selected-strip"></div>

                    <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="table-icon {{ $table->status }}" id="icon-{{ $table->id }}">
                                <i class="bx bx-table"></i>
                            </div>
                            <div>
                                <div class="fw-bold fs-5 lh-1">Table {{ $table->table_number }}</div>
                                @if ($table->section)
                                    <div class="text-muted small mt-1">{{ $table->section }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="status-dot {{ $table->status }}" id="dot-{{ $table->id }}"></span>
                            <span class="small fw-semibold text-capitalize"
                                id="status-text-{{ $table->id }}">{{ $table->status }}</span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex gap-3">
                            <span class="text-muted small">
                                <i class="bx bx-user me-1"></i>{{ $table->capacity }} seats
                            </span>
                            <span
                                class="text-muted small session-count-label{{ $table->activeSessions->count() === 0 ? ' d-none' : '' }}"
                                id="session-label-{{ $table->id }}">
                                <i class="bx bx-receipt me-1 text-warning"></i>
                                <span id="session-count-{{ $table->id }}">{{ $table->activeSessions->count() }}</span>
                                {{ $table->activeSessions->count() === 1 ? 'session' : 'sessions' }}
                            </span>
                        </div>
                        <i class="bx bx-chevron-right text-muted" id="chevron-{{ $table->id }}"></i>
                    </div>
                </div>

            @empty
                <div class="tables-empty">
                    <i class="bx bx-table text-muted" style="font-size:4rem;opacity:.2;"></i>
                    <h5 class="text-muted mt-3">No tables found</h5>
                    <p class="text-muted small">Add tables to your restaurant to see them here.</p>
                </div>
            @endforelse

        </div>
    </div>

    {{-- ── Sidebar Overlay ──────────────────────────────────────────── --}}
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    {{-- ── Sidebar Panel ────────────────────────────────────────────── --}}
    <aside class="table-sidebar" id="table-sidebar">

        <div class="sidebar-header">
            <div class="d-flex align-items-center gap-3">
                <div class="table-icon available" id="sidebar-icon"
                    style="width:40px;height:40px;font-size:1.1rem;border-radius:10px;">
                    <i class="bx bx-table"></i>
                </div>
                <div>
                    <div class="fw-bold" id="sidebar-title">—</div>
                    <div class="text-muted small d-flex align-items-center gap-1">
                        <span class="status-dot available" id="sidebar-dot" style="width:7px;height:7px;"></span>
                        <span id="sidebar-status-text" class="text-capitalize">—</span>
                        <span id="sidebar-section-text"></span>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <button id="sidebar-refresh-btn" class="btn btn-icon btn-sm btn-outline-secondary" title="Refresh">
                    <i class="bx bx-refresh" id="sidebar-refresh-icon"></i>
                </button>
                <button id="sidebar-close-btn" class="btn btn-icon btn-sm btn-outline-secondary">
                    <i class="bx bx-x"></i>
                </button>
            </div>
        </div>

        <div class="sidebar-body" id="sidebar-body"></div>

        <div class="grand-total-bar" id="grand-total-bar">
            <div class="d-flex align-items-center justify-content-between">
                <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing:.06em;">Grand Total</span>
                <span class="fw-bold fs-5 text-primary" id="grand-total-value">Rs. 0.00</span>
            </div>
        </div>

    </aside>

@endsection

@push('scripts')
    <script>
        (function() {
            'use strict';

            // ── Config ────────────────────────────────────────────────────
            const RESTAURANT_ID = {{ $restaurant->id }};
            const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '{{ csrf_token() }}';

            // ── State ─────────────────────────────────────────────────────
            let selectedTableId = null;
            let selectedTableUuid = null;
            let sidebarOpen = false;
            let sidebarLoading = false;
            let closingSession = null;
            let activeFilter = {
                section: '',
                status: ''
            };

            // Mirror server-rendered data so we can update stats without extra requests
            const tableStates = {};
            document.querySelectorAll('.table-card[data-id]').forEach(card => {
                tableStates[card.dataset.id] = {
                    status: card.dataset.status,
                    sessionCount: parseInt(card.dataset.sessions, 10) || 0,
                };
            });

            // ── DOM refs ──────────────────────────────────────────────────
            const overlay = document.getElementById('sidebar-overlay');
            const sidebar = document.getElementById('table-sidebar');
            const sidebarBody = document.getElementById('sidebar-body');
            const grandTotalBar = document.getElementById('grand-total-bar');
            const grandTotalVal = document.getElementById('grand-total-value');
            const sidebarTitle = document.getElementById('sidebar-title');
            const sidebarDot = document.getElementById('sidebar-dot');
            const sidebarIcon = document.getElementById('sidebar-icon');
            const sidebarStatus = document.getElementById('sidebar-status-text');
            const sidebarSec = document.getElementById('sidebar-section-text');
            const refreshBtn = document.getElementById('sidebar-refresh-btn');
            const refreshIcon = document.getElementById('sidebar-refresh-icon');

            // ── Sidebar open / close ──────────────────────────────────────
            function openSidebar() {
                sidebar.classList.add('open');
                overlay.classList.add('open');
                sidebarOpen = true;
            }

            function closeSidebar() {
                sidebar.classList.remove('open');
                overlay.classList.remove('open');
                sidebarOpen = false;

                if (selectedTableId) {
                    const prev = document.getElementById(`table-card-${selectedTableId}`);
                    if (prev) prev.classList.remove('selected');
                    const ch = document.getElementById(`chevron-${selectedTableId}`);
                    if (ch) {
                        ch.classList.remove('text-primary');
                        ch.classList.add('text-muted');
                    }
                }

                selectedTableId = null;
                selectedTableUuid = null;
            }

            document.getElementById('sidebar-close-btn').addEventListener('click', closeSidebar);
            overlay.addEventListener('click', closeSidebar);
            refreshBtn.addEventListener('click', () => {
                if (selectedTableUuid) fetchSessions(selectedTableUuid);
            });

            // ── Table card click ──────────────────────────────────────────
            document.getElementById('tables-grid').addEventListener('click', function(e) {
                const card = e.target.closest('.table-card[data-id]');
                if (!card || card.classList.contains('inactive')) return;

                // Deselect previous card
                if (selectedTableId && selectedTableId !== card.dataset.id) {
                    const prev = document.getElementById(`table-card-${selectedTableId}`);
                    if (prev) prev.classList.remove('selected');
                    const ch = document.getElementById(`chevron-${selectedTableId}`);
                    if (ch) {
                        ch.classList.remove('text-primary');
                        ch.classList.add('text-muted');
                    }
                }

                selectedTableId = card.dataset.id;
                selectedTableUuid = card.dataset.uuid;

                card.classList.add('selected');
                const ch = document.getElementById(`chevron-${selectedTableId}`);
                if (ch) {
                    ch.classList.add('text-primary');
                    ch.classList.remove('text-muted');
                }

                updateSidebarHeader(card.dataset.number, card.dataset.section, card.dataset.status);
                openSidebar();
                fetchSessions(selectedTableUuid);
            });

            function updateSidebarHeader(number, section, status) {
                sidebarTitle.textContent = 'Table ' + number;
                sidebarStatus.textContent = status;
                sidebarSec.textContent = section ? '· ' + section : '';

                ['available', 'occupied', 'reserved'].forEach(s => {
                    sidebarDot.classList.remove(s);
                    sidebarIcon.classList.remove(s);
                });
                sidebarDot.classList.add(status);
                sidebarIcon.classList.add(status);
            }

            // ── Fetch sessions ────────────────────────────────────────────
            async function fetchSessions(uuid) {
                if (sidebarLoading) return;
                sidebarLoading = true;
                refreshIcon.classList.add('bx-spin');
                renderSkeletons();

                try {
                    const res = await fetch(`/master/tables/${uuid}/sessions`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                    });
                    const data = await res.json();
                    renderSessions(data.sessions ?? []);
                } catch (err) {
                    console.error('Failed to fetch sessions', err);
                    sidebarBody.innerHTML =
                        `<p class="text-danger small text-center py-4">Failed to load sessions.</p>`;
                    grandTotalBar.classList.remove('visible');
                } finally {
                    sidebarLoading = false;
                    refreshIcon.classList.remove('bx-spin');
                }
            }

            // ── Close session ─────────────────────────────────────────────
            async function closeSession(sessionUuid) {
                if (closingSession) return;
                closingSession = sessionUuid;

                const btn = document.querySelector(`[data-close-session="${sessionUuid}"]`);
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = `<span class="spinner-border spinner-border-sm"></span>`;
                }

                try {
                    const res = await fetch(`/master/sessions/${sessionUuid}/close`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': CSRF,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json',
                        },
                    });
                    const data = await res.json();
                    if (data.success && selectedTableUuid) {
                        fetchSessions(selectedTableUuid);
                    }
                } catch (err) {
                    console.error('Failed to close session', err);
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = `<i class="bx bx-power-off"></i> Close`;
                    }
                } finally {
                    closingSession = null;
                }
            }

            // ── Render: skeletons ─────────────────────────────────────────
            function renderSkeletons() {
                sidebarBody.innerHTML = `
            <span class="skeleton mb-2" style="height:18px;width:60%;"></span>
            <span class="skeleton mb-3" style="height:14px;width:40%;"></span>
            <span class="skeleton mb-2" style="height:100px;border-radius:12px;"></span>
            <span class="skeleton"      style="height:80px;border-radius:12px;"></span>`;
                grandTotalBar.classList.remove('visible');
            }

            // ── Render: sessions list ─────────────────────────────────────
            function renderSessions(sessions) {
                if (!sessions.length) {
                    sidebarBody.innerHTML = `
                <div class="text-center py-5">
                    <i class="bx bx-coffee text-muted" style="font-size:3rem;opacity:.25;"></i>
                    <p class="text-muted mt-3 mb-0">No active sessions</p>
                    <p class="text-muted small">This table is currently free.</p>
                </div>`;
                    grandTotalBar.classList.remove('visible');
                    return;
                }

                const grandTotal = sessions.reduce((sum, s) => sum + (parseFloat(s.grand_total) || 0), 0);

                let html = `<p class="text-muted small mb-3">
            ${sessions.length} active ${sessions.length === 1 ? 'session' : 'sessions'}
        </p>`;

                sessions.forEach(session => {
                    const openedAt = session.opened_at ?
                        new Date(session.opened_at).toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        }) :
                        '—';
                    const guestStr = session.guest_count ? ` · ${session.guest_count} guests` : '';

                    html += `
            <div class="session-card">
                <div class="session-card-header">
                    <div>
                        <div class="fw-semibold small d-flex align-items-center gap-2">
                            <span class="badge bg-label-success rounded-pill px-2">
                                <i class="bx bxs-circle me-1" style="font-size:.55rem;"></i>Active
                            </span>
                            <span class="text-muted">by ${escHtml(session.opened_by)}</span>
                        </div>
                        <div class="text-muted mt-1" style="font-size:.76rem;">
                            Opened ${openedAt}${escHtml(guestStr)}
                        </div>
                    </div>
                    <button
                        class="btn btn-sm btn-danger d-flex align-items-center gap-1"
                        data-close-session="${session.uuid}"
                    >
                        <i class="bx bx-power-off"></i>
                        <span>Close</span>
                    </button>
                </div>
                <div class="p-2">
                    ${renderOrders(session.orders)}
                </div>
            </div>`;
                });

                sidebarBody.innerHTML = html;

                // Bind close-session buttons
                sidebarBody.querySelectorAll('[data-close-session]').forEach(btn => {
                    btn.addEventListener('click', () => closeSession(btn.dataset.closeSession));
                });

                // Bind order accordion toggles
                sidebarBody.querySelectorAll('.order-row-header').forEach(header => {
                    header.addEventListener('click', () => {
                        const list = header.nextElementSibling;
                        const icon = header.querySelector('.toggle-icon');
                        if (!list) return;
                        const isOpen = list.classList.toggle('open');
                        if (icon) {
                            icon.className =
                                `bx small text-muted toggle-icon ${isOpen ? 'bx-chevron-up' : 'bx-chevron-down'}`;
                        }
                    });
                });

                grandTotalVal.textContent = 'Rs. ' + grandTotal.toFixed(2);
                grandTotalBar.classList.add('visible');
            }

            // ── Render: orders ────────────────────────────────────────────
            function renderOrders(orders) {
                if (!orders || !orders.length) {
                    return `<p class="text-muted small text-center py-2 mb-0">No orders yet</p>`;
                }

                return orders.map(order => {
                    const statusClass = {
                        pending: 'bg-label-warning',
                        confirmed: 'bg-label-primary',
                        served: 'bg-label-success',
                    } [order.status] ?? 'bg-label-secondary';

                    const paidBadge = order.is_paid ?
                        `<span class="badge bg-label-success rounded-pill">Paid</span>` :
                        '';

                    const itemsHtml = (order.items || []).map(item => `
                <div class="order-item-row">
                    <div class="d-flex align-items-start gap-2">
                        <span class="badge bg-label-secondary rounded-pill" style="font-size:.7rem;min-width:28px;">
                            ×${escHtml(String(item.quantity))}
                        </span>
                        <div>
                            <div>${escHtml(item.name)}</div>
                            ${item.special_request
                                ? `<div class="text-muted fst-italic" style="font-size:.73rem;">${escHtml(item.special_request)}</div>`
                                : ''}
                        </div>
                    </div>
                    <span class="text-muted">Rs. ${parseFloat(item.subtotal).toFixed(2)}</span>
                </div>
            `).join('');

                    const noteHtml = order.note ?
                        `<div class="mt-2 p-2 rounded" style="background:#fff8e6;font-size:.78rem;">
                       <i class="bx bx-note me-1 text-warning"></i>${escHtml(order.note)}
                   </div>` :
                        '';

                    return `
            <div class="order-row">
                <div class="order-row-header">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bx bx-receipt text-muted small"></i>
                        <span class="small fw-medium">Order</span>
                        <span class="badge rounded-pill ${statusClass}">${escHtml(order.status)}</span>
                        ${paidBadge}
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="small fw-bold text-primary">Rs. ${parseFloat(order.total_amount).toFixed(2)}</span>
                        <i class="bx bx-chevron-down small text-muted toggle-icon"></i>
                    </div>
                </div>
                <div class="order-items-list">
                    ${itemsHtml}
                    ${noteHtml}
                </div>
            </div>`;
                }).join('');
            }

            // ── Filters ───────────────────────────────────────────────────
            function applyFilters() {
                document.querySelectorAll('.table-card[data-id]').forEach(card => {
                    const sectionOk = !activeFilter.section || card.dataset.section === activeFilter.section;
                    const statusOk = !activeFilter.status || tableStates[card.dataset.id]?.status ===
                        activeFilter.status;
                    card.style.display = (sectionOk && statusOk) ? '' : 'none';
                });
                updateStats();
            }

            const sectionFiltersEl = document.getElementById('section-filters');
            if (sectionFiltersEl) {
                sectionFiltersEl.addEventListener('click', e => {
                    const btn = e.target.closest('button[data-section]');
                    if (!btn) return;
                    activeFilter.section = btn.dataset.section;
                    sectionFiltersEl.querySelectorAll('button').forEach(b => {
                        b.className = b === btn ? 'btn btn-sm btn-primary' :
                            'btn btn-sm btn-outline-primary';
                    });
                    applyFilters();
                });
            }

            document.getElementById('status-filters').addEventListener('click', e => {
                const btn = e.target.closest('button[data-status]');
                if (!btn) return;
                activeFilter.status = btn.dataset.status;
                document.querySelectorAll('#status-filters button').forEach(b => {
                    const active = b === btn;
                    if (b.dataset.status === '') b.className =
                        `btn btn-sm ${active ? 'btn-dark'    : 'btn-outline-secondary'}`;
                    else if (b.dataset.status === 'available') b.className =
                        `btn btn-sm ${active ? 'btn-success' : 'btn-outline-secondary'}`;
                    else if (b.dataset.status === 'occupied') b.className =
                        `btn btn-sm ${active ? 'btn-danger'  : 'btn-outline-secondary'}`;
                });
                applyFilters();
            });

            // ── Stats ─────────────────────────────────────────────────────
            function updateStats() {
                const states = Object.values(tableStates);
                document.getElementById('stat-total').textContent = states.length;
                document.getElementById('stat-occupied').textContent = states.filter(s => s.status === 'occupied')
                    .length;
                document.getElementById('stat-available').textContent = states.filter(s => s.status === 'available')
                    .length;
            }

            // ── Real-time card update ─────────────────────────────────────
            function updateTableCard(tableId, newStatus, sessionCountDelta) {
                const card = document.getElementById(`table-card-${tableId}`);
                if (!card) return;

                if (tableStates[tableId]) {
                    tableStates[tableId].status = newStatus;
                    if (typeof sessionCountDelta === 'number') {
                        tableStates[tableId].sessionCount = Math.max(0, tableStates[tableId].sessionCount +
                            sessionCountDelta);
                    }
                }

                // Swap CSS status class
                ['status-available', 'status-occupied', 'status-reserved'].forEach(c => card.classList.remove(c));
                card.classList.add(`status-${newStatus}`);
                card.dataset.status = newStatus;

                // Update dot, text, icon
                const dot = document.getElementById(`dot-${tableId}`);
                const text = document.getElementById(`status-text-${tableId}`);
                const icon = document.getElementById(`icon-${tableId}`);

                if (dot) {
                    ['available', 'occupied', 'reserved'].forEach(c => dot.classList.remove(c));
                    dot.classList.add(newStatus);
                }
                if (text) {
                    text.textContent = newStatus;
                }
                if (icon) {
                    ['available', 'occupied', 'reserved'].forEach(c => icon.classList.remove(c));
                    icon.classList.add(newStatus);
                }

                // Session count label
                const count = tableStates[tableId]?.sessionCount ?? 0;
                const countEl = document.getElementById(`session-count-${tableId}`);
                const labelEl = document.getElementById(`session-label-${tableId}`);
                if (countEl) countEl.textContent = count;
                if (labelEl) labelEl.classList.toggle('d-none', count === 0);

                // Flash animation
                card.classList.remove('just-updated');
                void card.offsetWidth; // force reflow to restart animation
                card.classList.add('just-updated');
                setTimeout(() => card.classList.remove('just-updated'), 1100);

                updateStats();
                applyFilters();
            }

            // ── Laravel Echo ──────────────────────────────────────────────
            if (typeof Echo !== 'undefined') {
                Echo.private(`restaurant.${RESTAURANT_ID}`)
                    .listen('.TableStatusUpdated', (e) => {
                        const {
                            table,
                            session
                        } = e;
                        if (!table) return;

                        const delta = session ? (session.status === 'active' ? 1 : -1) : null;
                        updateTableCard(String(table.id), table.status, delta);

                        // If sidebar is open for this table, sync header + reload sessions
                        if (sidebarOpen && selectedTableId === String(table.id)) {
                            updateSidebarHeader(
                                document.getElementById(`table-card-${table.id}`)?.dataset.number ?? '',
                                document.getElementById(`table-card-${table.id}`)?.dataset.section ?? '',
                                table.status
                            );
                            fetchSessions(selectedTableUuid);
                        }
                    })
                    .listen('.OrderStatusUpdated', () => {
                        if (sidebarOpen && selectedTableUuid) {
                            fetchSessions(selectedTableUuid);
                        }
                    });
            }

            // ── Utility ───────────────────────────────────────────────────
            function escHtml(str) {
                return String(str ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;');
            }

        })();
    </script>
@endpush
