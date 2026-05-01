@extends('layouts.admin-layout')

@push('title')
    Admin Dashboard
@endpush

@section('content')
    <div>
        <div class="row g-4">
            <!-- Welcome Column -->
            <div class="col-lg-8">
                
                @php
                    $adminUser = auth()->guard('admin')->user();
                    $activeSub = $adminUser->activeSubscription;
                    $isExpiringSoon = false;
                    $isExpiredGrace = false;
                    $daysRemaining = 0;
                    
                    if ($activeSub && $activeSub->expires_at && !$adminUser->is_super_admin) {
                        if ($activeSub->expires_at->isPast()) {
                            $isExpiredGrace = true;
                            // Calculate remaining grace period days carefully considering the actual difference
                            $daysPast = floor(now()->diffInSeconds($activeSub->expires_at) / 86400); 
                            $daysRemaining = $activeSub->grace_period - $daysPast;
                        } else {
                            $daysRemaining = floor(now()->diffInSeconds($activeSub->expires_at) / 86400); 
                            if ($daysRemaining <= 15) {
                                $isExpiringSoon = true;
                            }
                        }
                    }
                @endphp

                @if($isExpiredGrace)
                    <div class="alert alert-danger shadow-sm border-0 d-flex align-items-center mb-4" role="alert">
                        <i class="bx bx-error-circle fs-3 me-3"></i>
                        <div>
                            <strong>Subscription Expired!</strong> You are currently in the grace period. You have <strong>{{ max(0, $daysRemaining) }} days</strong> left to renew before you lose access.
                        </div>
                    </div>
                @elseif($isExpiringSoon)
                    <div class="alert alert-warning shadow-sm border-0 d-flex align-items-center mb-4" role="alert">
                        <i class="bx bx-time-five fs-3 me-3"></i>
                        <div>
                            <strong>Expiring Soon!</strong> Your subscription will expire in <strong>{{ $daysRemaining }} days</strong>. Please <a href="{{ route('master.billing') }}" class="alert-link">renew soon</a> to avoid interruption.
                        </div>
                    </div>
                @endif

                @if(!$adminUser->restaurant_id)
                    <div class="alert alert-danger shadow-sm border-0 d-flex align-items-center mb-4" role="alert">
                        <i class="bx bx-store-alt fs-3 me-3"></i>
                        <div>
                            <strong>Restaurant Profile Missing!</strong> You haven't created a restaurant profile yet. You need to create one to manage menus, tables, and orders.
                            <br>
                            <a href="{{ route('master.profile') }}" class="btn btn-sm btn-danger mt-2 shadow-sm">
                                <i class="bx bx-plus-circle me-1"></i> Create Restaurant Profile
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Welcome Banner --}}
                <div class="card border-0 shadow-sm bg-primary text-white mb-4 overflow-hidden">
                    <div class="card-body p-4 p-xl-5 position-relative">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h3 class="fw-bold text-white mb-2">Welcome back, {{ auth()->guard('admin')->user()->name }}! 👋</h3>
                                <p class="mb-4 opacity-75">Your restaurant's digital presence is active and ready. Manage your menu, track orders, and monitor performance from your command center.</p>
                                <a href="{{ route('master.profile') }}" class="btn btn-light text-primary px-4 shadow-sm">
                                    <i class="bx bx-cog me-2"></i> Update Settings
                                </a>
                            </div>
                            <div class="col-md-4 d-none d-md-block">
                                <i class="bx bx-rocket" style="font-size: 120px; opacity: 0.2; position: absolute; right: -20px; bottom: -20px;"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ROW 1: Quick Menu Actions + Revenue Chart --}}
                <div class="row g-4 mb-4">
                    {{-- Quick Actions --}}
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-light-primary rounded p-2 me-3">
                                        <i class="bx bx-category fs-4 text-primary"></i>
                                    </div>
                                    <h6 class="mb-0 fw-bold">Quick Menu Actions</h6>
                                </div>
                                <div class="list-group list-group-flush">
                                    <a href="{{ route('master.menu-categories.index') }}" class="list-group-item list-group-item-action border-0 px-0 d-flex justify-content-between align-items-center">
                                        <span>Manage Categories</span>
                                        <i class="bx bx-chevron-right text-muted"></i>
                                    </a>
                                    <a href="{{ route('master.menu-items.create') }}" class="list-group-item list-group-item-action border-0 px-0 d-flex justify-content-between align-items-center">
                                        <span>Add New Menu Item</span>
                                        <i class="bx bx-chevron-right text-muted"></i>
                                    </a>
                                    <a href="{{ route('master.menu-items.index') }}" class="list-group-item list-group-item-action border-0 px-0 d-flex justify-content-between align-items-center">
                                        <span>View All Items</span>
                                        <i class="bx bx-chevron-right text-muted"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Revenue Chart --}}
                    @if($hasChartAccess)
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light-success rounded p-2 me-3">
                                                <i class="bx bx-dollar-circle fs-4 text-success"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold">Revenue</h6>
                                                <small class="text-muted">Last 7 days</small>
                                            </div>
                                        </div>
                                        @if($stats->isNotEmpty())
                                            <span class="badge bg-label-success fs-6">Rs. {{ number_format($stats->sum('total_revenue'), 0) }}</span>
                                        @endif
                                    </div>
                                    @if($stats->isNotEmpty())
                                        <div id="revenueInsightChart"></div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="bx bx-line-chart fs-1 text-muted opacity-25 mb-2 d-block"></i>
                                            <p class="text-muted small mb-0">No data yet. Revenue will appear once orders are placed.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100 bg-light">
                                <div class="card-body p-4 d-flex flex-column align-items-center justify-content-center text-center" style="min-height: 350px;">
                                    <div class="mb-3">
                                        <i class="bx bx-lock fs-1 text-warning opacity-75"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-2">You are on the Free Plan</h5>
                                    <p class="text-muted mb-4">Unlock revenue charts and advanced analytics by upgrading to a premium plan.</p>
                                    <a href="{{ route('master.billing') }}" class="btn btn-primary btn-sm">
                                        <i class="bx bx-wallet me-1"></i> View Billing Options
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- ROW 2: Orders Chart --}}
                @if($hasChartAccess)
                <div class="row g-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light-primary rounded p-2 me-3">
                                            <i class="bx bx-cart fs-4 text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold">Total Orders</h6>
                                            <small class="text-muted">Last 7 days</small>
                                        </div>
                                    </div>
                                    @if($stats->isNotEmpty())
                                        <span class="badge bg-label-primary fs-6">{{ number_format($stats->sum('total_orders')) }} Orders</span>
                                    @endif
                                </div>
                                @if($stats->isNotEmpty())
                                    <div id="ordersInsightChart"></div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="bx bx-cart fs-1 text-muted opacity-25 mb-2 d-block"></i>
                                        <p class="text-muted small mb-0">No data yet. Order trends will appear once orders are placed.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif

            </div>

            <!-- QR Sidebar -->
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 20px;">
                    @livewire('menu-qr-generator')
                    
                    {{-- Plan Status --}}
                    @php
                        $plan = app(\App\Services\SubscriptionService::class)->getActivePlan(auth('admin')->user());
                    @endphp
                    @if($plan && $plan->price > 0)
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3"><i class="bx bx-crown text-warning me-2"></i>Current Plan Status</h6>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Plan</span>
                                <span class="fw-semibold">
                                    @if($plan->id === 0)
                                        <span class="badge bg-secondary-subtle text-secondary">{{ $plan->name }}</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success">{{ $plan->name }}</span>
                                    @endif
                                </span>
                            </div>
                            
                            @if(isset($activeSub) && $activeSub && $activeSub->expires_at)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">Expires On</span>
                                    <span class="fw-semibold {{ $activeSub->expires_at->isPast() ? 'text-danger' : '' }}">
                                        {{ $activeSub->expires_at->format('d M, Y') }}
                                    </span>
                                </div>
                                @if($activeSub->expires_at->isPast())
                                <div class="d-flex justify-content-between align-items-center mb-0">
                                    <span class="text-muted">Grace Period</span>
                                    <span class="fw-semibold text-danger">{{ max(0, $daysRemaining ?? 0) }} Days Left</span>
                                </div>
                                @endif
                            @elseif($plan->id !== 0)
                                <div class="d-flex justify-content-between align-items-center mb-0">
                                    <span class="text-muted">Validity</span>
                                    <span class="fw-semibold text-success">Lifetime</span>
                                </div>
                            @endif
                            <hr class="my-3">
                            <div class="d-grid">
                                <a href="{{ route('master.billing') }}" class="btn btn-outline-primary">Manage Billing</a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-light-primary { background-color: rgba(var(--bs-primary-rgb), 0.1); }
        .bg-light-info    { background-color: rgba(var(--bs-info-rgb), 0.1); }
        .bg-light-success { background-color: rgba(var(--bs-success-rgb), 0.1); }
        .list-group-item-action:hover { background-color: transparent; color: var(--bs-primary); }
    </style>

    {{-- QR Print Modal: needed for the menu-qr-generator widget on this page --}}
    <x-qr-print-modal />
@endsection

@push('scripts')
    @if($hasChartAccess)
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(isset($stats) && $stats->isNotEmpty())
                const stats = @json($stats);

                const dates    = stats.map(s => new Date(s.date).toLocaleDateString(undefined, { month: 'short', day: 'numeric' }));
                const revenues = stats.map(s => s.total_revenue);
                const orders   = stats.map(s => s.total_orders);

                // ── Revenue Chart ──────────────────────────────────────────────────
                new ApexCharts(document.querySelector('#revenueInsightChart'), {
                    chart: {
                        type: 'area',
                        height: 180,
                        toolbar: { show: false },
                        sparkline: { enabled: false },
                        animations: { enabled: true }
                    },
                    series: [{ name: 'Revenue (Rs.)', data: revenues }],
                    colors: ['#28c76f'],
                    stroke: { curve: 'smooth', width: 2 },
                    fill: {
                        type: 'gradient',
                        gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.04, stops: [0, 100] }
                    },
                    xaxis: {
                        categories: dates,
                        labels: { style: { fontSize: '11px', colors: '#a0a0a0' } },
                        axisBorder: { show: false },
                        axisTicks: { show: false }
                    },
                    yaxis: {
                        labels: {
                            style: { fontSize: '11px', colors: '#a0a0a0' },
                            formatter: val => 'Rs. ' + Number(val).toLocaleString()
                        }
                    },
                    grid: { borderColor: '#f0f0f0', strokeDashArray: 4 },
                    dataLabels: { enabled: false },
                    tooltip: {
                        theme: 'light',
                        y: { formatter: val => 'Rs. ' + Number(val).toLocaleString() }
                    }
                }).render();

                // ── Orders Chart ───────────────────────────────────────────────────
                new ApexCharts(document.querySelector('#ordersInsightChart'), {
                    chart: {
                        type: 'bar',
                        height: 260,
                        toolbar: { show: false },
                        animations: { enabled: true }
                    },
                    series: [{ name: 'Orders', data: orders }],
                    colors: ['#696cff'],
                    plotOptions: {
                        bar: { borderRadius: 5, columnWidth: '45%', distributed: false }
                    },
                    xaxis: {
                        categories: dates,
                        labels: { style: { fontSize: '11px', colors: '#a0a0a0' } },
                        axisBorder: { show: false },
                        axisTicks: { show: false }
                    },
                    yaxis: {
                        labels: {
                            style: { fontSize: '11px', colors: '#a0a0a0' },
                            formatter: val => Math.floor(val)
                        }
                    },
                    grid: { borderColor: '#f0f0f0', strokeDashArray: 4 },
                    dataLabels: { enabled: false },
                    tooltip: {
                        theme: 'light',
                        y: { formatter: val => val + ' orders' }
                    }
                }).render();
            @endif
        });
    </script>
    @endif
@endpush