@extends('layouts.admin-layout')

@push('title', 'Billing & Subscription')

@section('content')
    <div>
        {{-- Page Header --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mb-1">
                        <li class="breadcrumb-item text-muted">Account</li>
                        <li class="breadcrumb-item active">Billing</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0">Billing & Subscription</h3>
                <p class="text-muted mb-0">View your current plan and subscription history.</p>
            </div>
        </div>

        {{-- ===================== SUPER ADMIN NOTICE ===================== --}}
        @if ($admin->is_super_admin)
            <div class="card border-0 shadow-sm bg-primary text-white mb-4 overflow-hidden">
                <div class="card-body p-4 p-xl-5 position-relative">
                    <div class="row align-items-center">
                        <div class="col-md-9">
                            <h3 class="fw-bold text-white mb-2">You're a Super Admin 🛡️</h3>
                            <p class="mb-0 opacity-75">You do not need any subscription plan to interact with the system.
                                All platform features are unlocked for you by default.</p>
                        </div>
                    </div>
                    <i class="bx bx-shield-quarter"
                        style="font-size: 120px; opacity: 0.15; position: absolute; right: -10px; bottom: -20px;"></i>
                </div>
            </div>


            {{-- ===================== CURRENT PLAN CARD ===================== --}}
        @else
            @php $sub = $admin->activeSubscription; @endphp
            <div class="card border-0 shadow-sm bg-primary text-white mb-4 overflow-hidden">
                <div class="card-body p-4 p-xl-5 position-relative">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <p class="text-white opacity-75 small text-uppercase fw-semibold mb-1">Your Current Plan</p>
                            <h3 class="fw-bold text-white mb-2">{{ $currentPlan ? $currentPlan->name : 'Free Plan' }}</h3>

                            @if ($currentPlan && $currentPlan->id !== 0)
                                <div class="d-flex flex-wrap gap-4 mb-4">
                                    <div>
                                        <p class="opacity-75 mb-0" style="font-size:0.72rem;">STARTED</p>
                                        <div class="fw-semibold">{{ $sub?->starts_at->format('d M Y') ?? '—' }}</div>
                                    </div>
                                    <div>
                                        <p class="opacity-75 mb-0" style="font-size:0.72rem;">EXPIRES</p>
                                        <div class="fw-semibold">{{ $sub?->expires_at?->format('d M Y') ?? 'Never' }}</div>
                                    </div>
                                    <div>
                                        <p class="opacity-75 mb-0" style="font-size:0.72rem;">DURATION</p>
                                        <div class="fw-semibold">{{ $currentPlan->duration_value }}
                                            {{ Str::plural($currentPlan->duration_unit, $currentPlan->duration_value) }}
                                        </div>
                                    </div>
                                    <div>
                                        <p class="opacity-75 mb-0" style="font-size:0.72rem;">PRICE</p>
                                        <div class="fw-semibold">{{ $currentPlan->currency }}
                                            {{ number_format($currentPlan->price, 2) }}</div>
                                    </div>
                                </div>

                                @if ($sub?->expires_at)
                                    @php
                                        $daysLeft = now()->diffInDays($sub->expires_at, false);
                                        $total = $sub->starts_at->diffInDays($sub->expires_at);
                                        $elapsed = $sub->starts_at->diffInDays(now());
                                        $pct = $total > 0 ? min(100, round(($elapsed / $total) * 100)) : 100;
                                    @endphp
                                    <div class="progress rounded-pill mb-2"
                                        style="height:6px; background:rgba(255,255,255,0.25);">
                                        <div class="progress-bar bg-white" style="width: {{ $pct }}%"></div>
                                    </div>
                                    <p class="opacity-75 small mb-0">
                                        {{ $daysLeft > 0 ? $daysLeft . ' days remaining' : 'Expired' }}
                                    </p>
                                @endif
                            @else
                                <p class="opacity-75 mb-4">You are on the Free Plan. Upgrade to unlock more features.</p>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach (['Generate Menu QR Code', 'Add Menu Images or PDF', 'Scan to View Menu'] as $f)
                                        <span class="badge rounded-pill fw-normal px-3 py-2"
                                            style="background:rgba(255,255,255,0.2);">
                                            <i class="bx bx-check me-1"></i>{{ $f }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- Features side panel (premium only) --}}
                        @if ($currentPlan && $currentPlan->id !== 0 && is_array($currentPlan->features) && count($currentPlan->features))
                            <div class="col-md-4 mt-4 mt-md-0">
                                <div class="rounded-3 p-3" style="background:rgba(255,255,255,0.12);">
                                    <p class="text-white opacity-75 small text-uppercase fw-semibold mb-2">Plan Features</p>
                                    <ul class="list-unstyled mb-0">
                                        @foreach ($currentPlan->features as $key => $value)
                                            <li class="d-flex align-items-start gap-2 mb-2 small text-white">
                                                <i class="bx bx-check-circle opacity-75 mt-1"></i>
                                                <span><strong>{{ Str::title(str_replace('_', ' ', $key)) }}</strong>{{ $value ? ': ' . $value : '' }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                    <i class="bx bx-receipt"
                        style="font-size: 120px; opacity: 0.1; position: absolute; right: -10px; bottom: -20px;"></i>
                </div>
            </div>
        @endif

        {{-- ===================== AVAILABLE PLANS ===================== --}}
        <div class="mb-4">
            <h5 class="fw-bold mb-3">Available Plans</h5>
            <div class="row g-4">

                {{-- Dynamic Plans from DB --}}
                @forelse($plans as $plan)
                    @php
                        $isCurrentPlan = !$admin->is_super_admin && $currentPlan && $currentPlan->id === $plan->id;
                    @endphp
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100 @if ($isCurrentPlan) border border-success @endif"
                            style="@if ($loop->iteration == 2 && !$isCurrentPlan) background: linear-gradient(135deg, #fff8f8 0%, #ffeeee 100%); @endif">
                            @if ($isCurrentPlan)
                                <div class="card-header bg-success text-white py-2 text-center small fw-semibold" >
                                    ✓ Current Plan
                                </div>
                            @elseif($loop->iteration == 2)
                                <div class="card-header bg-primary text-white py-2 text-center small fw-semibold">
                                    ⭐ Most Popular
                                </div>
                            @endif
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="fw-bold mb-0">{{ $plan->name }}</h5>
                                        <p class="text-muted small mb-0">
                                            {{ $plan->price > 0 ? 'Premium Access' : 'Always Free, forever' }}</p>
                                    </div>
                                    @if ($plan->price > 0)
                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                            style="width:40px;height:40px;background:#f0fff4;">
                                            <i class="bx bx-crown text-success"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="display-6 fw-bold mb-3">
                                    {{ $plan->currency }} {{ number_format($plan->price, 0) }}
                                    <span class="fs-6 text-muted fw-normal">/
                                        {{ $plan->price > 0 ? $plan->duration_value  . ' ' . Str::plural($plan->duration_unit, $plan->duration_value) : 'forever' }}</span>
                                </div>
                                <hr class="my-3">
                                @if (is_array($plan->features) && count($plan->features))
                                    <ul class="list-unstyled mb-0">
                                        @foreach ($plan->features as $feature)
                                            <li class="d-flex align-items-center gap-2 mb-2 small">
                                                <i class="bx bx-check text-success fs-5"></i>
                                                <span>{{ $feature }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted small mb-0">Contact admin for features details.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert border-0 bg-light text-muted text-center small mb-0">
                            No premium plans published yet.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ===================== BILLING HISTORY ===================== --}}
        @unless ($admin->is_super_admin)
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-body p-0">
                    <div class="px-4 py-3 border-bottom d-flex align-items-center justify-content-between">
                        <h6 class="fw-bold mb-0">Billing History</h6>
                        <span class="badge bg-secondary-subtle text-secondary rounded-pill">{{ $billingHistory->count() }}
                            records</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4 py-3 fw-semibold text-muted small text-uppercase">Plan</th>
                                    <th class="py-3 fw-semibold text-muted small text-uppercase">Started</th>
                                    <th class="py-3 fw-semibold text-muted small text-uppercase">Expired / Ends</th>
                                    <th class="py-3 fw-semibold text-muted small text-uppercase">Price</th>
                                    <th class="py-3 fw-semibold text-muted small text-uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($billingHistory as $record)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="fw-semibold">{{ $record->plan?->name ?? '—' }}</div>
                                            <div class="text-muted small">
                                                {{ $record->plan?->duration_value }}
                                                {{ $record->plan ? Str::plural($record->plan->duration_unit, $record->plan->duration_value) : '' }}
                                            </div>
                                        </td>
                                        <td class="py-3 small">{{ $record->starts_at->format('d M Y') }}</td>
                                        <td class="py-3 small">{{ $record->expires_at?->format('d M Y') ?? 'Never' }}</td>
                                        <td class="py-3 small fw-semibold">
                                            @if ($record->plan)
                                                {{ $record->plan->currency }} {{ number_format($record->plan->price, 2) }}
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td class="py-3">
                                            @if ($record->status === 'active')
                                                <span
                                                    class="badge bg-success-subtle text-success rounded-pill px-3">Active</span>
                                            @elseif($record->status === 'expired')
                                                <span
                                                    class="badge bg-secondary-subtle text-secondary rounded-pill px-3">Expired</span>
                                            @else
                                                <span
                                                    class="badge bg-warning-subtle text-warning rounded-pill px-3">{{ Str::title($record->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="bx bx-receipt" style="font-size:2.5rem;"></i>
                                            <p class="mt-2 mb-0 small">No billing history found.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endunless
    </div>
@endsection
