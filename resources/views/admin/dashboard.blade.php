@extends('layouts.admin-layout')

@push('title')
    Admin Dashboard
@endpush

@section('content')
    <div>
        <div class="row g-4">
            <!-- Welcome Column -->
            <div class="col-lg-8">
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
                            <div class="col-md-4 d-none d-md-block text-center text-white">
                                <i class="bx bx-rocket" style="font-size: 120px; opacity: 0.2; position: absolute; right: -20px; bottom: -20px;"></i>
                                <img src="{{ asset('logo.png') }}" alt="logo" class="img-fluid" style="max-height: 120px; filter: brightness(0) invert(1);">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
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
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-light-info rounded p-2 me-3">
                                        <i class="bx bx-line-chart fs-4 text-info"></i>
                                    </div>
                                    <h6 class="mb-0 fw-bold">Performance Insights</h6>
                                </div>
                                <div class="text-center py-3">
                                    <i class="bx bx-analyse fs-1 text-muted opacity-25 mb-2"></i>
                                    <p class="text-muted small mb-0 px-3">Analytics modules are being initialized. Check back soon for detailed scan reports and popular item tracking.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- QR Sidebar -->
            <div class="col-lg-4">
                @livewire('menu-qr-generator')
            </div>
        </div>
    </div>

    <style>
        .bg-light-primary { background-color: rgba(var(--bs-primary-rgb), 0.1); }
        .bg-light-info { background-color: rgba(var(--bs-info-rgb), 0.1); }
        .bg-light-success { background-color: rgba(var(--bs-success-rgb), 0.1); }
        .list-group-item-action:hover { background-color: transparent; color: var(--bs-primary); }
    </style>
@endsection