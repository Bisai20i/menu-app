@extends('layouts.admin-layout')

@push('title')
    Reports
@endpush

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
        <h5 class="mb-3 mb-md-0 fw-bold"><i class="bx bx-bar-chart-alt-2 me-2 text-primary"></i>Restaurant Performance Reports</h5>
        <div class="d-flex gap-2">
            <div class="input-group input-group-merge" style="min-width: 300px;">
                <span class="input-group-text"><i class="bx bx-search"></i></span>
                <input type="text" id="reportSearch" class="form-control" placeholder="Search restaurant or admin..." value="{{ request('search') }}">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive text-nowrap" id="reportsTableContainer">
            @include('admin.reports.partials.table', ['restaurants' => $restaurants])
        </div>
    </div>
</div>

<!-- Admin Details Modal -->
<x-modal id="adminDetailsModal" title="Admin Details">
    <div id="adminModalBody">
         <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    <x-slot name="footer">
        <button type="button" class="btn btn-label-secondary w-100" data-bs-dismiss="modal">Close Details</button>
    </x-slot>
</x-modal>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let searchTimer;
        
        $('#reportSearch').on('keyup', function() {
            clearTimeout(searchTimer);
            let search = $(this).val();
            
            searchTimer = setTimeout(function() {
                fetchReports(1, search);
            }, 500);
        });

        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            let url = $(this).attr('href');
            let pageParams = new URLSearchParams(url.split('?')[1]);
            let page = pageParams.get('page') || 1;
            let search = $('#reportSearch').val();
            fetchReports(page, search);
        });

        function fetchReports(page, search) {
            $('#reportsTableContainer').css('opacity', '0.5');
            $.ajax({
                url: "{{ route('master.reports.index') }}",
                data: { page: page, search: search },
                success: function(data) {
                    $('#reportsTableContainer').html(data).css('opacity', '1');
                },
                error: function() {
                    $('#reportsTableContainer').css('opacity', '1');
                }
            });
        }

        $(document).on('click', '.view-admin-details', function() {
            let admin = $(this).data('admin');
            let restaurant = $(this).data('restaurant');
            
            if (typeof admin === 'string') admin = JSON.parse(admin);
            if (typeof restaurant === 'string') restaurant = JSON.parse(restaurant);

            let html = `
                <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                    <div class="avatar avatar-xl me-3">
                        <img src="${admin.image ? '/storage/' + admin.image : '{{ asset('logo.png') }}'}" class="rounded-circle shadow-sm" style="width: 65px; height: 65px; object-fit: cover; border: 3px solid #f8f9fa;">
                    </div>
                    <div>
                        <h5 class="mb-1 fw-bold text-dark text-truncate">${admin.name}</h5>
                        <p class="text-muted mb-0 small"><i class="bx bx-envelope me-1"></i>${admin.email}</p>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="d-flex flex-column h-100 p-2 bg-light rounded-3">
                            <label class="text-muted small text-uppercase fw-bold mb-1" style="font-size: 0.65rem;">Restaurant</label>
                            <p class="fw-semibold mb-0 text-dark small">${restaurant.name}</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column h-100 p-2 bg-light rounded-3">
                            <label class="text-muted small text-uppercase fw-bold mb-1" style="font-size: 0.65rem;">Joined Date</label>
                            <p class="fw-semibold mb-0 text-dark small">${new Date(admin.created_at).toLocaleDateString(undefined, {year: 'numeric', month: 'short', day: 'numeric'})}</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column h-100 p-2 bg-light rounded-3">
                            <label class="text-muted small text-uppercase fw-bold mb-1" style="font-size: 0.65rem;">Account Status</label>
                            <p class="mb-0">
                                <span class="badge bg-label-${admin.is_active ? 'success' : 'danger'} text-capitalize">
                                    ${admin.is_active ? 'Active' : 'Deactivated'}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column h-100 p-2 bg-light rounded-3">
                            <label class="text-muted small text-uppercase fw-bold mb-1" style="font-size: 0.65rem;">System Role</label>
                            <p class="fw-semibold mb-0 text-capitalize text-dark small">${admin.role}</p>
                        </div>
                    </div>
                </div>
            `;
            $('#adminModalBody').html(html);
            $('#adminDetailsModal').modal('show');
        });
    });
</script>
@endpush
