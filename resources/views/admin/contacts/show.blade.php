@extends('layouts.admin-layout')

@push('title', 'View Contact Message')

@section('content')
    <div>
        <div class="mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mb-1">
                    <li class="breadcrumb-item text-muted">Communications</li>
                    <li class="breadcrumb-item"><a href="{{ route('master.contacts.index') }}">Contact Messages</a></li>
                    <li class="breadcrumb-item active">View Message</li>
                </ol>
            </nav>
            <h3 class="fw-bold mb-0">View Message</h3>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Message Content</h5>
                        <span class="badge bg-label-info">Received: {{ $contact->created_at->format('M d, Y H:i') }}</span>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h6 class="text-uppercase text-muted small fw-bold mb-2">From</h6>
                            <p class="mb-0 fw-bold">{{ $contact->name }}</p>
                            <p class="text-muted mb-0">{{ $contact->email }}</p>
                        </div>
                        <hr class="my-4">
                        <div>
                            <h6 class="text-uppercase text-muted small fw-bold mb-2">Message</h6>
                            <div class="p-3 bg-light rounded text-body" style="white-space: pre-wrap;">{{ $contact->message }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="mailto:{{ $contact->email }}" class="btn btn-primary">
                                <i class="bx bx-envelope me-2"></i> Reply via Email
                            </a>
                            <form action="{{ route('master.contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this message?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i class="bx bx-trash me-2"></i> Delete Message
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
