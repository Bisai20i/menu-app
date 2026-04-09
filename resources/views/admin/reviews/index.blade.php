@extends('layouts.admin-layout')

@push('title', 'Reviews')

@section('content')
<div>
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Reviews</h4>
            <nav aria-label="breadcrumb text-muted small">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('master.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Reviews</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @livewire('dynamic-data-table', [
            'model' => 'Review',
            'routePrefix' => 'master.reviews'
            ])
        </div>
    </div>
</div>
@endsection