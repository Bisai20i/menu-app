@extends('layouts.admin-layout')

@push('title')
    Detailed Report - {{ $restaurant->name }}
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.35.0/dist/apexcharts.css">
<style>
    .report-card { border-radius: 12px; transition: all 0.2s; }
    .report-card:hover { transform: translateY(-3px); }
    .stat-icon { width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; border-radius: 8px; }
    @media print {
        .layout-menu, .layout-navbar, .btn { display: none !important; }
        .content-wrapper { padding: 0 !important; }
        .container-xxl { max-width: 100% !important; }
    }
</style>
@endpush

@section('content')
<div class="mb-4 d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center text-truncate">
        <a href="{{ route('master.reports.index') }}" class="btn btn-icon btn-label-secondary me-3 shadow-none border-0">
            <i class="bx bx-chevron-left"></i>
        </a>
        <div class="text-truncate">
            <h4 class="fw-bold mb-0 text-truncate">{{ $restaurant->name }} - Performance Analysis</h4>
            <p class="text-muted mb-0 small text-uppercase ls-1">Analytics Dashboard</p>
        </div>
    </div>
    <div class="d-none d-md-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-secondary shadow-xs">
            <i class="bx bx-printer me-1"></i> Print PDF
        </button>
    </div>
</div>

<!-- Summary Stats -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card report-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="stat-icon bg-label-primary shadow-xs">
                        <i class="bx bx-receipt fs-4"></i>
                    </div>
                </div>
                <h6 class="text-muted fw-normal mb-1 small">Total Orders</h6>
                <h4 class="fw-bold text-dark mb-0">{{ number_format($totals->total_orders) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card report-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="stat-icon bg-label-success shadow-xs">
                        <i class="bx bx-wallet fs-4"></i>
                    </div>
                </div>
                <h6 class="text-muted fw-normal mb-1 small">Total Revenue</h6>
                <h4 class="fw-bold text-dark mb-0">Rs. {{ number_format($totals->total_revenue, 2) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card report-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="stat-icon bg-label-info shadow-xs">
                        <i class="bx bx-show-alt fs-4"></i>
                    </div>
                </div>
                <h6 class="text-muted fw-normal mb-1 small">Total Menu Views</h6>
                <h4 class="fw-bold text-dark mb-0">{{ number_format($totals->total_views) }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card report-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="stat-icon bg-label-warning shadow-xs">
                        <i class="bx bx-group fs-4"></i>
                    </div>
                </div>
                <h6 class="text-muted fw-normal mb-1 small">Owner/Admin</h6>
                <h5 class="fw-bold text-dark mb-0 text-truncate">{{ $restaurant->admin->name ?? 'N/A' }}</h5>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Revenue Chart -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-bold">Revenue & Order Trends (Last 30 Active Days)</h6>
            </div>
            <div class="card-body">
                <div id="revenueChart" style="min-height: 350px;"></div>
            </div>
        </div>
    </div>
    <!-- Menu Views Chart -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-bold">Menu Engagement (Views)</h6>
            </div>
            <div class="card-body">
                <div id="viewsChart" style="min-height: 350px;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const stats = @json($stats);
        
        if (stats.length === 0) {
            document.querySelectorAll("#revenueChart, #viewsChart").forEach(el => {
                el.innerHTML = '<div class="d-flex h-100 align-items-center justify-content-center py-5 text-muted small"><i class="bx bx-info-circle me-1"></i> No statistical data available yet.</div>';
            });
            return;
        }

        const dates = stats.map(s => s.date.split('T')[0]);
        const revenues = stats.map(s => parseFloat(s.total_revenue));
        const orders = stats.map(s => s.total_orders);
        const views = stats.map(s => s.menu_views);

        // Revenue & Orders Chart
        var revenueOptions = {
            series: [{
                name: 'Revenue (Rs.)',
                type: 'column',
                data: revenues
            }, {
                name: 'Orders Count',
                type: 'line',
                data: orders
            }],
            chart: {
                height: 350,
                type: 'line',
                stacked: false,
                toolbar: { show: false }
            },
            stroke: { width: [0, 4], curve: 'smooth' },
            plotOptions: { bar: { columnWidth: '50%', borderRadius: 4 } },
            fill: { opacity: [0.85, 1], gradient: { inverseColors: false, shade: 'light', type: "vertical" } },
            colors: ['#696cff', '#03c3ec'],
            labels: dates,
            xaxis: { type: 'datetime' },
            yaxis: [{
                title: { text: 'Revenue (Rs.)', style: { color: '#696cff' } },
                labels: { style: { colors: '#696cff' } }
            }, {
                opposite: true,
                title: { text: 'Total Orders', style: { color: '#03c3ec' } },
                labels: { style: { colors: '#03c3ec' } }
            }],
            tooltip: { shared: true, intersect: false, y: { formatter: val => val.toLocaleString() } },
            legend: { position: 'top', horizontalAlign: 'right' }
        };

        var revenueChart = new ApexCharts(document.querySelector("#revenueChart"), revenueOptions);
        revenueChart.render();

        // Views Chart
        var viewsOptions = {
            series: [{ name: 'Unique Views', data: views }],
            chart: { height: 350, type: 'area', toolbar: { show: false }, zoom: { enabled: false } },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.7, opacityTo: 0.3, stops: [0, 90, 100] } },
            colors: ['#71dd37'],
            xaxis: { type: 'datetime', categories: dates },
            tooltip: { x: { format: 'dd MMM yyyy' } },
            legend: { show: false }
        };

        var viewsChart = new ApexCharts(document.querySelector("#viewsChart"), viewsOptions);
        viewsChart.render();
    });
</script>
@endpush
