@extends('layouts.admin-layout')

@push('title', 'Menu - ' . $model . ' Management')


@section('content')

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mb-1">
                    <li class="breadcrumb-item text-muted">Management</li>
                    <li class="breadcrumb-item active">{{ \Illuminate\Support\Str::plural($model) }}</li>
                </ol>
            </nav>
            <h3 class="fw-bold mb-0">{{ \Illuminate\Support\Str::plural($model) }} Management</h3>
            <p class="text-muted mb-0">Manage your {{ strtolower(\Illuminate\Support\Str::plural($model)) }} effectively.</p>
        </div>
        <a href="{{ route($routePrefix.'.create') }}" class="btn btn-primary shadow-sm"> 
            <i class="bx bx-plus-circle me-1"></i> Create New {{ $model }} 
        </a> 
    </div>

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-body p-0">
            <livewire:dynamic-data-table model="{{ \Illuminate\Support\Str::kebab($model) }}" route-prefix="{{ $routePrefix }}" lazy />
        </div>
    </div>

@endsection