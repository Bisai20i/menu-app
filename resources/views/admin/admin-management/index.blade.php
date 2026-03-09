@extends('layouts.admin-layout')

@push('title', 'Admin Management')

@section('content')
    <div>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-3">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mb-1">
                        <li class="breadcrumb-item text-muted">Management</li>
                        <li class="breadcrumb-item active">Admins</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0">Admin Management</h3>
                <p class="text-muted mb-0">System administrators and restaurant configurations.</p>
            </div>
            <div>
                <a href="{{ route('master.admin-management.create') }}" class="btn btn-primary shadow-sm px-4">
                    <i class="bx bx-plus-circle me-2"></i> Create New Admin
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-body p-0">
                <livewire:dynamic-data-table 
                    model="admin" 
                    route-prefix="master.admin-management" 
                    lazy 
                />
            </div>
        </div>
    </div>
@endsection
