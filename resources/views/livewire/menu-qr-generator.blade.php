<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-4 text-center">
        <h5 class="fw-bold mb-3">Your Digital Menu QR</h5>
        <p class="text-muted small mb-4">Display this QR code at your restaurant for customers to scan and view your digital menu instantly.</p>
        
        @if($restaurant)
            <div class="d-inline-block p-3 bg-white border rounded-3 mb-4 shadow-sm">
                {!! QrCode::size(200)->generate($menuUrl) !!}
            </div>
            
            <div class="mb-4">
                <code class="d-block p-2 bg-light rounded text-primary small mb-1">{{ $menuUrl }}</code>
                <small class="text-muted">Public Menu URL</small>
            </div>

            <div class="d-grid gap-2">
                <button wire:click="downloadQR" class="btn btn-primary d-flex align-items-center justify-content-center py-2">
                    <i class="bx bx-download me-2"></i> Download High Quality QR
                </button>
                <a href="{{ $menuUrl }}" target="_blank" class="btn btn-outline-secondary d-flex align-items-center justify-content-center py-2">
                    <i class="bx bx-link-external me-2"></i> Preview Digital Menu
                </a>
            </div>
        @else
            <div class="alert alert-warning mb-0">
                <i class="bx bx-error-circle me-2"></i> No restaurant associated with your account. Please set up your establishment in settings.
            </div>
        @endif
    </div>
</div>
