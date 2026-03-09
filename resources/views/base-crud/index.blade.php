@extends('layouts.admin-layout')

@push('title', 'Menu - ' . $model . ' Management')


@section('content')

    <div class="d-flex justify-content-between align-items-center"> <!-- Left side: Heading --> 
        <h2 class="mb-0">{{ $model }} Management</h2> <!-- Right side: Create Article link --> 
        <a href="{{ route($routePrefix.'.create') }}" class="btn btn-primary"> 
            <i class="bi bi-plus-circle"></i> Create {{ $model }} 
        </a> 
    </div>

    <div class="py-4">
        <livewire:dynamic-data-table model="{{ \Illuminate\Support\Str::kebab($model) }}" route-prefix="{{ $routePrefix }}" lazy />
    </div>

@endsection