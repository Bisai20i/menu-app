@extends('layouts.admin-layout')

@push('title', 'Edit Admin & Restaurant')

@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('master.admin-management.index') }}">Admin Management</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
        <h2 class="mb-0">Edit Admin & Restaurant</h2>
    </div>

    <div class="py-2">
        <livewire:admin-restaurant-form :id="$id" />
    </div>
@endsection
