@extends('layouts.admin-layout')

@push('title', 'Profile & Business Settings')

@section('content')
<div>
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="fw-bold mb-1">Account & Restaurant Settings</h4>
                    <nav aria-label="breadcrumb text-muted small">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('master.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Profile Settings</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('master.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
            <!-- Sidebar: User & Restaurant Quick View -->
            <div class="col-xl-4 col-lg-5">
                <div class="row g-4 sticky-top" style="top: 100px; z-index: 10;">
                    <!-- User Glance -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm overflow-hidden">
                            <div class="card-body text-center pt-5 pb-4">
                                <div class="position-relative d-inline-block mb-4">
                                    <img src="{{ $admin->image_url }}" alt="user-avatar" class="rounded-circle border border-5 border-white shadow-sm" height="120" width="120" id="profilePreview" style="object-fit: cover;" />
                                    <label for="imageUpload" class="btn btn-sm btn-primary rounded-circle position-absolute bottom-0 end-0 p-2 shadow" style="cursor: pointer;">
                                        <i class="bx bx-camera shadow-none"></i>
                                        <input type="file" id="imageUpload" name="image" hidden accept="image/*" />
                                    </label>
                                </div>
                                <h5 class="fw-bold mb-1">{{ $admin->name }}</h5>
                                <p class="text-muted small mb-3">{{ $admin->email }}</p>
                                <span class="badge bg-light text-primary border px-3 py-2 rounded-pill fw-semibold small">Super Admin</span>
                            </div>
                        </div>
                    </div>

                    <!-- Restaurant Logo Glance -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center py-5">
                                <div>
                                    <span class="badge bg-label-secondary mb-4 p-2 px-3 rounded-pill text-uppercase fw-bold" style="font-size: 0.65rem;">Official Branding</span>
                                </div>
                                <div class="position-relative d-inline-block mb-4">
                                    @if($admin->restaurant && $admin->restaurant->logo_path)
                                    <img src="{{ asset('storage/' . $admin->restaurant->logo_path) }}" alt="restaurant-logo" class="rounded border p-1 bg-white shadow-sm" height="140" width="140" id="logoPreview" style="object-fit: contain;" />
                                    @else
                                    <div id="logoPreviewPlaceholder" class="rounded bg-light d-flex flex-direction-column align-items-center justify-content-center" style="height: 140px; width: 140px; border: 2px dashed #e0e0e0;">
                                        <i class="bx bx-shop text-muted display-6"></i>
                                        <span class="text-muted small mt-2">Logo</span>
                                    </div>
                                    <img src="" id="logoPreview" class="d-none rounded border p-1 bg-white shadow-sm" height="140" width="140" style="object-fit: contain;" />
                                    @endif
                                    <label for="logoUpload" class="btn btn-sm btn-primary rounded-circle position-absolute bottom-0 end-0 p-2 shadow" style="cursor: pointer;">
                                        <i class="bx bx-edit-alt shadow-none"></i>
                                        <input type="file" id="logoUpload" name="restaurant_logo" hidden accept="image/*" />
                                    </label>
                                </div>
                                <h5 class="fw-bold mb-0">{{ $admin->restaurant->name ?? '-- Not Set --' }}</h5>
                                <p class="text-muted small mb-0">{{ $admin->restaurant->slug ?? 'slug-pending' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content: Forms -->
            <div class="col-xl-8 col-lg-7">
                <div class="d-flex flex-column gap-4">
                    <!-- Section: Personal Info -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-transparent border-bottom py-3 d-flex align-items-center">
                            <h5 class="card-title fw-bold mb-0">Personal Account Settings</h5>
                        </div>
                        <div class="card-body py-4">
                            <div class="row g-3">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Full Display Name</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $admin->name) }}" required>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Primary Email Address</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $admin->email) }}" required>
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <hr class="my-4 opacity-50">

                            <div class="row g-3">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">New Password <small class="text-muted fw-normal ms-2">(Leave blank to keep current)</small></label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter new password">
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Restaurant Identification -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-transparent border-bottom py-3 d-flex align-items-center">
                            <h5 class="card-title fw-bold mb-0">Restaurant Branding & Identity</h5>
                        </div>
                        <div class="card-body py-4">
                            <div class="row g-3">
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-semibold">Restaurant Public Name</label>
                                    <input type="text" name="restaurant_name" class="form-control @error('restaurant_name') is-invalid @enderror" value="{{ old('restaurant_name', $admin->restaurant->name ?? '') }}" required>
                                    @error('restaurant_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                {{-- <div class="col-md-5 mb-3">
                                    <label class="form-label fw-semibold">Unique Browser Slug</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text bg-light border text-muted fw-normal" style="font-size: 0.8rem;">/menu/</span>
                                        <input type="text" name="restaurant_slug" class="form-control @error('restaurant_slug') is-invalid @enderror" value="{{ old('restaurant_slug', $admin->restaurant->slug ?? '') }}" required>
                            </div>
                            @error('restaurant_slug') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div> --}}
                        <div class="col-12 mb-3">
                            <label class="form-label fw-semibold">Business Description / Catchphrase</label>
                            <textarea name="restaurant_description" class="form-control @error('restaurant_description') is-invalid @enderror" rows="4" placeholder="Briefly describe your restaurant, atmosphere or mission statement...">{{ old('restaurant_description', $admin->restaurant->description ?? '') }}</textarea>
                            @error('restaurant_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <hr class="my-2 opacity-25">

                        <div class="col-12 d-flex align-items-center justify-content-between px-0">
                            <h6 class="fw-bold mb-0"><i class="bx bx-google me-2"></i>Google Review Integration</h6>
                            <button type="button" class="btn btn-xs btn-outline-primary rounded-pill px-2 py-1" data-bs-toggle="modal" data-bs-target="#googleHelpModal">
                                <i class="bx bx-help-circle me-1"></i> How to get these?
                            </button>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Google Place ID</label>
                            <input type="text" name="google_place_id" class="form-control @error('google_place_id') is-invalid @enderror" value="{{ old('google_place_id', $admin->restaurant->google_place_id ?? '') }}" placeholder="ChIJa1p3...');">
                            @error('google_place_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Google Review Link</label>
                            <input type="url" name="google_review_link" class="form-control @error('google_review_link') is-invalid @enderror" value="{{ old('google_review_link', $admin->restaurant->google_review_link ?? '') }}" placeholder="https://search.google.com/local/writereview?placeid=...">
                            @error('google_review_link') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section: Business Contact & Localization -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom py-3 d-flex align-items-center">
                    <h5 class="card-title fw-bold mb-0">Support & Transactional Defaults</h5>
                </div>
                <div class="card-body py-4">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Public Contact Email</label>
                            <input type="email" name="restaurant_email" class="form-control @error('restaurant_email') is-invalid @enderror" value="{{ old('restaurant_email', $admin->restaurant->email ?? '') }}" placeholder="Business@email.com">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Public Hotline</label>
                            <input type="text" name="restaurant_phone" class="form-control @error('restaurant_phone') is-invalid @enderror" value="{{ old('restaurant_phone', $admin->restaurant->phone ?? '') }}" placeholder="+977-XXX-XXXXXX">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-semibold">Official Business Address</label>
                            <input type="text" name="restaurant_address" class="form-control @error('restaurant_address') is-invalid @enderror" value="{{ old('restaurant_address', $admin->restaurant->address ?? '') }}" placeholder="Store location, street name, city...">
                        </div>
                    </div>

                    <hr class="my-4 opacity-50">

                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Currency Display</label>
                            <input type="text" name="currency" class="form-control @error('currency') is-invalid @enderror" value="{{ old('currency', $admin->restaurant->settings['currency'] ?? 'NPR') }}" required placeholder="e.g. NPR, USD, Rs.">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold d-block">Brand Color Theme</label>
                            <div class="d-flex align-items-center bg-light rounded px-3 py-1 border">
                                <input type="color" name="primary_color" id="brandColor" class="form-control form-control-color border-0 bg-transparent p-0" value="{{ old('primary_color', $admin->restaurant->settings['primary_color'] ?? '#000000') }}" style="width: 35px; height: 35px; min-width: 35px;">
                                <code id="colorHex" class="ms-3 fw-bold text-uppercase" style="font-size: 0.9rem;">{{ old('primary_color', $admin->restaurant->settings['primary_color'] ?? '#000000') }}</code>
                            </div>
                        </div>
                    </div>

                    @php
                    $paymentQr = $admin->restaurant->settings['payment_qr'] ?? null;
                    $paymentQrSrc = null;
                    if (is_string($paymentQr) && trim($paymentQr) !== '') {
                    $paymentQr = trim($paymentQr);
                    $paymentQrSrc = (\Illuminate\Support\Str::startsWith($paymentQr, ['data:', 'http://', 'https://']))
                    ? $paymentQr
                    : (\Illuminate\Support\Str::startsWith($paymentQr, '/')
                    ? $paymentQr
                    : asset('storage/' . $paymentQr));
                    }

                    $wifiQr = $admin->restaurant->settings['restaurant_wifi_qr'] ?? null;
                    $wifiQrSrc = null;
                    if (is_string($wifiQr) && trim($wifiQr) !== '') {
                    $wifiQr = trim($wifiQr);
                    $wifiQrSrc = (\Illuminate\Support\Str::startsWith($wifiQr, ['data:', 'http://', 'https://']))
                    ? $wifiQr
                    : (\Illuminate\Support\Str::startsWith($wifiQr, '/')
                    ? $wifiQr
                    : asset('storage/' . $wifiQr));
                    }
                    @endphp

                    <div class="row g-3 mt-1">
                        <!-- Payment QR -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Payment QR</label>
                            <input
                                type="file"
                                name="payment_qr_image"
                                id="paymentQrImageInput"
                                class="form-control @error('payment_qr_image') is-invalid @enderror"
                                accept="image/*">
                            @error('payment_qr_image') <div class="invalid-feedback">{{ $message }}</div> @enderror

                            <div class="mt-3">
                                @if(!empty($paymentQrSrc))
                                <img
                                    id="paymentQrPreview"
                                    src="{{ $paymentQrSrc }}"
                                    data-initial-src="{{ $paymentQrSrc }}"
                                    alt="payment-qr-preview"
                                    style="max-height: 150px; max-width: 100%; border-radius: 12px; border: 1px solid rgba(0,0,0,0.08); background: #fff;">
                                @else
                                <div id="paymentQrPreviewPlaceholder" class="rounded bg-light d-flex align-items-center justify-content-center text-muted" style="height: 150px; width: 150px; border: 1px dashed #e0e0e0;">
                                    <span>QR Preview</span>
                                </div>
                                <img
                                    id="paymentQrPreview"
                                    class="d-none"
                                    alt="payment-qr-preview"
                                    style="max-height: 150px; max-width: 100%; border-radius: 12px; border: 1px solid rgba(0,0,0,0.08); background: #fff;"
                                    data-initial-src="">
                                @endif
                            </div>
                        </div>

                        <!-- Wifi QR -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Restaurant Wifi QR</label>
                            <input
                                type="file"
                                name="restaurant_wifi_qr_image"
                                id="wifiQrImageInput"
                                class="form-control @error('restaurant_wifi_qr_image') is-invalid @enderror"
                                accept="image/*">
                            @error('restaurant_wifi_qr_image') <div class="invalid-feedback">{{ $message }}</div> @enderror

                            <div class="mt-3">
                                @if(!empty($wifiQrSrc))
                                <img
                                    id="wifiQrPreview"
                                    src="{{ $wifiQrSrc }}"
                                    data-initial-src="{{ $wifiQrSrc }}"
                                    alt="wifi-qr-preview"
                                    style="max-height: 150px; max-width: 100%; border-radius: 12px; border: 1px solid rgba(0,0,0,0.08); background: #fff;">
                                @else
                                <div id="wifiQrPreviewPlaceholder" class="rounded bg-light d-flex align-items-center justify-content-center text-muted" style="height: 150px; width: 150px; border: 1px dashed #e0e0e0;">
                                    <span>QR Preview</span>
                                </div>
                                <img
                                    id="wifiQrPreview"
                                    class="d-none"
                                    alt="wifi-qr-preview"
                                    style="max-height: 150px; max-width: 100%; border-radius: 12px; border: 1px solid rgba(0,0,0,0.08); background: #fff;"
                                    data-initial-src="">
                                @endif
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="text-muted small">
                                Tip: Use high-contrast QR images (square) so guests can scan easily for payments and Wi-Fi access.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="d-flex align-items-center justify-content-end gap-3 mb-5">
                <button type="reset" class="btn btn-outline-secondary">Reset Fields</button>
                <button type="submit" class="btn btn-primary">
                    Apply All Changes
                </button>
            </div>
        </div>
</div>
</div>
</form>
</div>

<!-- Modal: How to get Google Review Link & Place ID -->
<x-modal id="googleHelpModal" title="Google Review Setup Guide" size="modal-lg">
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card h-100 bg-light border-0">
                <div class="card-body">
                    <h6 class="fw-bold text-primary mb-3"><i class="bx bx-map-pin me-2"></i>How to get Place ID?</h6>
                    <ol class="small text-muted ps-3 mb-0">
                        <li class="mb-2">Go to the <a href="https://developers.google.com/maps/documentation/places/web-service/place-id" target="_blank" class="text-decoration-underline">Google Place ID Finder</a>.</li>
                        <li class="mb-2">Enter your restaurant name in the address search bar.</li>
                        <li class="mb-2">Select your business from the dropdown.</li>
                        <li class="mb-2">Copy the <b>Place ID</b> (it looks like <code>ChIJa1p3...</code>) and paste it here.</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 bg-light border-0">
                <div class="card-body">
                    <h6 class="fw-bold text-primary mb-3"><i class="bx bx-link-external me-2"></i>Get Direct Review Link</h6>
                    <ol class="small text-muted ps-3 mb-0">
                        <li class="mb-2">Log in to your <b>Google Business Profile</b>.</li>
                        <li class="mb-2">Select <b>"Ask for reviews"</b> from the dashboard.</li>
                        <li class="mb-2">Copy the short URL provided (e.g., <code>https://g.page/r/XYZ/review</code>).</li>
                        <li class="mb-2">Alternatively, use <code>https://search.google.com/local/writereview?placeid=<b>YOUR_PLACE_ID</b></code>.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="alert alert-info border-0 mt-4 mb-0 small">
        <i class="bx bx-bulb me-2"></i><b>Pro Tip:</b> Providing a direct review link increases the likelihood of customers leaving feedback by reducing the number of clicks!
    </div>

    <x-slot:footer>
        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Got it!</button>
    </x-slot:footer>
</x-modal>
@endsection

@push('styles')
<style>
    .transition-all {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .hover-transform:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
    }

    .sticky-top {
        transition: all 0.3s ease;
    }
</style>
@endpush

@push('scripts')
<script>
    // Real-time Hex display
    document.getElementById('brandColor').addEventListener('input', function() {
        document.getElementById('colorHex').textContent = this.value;
    });

    // Profile Photo Preview
    document.getElementById('imageUpload').addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profilePreview').src = e.target.result;
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Logo Preview
    document.getElementById('logoUpload').addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('logoPreview');
                const placeholder = document.getElementById('logoPreviewPlaceholder');

                preview.src = e.target.result;
                preview.classList.remove('d-none');
                if (placeholder) placeholder.classList.add('d-none');
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Handle QR Previews (Reusable Logic)
    function setupImagePreview(inputId, previewId, placeholderId) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        const placeholder = document.getElementById(placeholderId);

        if (input && preview) {
            const initialSrc = preview.getAttribute('data-initial-src') || '';

            input.addEventListener('change', function() {
                const file = this.files && this.files[0] ? this.files[0] : null;
                if (!file) {
                    if (initialSrc) {
                        preview.src = initialSrc;
                        preview.classList.remove('d-none');
                        if (placeholder) placeholder.classList.add('d-none');
                    } else {
                        preview.classList.add('d-none');
                        if (placeholder) placeholder.classList.remove('d-none');
                    }
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                    if (placeholder) placeholder.classList.add('d-none');
                };
                reader.readAsDataURL(file);
            });
        }
    }

    setupImagePreview('paymentQrImageInput', 'paymentQrPreview', 'paymentQrPreviewPlaceholder');
    setupImagePreview('wifiQrImageInput', 'wifiQrPreview', 'wifiQrPreviewPlaceholder');
</script>
@endpush