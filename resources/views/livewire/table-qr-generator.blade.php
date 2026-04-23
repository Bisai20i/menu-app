<div class="container-fluid p-0">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1">Table QR Codes</h4>
            <p class="text-muted small mb-0">Generate and download QR codes for your restaurant tables</p>
        </div>
        <div class="col-md-3">
            <div class="input-group border rounded bg-white shadow-sm">
                <span class="input-group-text border-0 bg-transparent text-muted"><i class="bx bx-search"></i></span>
                <input wire:model.live="search" type="text" class="form-control border-0 shadow-none ps-0" placeholder="Filter tables...">
            </div>
        </div>
    </div>

    <div class="row g-4">
        @forelse($tables as $table)
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 text-center transition hover-lift">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <span class="badge {{ $table->status === 'available' ? 'bg-success' : ($table->status === 'occupied' ? 'bg-danger' : 'bg-warning') }} mb-2">
                                {{ strtoupper($table->status) }}
                            </span>
                            <h5 class="fw-bold mb-1">{{ $table->table_number }}</h5>
                            <p class="text-muted small">{{ $table->section ?? 'No Section' }} • {{ $table->capacity }} Seats</p>
                        </div>
                        
                        <!-- QR Preview -->
                        <div class="qr-container bg-light rounded-3 p-3 mb-4 d-inline-block shadow-inner">
                            {!! QrCode::size(150)->margin(1)->generate(url('/app/' . $restaurant_slug . '/' . $table->uuid)) !!}
                        </div>

                        <div class="d-grid gap-2">
                            <button wire:click="downloadQR('{{ $table->uuid }}', '{{ $table->table_number }}')" class="btn btn-primary d-flex align-items-center justify-content-center">
                                <i class="bx bx-download me-2"></i> Download SVG
                            </button>
                            <a href="{{ url('/app/' . $restaurant_slug . '/' . $table->uuid) }}" target="_blank" class="btn btn-link btn-sm text-muted text-decoration-none">
                                <i class="bx bx-link-external me-1"></i> Preview Link
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 py-5 text-center">
                <div class="display-1 text-muted opacity-25 mb-3"><i class="bx bx-qr"></i></div>
                <h5 class="text-muted">No tables found to generate QR codes.</h5>
            </div>
        @endforelse
    </div>

    <style>
        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
        .qr-container svg {
            max-width: 100%;
            height: auto;
        }
        .shadow-inner {
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
        }
    </style>
</div>
