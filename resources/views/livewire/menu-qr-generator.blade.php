<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-4 text-center">
        <h5 class="fw-bold mb-3">Your Digital Menu QR</h5>
        <p class="text-muted small mb-4">Display this QR code at your restaurant for customers to scan and view your
            digital menu instantly.</p>

        @if($restaurant)
            <div id="main-qr-source" class="d-inline-block p-3 bg-white border rounded-3 mb-4 shadow-sm">
                {!! $menuQrSvg !!}
            </div>

            <div class="mb-4">
                <code class="d-block p-2 bg-light rounded text-primary small mb-1">{{ $menuUrl }}</code>
                <small class="text-muted">Public Menu URL</small>
            </div>

            <div class="d-grid gap-2 mb-4">
                <button wire:click="downloadQR" wire:loading.attr="disabled" wire:target="downloadQR"
                    class="btn btn-primary d-flex align-items-center justify-content-center py-2">
                    <span wire:loading.class="d-none" class="d-inline-flex align-items-center">
                        <i class="bx bx-download me-2"></i> Download SVG
                    </span>
                    <span wire:loading class="align-items-center">
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Generating QR...
                    </span>
                </button>

                <button type="button" class="btn btn-dark d-flex align-items-center justify-content-center py-2"
                    onclick="openQrPrintModal({
                        sourceId  : 'main-qr-source',
                        title     : '{{ $restaurant->name }}',
                        wifiQrUrl : '{{ $restaurant->restaurant_wifi_qr ?? '' }}'
                    })">
                    <i class="bx bx-printer me-2"></i> Print Format (Poster)
                </button>

                <a href="{{ $menuUrl }}" target="_blank" wire:loading.class="disabled opacity-50 pe-none"
                    wire:loading.attr="aria-disabled" wire:target="downloadQR"
                    class="btn btn-outline-secondary d-flex align-items-center justify-content-center py-2">
                    <i class="bx bx-link-external me-2"></i> Preview Digital Menu
                </a>
            </div>

            @if($restaurant->restaurant_wifi_qr)
                <hr class="my-4 opacity-50">

                <div class="text-start p-3 rounded-3 border bg-light">
                    <div class="row g-3 align-items-center">
                        <!-- Restaurant Branding (2/3) -->
                        <div class="col-8">
                            <div class="d-flex align-items-start gap-3">
                                @if($restaurant->logo_path)
                                    <img src="{{ asset('storage/' . $restaurant->logo_path) }}" alt="logo"
                                        class="rounded border p-1 bg-white shadow-sm" height="60" width="60"
                                        style="object-fit: contain;">
                                @endif
                                <div>
                                    <h6 class="fw-bold mb-1 text-dark text-uppercase letter-spacing-1">{{ $restaurant->name }}
                                    </h6>
                                    @if($restaurant->description)
                                        <p class="text-muted mb-2" style="font-size: 0.75rem; line-height: 1.3;">
                                            {{ \Illuminate\Support\Str::limit($restaurant->description, 100) }}</p>
                                    @endif

                                    <div class="d-flex flex-wrap gap-3">
                                        @if($restaurant->address)
                                            <span class="text-muted d-flex align-items-center" style="font-size: 0.7rem;">
                                                <i class="bx bx-map me-1 text-primary"></i> {{ $restaurant->address }}
                                            </span>
                                        @endif
                                        @if($restaurant->phone)
                                            <span class="text-muted d-flex align-items-center" style="font-size: 0.7rem;">
                                                <i class="bx bx-phone me-1 text-primary"></i> {{ $restaurant->phone }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Wifi QR (1/3) -->
                        <div class="col-4 text-end">
                            <div class="d-inline-block text-center mt-auto">
                                <img src="{{ $restaurant->restaurant_wifi_qr }}" alt="Wifi QR"
                                    class="rounded border p-1 bg-white shadow-sm" height="100" width="100"
                                    style="object-fit: contain;">
                                <div class="mt-1">
                                    <span class="badge bg-label-info p-1 px-2 rounded-pill text-uppercase fw-bold"
                                        style="font-size: 0.6rem;">Connect to WIFI</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        @else
            <div class="alert alert-warning mb-0">
                <i class="bx bx-error-circle me-2"></i> No restaurant associated with your account. Please set up your
                establishment in settings.
            </div>
        @endif
    </div>
</div>
