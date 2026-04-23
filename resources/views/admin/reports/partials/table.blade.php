<table class="table table-hover align-middle">
    <thead class="table-light">
        <tr>
            <th style="width: 25%">Restaurant</th>
            <th style="width: 20%">Owner (Admin)</th>
            <th class="text-center">Avg Orders/Day</th>
            <th class="text-center">Avg Revenue/Day</th>
            <th class="text-center">Avg Views/Day</th>
            <th class="text-end">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($restaurants as $restaurant)
        <tr>
            <td>
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-2">
                        <img src="{{ $restaurant->logo_path ? asset('storage/' . $restaurant->logo_path) : asset('logo.png') }}" class="rounded shadow-xs" style="width: 35px; height: 35px; object-fit: cover;">
                    </div>
                    <span class="fw-bold text-dark">{{ $restaurant->name }}</span>
                </div>
            </td>
            <td>
                @if($restaurant->admin)
                    <div class="d-flex flex-column">
                        <a href="javascript:void(0);" 
                           class="view-admin-details fw-semibold text-primary" 
                           data-admin="{{ json_encode($restaurant->admin->only(['id', 'name', 'email', 'image', 'is_active', 'role', 'created_at'])) }}"
                           data-restaurant="{{ json_encode($restaurant->only(['id', 'name'])) }}">
                            {{ $restaurant->admin->name }}
                        </a>
                        <small class="text-muted">{{ $restaurant->admin->email }}</small>
                    </div>
                @else
                    <span class="text-muted small italic">No owner assigned</span>
                @endif
            </td>
            <td class="text-center">
                <span class="badge bg-label-primary px-3">{{ number_format($restaurant->avg_orders, 1) }}</span>
            </td>
            <td class="text-center">
                <span class="fw-bold text-success">Rs. {{ number_format($restaurant->avg_revenue, 2) }}</span>
            </td>
            <td class="text-center">
                <span class="fw-bold text-info">{{ number_format($restaurant->avg_views, 1) }}</span>
            </td>
            <td class="text-end">
                <a href="{{ route('master.reports.show', $restaurant->id) }}" class="btn btn-icon btn-outline-primary border-0 btn-sm shadow-xs" title="View Full Report">
                    <i class="bx bx-right-arrow-alt fs-4"></i>
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center py-5">
                <div class="mb-3">
                    <i class="bx bx-search text-muted" style="font-size: 3rem;"></i>
                </div>
                <h6 class="text-muted">No restaurant records found matching your criteria.</h6>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="px-4 py-3 d-flex justify-content-between align-items-center border-top">
    <div class="small text-muted">
        Showing {{ $restaurants->firstItem() ?? 0 }} to {{ $restaurants->lastItem() ?? 0 }} of {{ $restaurants->total() }} entries
    </div>
    <div class="pagination-wrapper">
        {{ $restaurants->appends(request()->all())->links() }}
    </div>
</div>
