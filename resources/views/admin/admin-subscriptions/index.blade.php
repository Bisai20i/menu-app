@extends('layouts.admin-layout')

@push('title', 'Admin Subscriptions')

@section('content')
<div>
    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mb-1">
                    <li class="breadcrumb-item text-muted">Settings</li>
                    <li class="breadcrumb-item active">Subscriptions</li>
                </ol>
            </nav>
            <h3 class="fw-bold mb-0">Admin Subscriptions</h3>
            <p class="text-muted mb-0">Manage and assign subscription plans to admins.</p>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light border-bottom">
                        <tr>
                            <th class="px-4 py-3 fw-semibold text-muted small text-uppercase">Admin</th>
                            <th class="py-3 fw-semibold text-muted small text-uppercase">Current Plan</th>
                            <th class="py-3 fw-semibold text-muted small text-uppercase">Expiry</th>
                            <th class="py-3 fw-semibold text-muted small text-uppercase text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($admins as $admin)
                            <tr>
                                {{-- Admin Info --}}
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $admin->image_url }}"
                                             class="rounded-circle object-fit-cover"
                                             style="width:40px; height:40px;"
                                             alt="{{ $admin->name }}">
                                        <div>
                                            <div class="fw-semibold text-dark">{{ $admin->name }}</div>
                                            <div class="text-muted small">{{ $admin->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Current Plan --}}
                                <td class="py-3">
                                    @if($admin->current_plan->id === 0)
                                        <span class="badge rounded-pill bg-secondary-subtle text-secondary px-3 py-2">
                                            <i class="bx bx-user me-1"></i>{{ $admin->current_plan->name }}
                                        </span>
                                    @else
                                        <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2">
                                            <i class="bx bx-crown me-1"></i>{{ $admin->current_plan->name }}
                                        </span>
                                    @endif
                                </td>

                                {{-- Expiry --}}
                                <td class="py-3">
                                    @if($admin->current_plan->id === 0)
                                        <span class="text-muted small">—</span>
                                    @elseif($admin->activeSubscription)
                                        <div class="fw-semibold small">
                                            {{ $admin->activeSubscription->expires_at ? $admin->activeSubscription->expires_at->format('d M Y') : 'Never' }}
                                        </div>
                                        <div class="text-muted" style="font-size:0.75rem;">
                                            {{ $admin->current_plan->duration_value }} {{ Str::plural($admin->current_plan->duration_unit, $admin->current_plan->duration_value) }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="py-3 text-center">
                                    <button type="button"
                                            class="btn btn-sm btn-primary px-3"
                                            data-bs-toggle="modal"
                                            data-bs-target="#assignPlanModal-{{ $admin->id }}">
                                        <i class="bx bx-plus me-1"></i>Assign Plan
                                    </button>

                                    @if($admin->current_plan->id !== 0)
                                        <form action="{{ route('master.admin-subscriptions.remove', $admin->id) }}"
                                              method="POST"
                                              class="d-inline ms-1"
                                              id="remove-form-{{ $admin->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger px-3"
                                                    onclick="document.getElementById('remove-form-{{ $admin->id }}').submit()">
                                                <i class="bx bx-x me-1"></i>Remove
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>

                            {{-- Assign Plan Modal --}}
                            <div class="modal fade" id="assignPlanModal-{{ $admin->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">
                                        <form action="{{ route('master.admin-subscriptions.assign') }}" method="POST">
                                            @csrf
                                            <div class="modal-header border-bottom-0 pb-0">
                                                <div>
                                                    <h5 class="modal-title fw-bold">Assign Subscription Plan</h5>
                                                    <p class="text-muted small mb-0">Assigning plan to <strong>{{ $admin->name }}</strong></p>
                                                </div>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body pt-3">
                                                <input type="hidden" name="admin_id" value="{{ $admin->id }}">

                                                @if($plans->isEmpty())
                                                    <div class="alert alert-warning border-0 bg-warning-subtle text-warning-emphasis small">
                                                        <i class="bx bx-info-circle me-1"></i>
                                                        No published subscription plans found. Please create a plan first.
                                                    </div>
                                                @else
                                                    <div class="mb-3">
                                                        <label for="plan-{{ $admin->id }}" class="form-label fw-semibold">Select Plan</label>
                                                        @foreach($plans as $plan)
                                                            <label class="d-flex align-items-start gap-3 border rounded-3 p-3 mb-2 cursor-pointer plan-option"
                                                                   for="plan-radio-{{ $admin->id }}-{{ $plan->id }}"
                                                                   style="cursor:pointer;">
                                                                <input type="radio"
                                                                       class="form-check-input mt-1 flex-shrink-0"
                                                                       id="plan-radio-{{ $admin->id }}-{{ $plan->id }}"
                                                                       name="subscription_plan_id"
                                                                       value="{{ $plan->id }}"
                                                                       @if($loop->first) checked @endif>
                                                                <div>
                                                                    <div class="fw-semibold">{{ $plan->name }}</div>
                                                                    <div class="text-muted small">
                                                                        {{ $plan->duration_value }} {{ Str::plural($plan->duration_unit, $plan->duration_value) }}
                                                                        &nbsp;&bull;&nbsp;
                                                                        {{ $plan->currency }} {{ number_format($plan->price, 2) }}
                                                                    </div>
                                                                </div>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer border-top-0 pt-0">
                                                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary px-4" @if($plans->isEmpty()) disabled @endif>
                                                    <i class="bx bx-check me-1"></i>Assign Plan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="bx bx-user-x" style="font-size: 2.5rem;"></i>
                                    <p class="mt-2 mb-0">No admins found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
