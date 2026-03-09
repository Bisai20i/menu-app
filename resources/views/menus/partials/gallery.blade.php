@if($galleryImages->count() > 0)
<hr class="gold-divider">
<section class="gallery-section py-5" id="gallery">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-label">Visual menu</span>
            <h2 class="gallery-heading mb-1">Our <em>Gallery</em></h2>
            <p class="text-muted" style="font-size:.85rem">A glimpse into our signature offerings</p>
        </div>

        <div class="gallery-grid">
            @foreach($galleryImages as $image)
                <div class="gallery-card" 
                     onclick="{{ $image->media_type === 'pdf' ? 'window.open(\''.$image->display_url.'\', \'_blank\')' : 'openImageModal(\''.$image->display_url.'\')' }}">
                    <div class="gallery-thumb" style="{{ $image->media_type === 'pdf' ? 'background:#fde8e8' : '' }}">
                        <span class="thumb-type-badge {{ $image->media_type === 'pdf' ? 'badge-pdf' : 'badge-img' }}">
                            {{ strtoupper($image->media_type) }}
                        </span>
                        @if($image->media_type === 'pdf')
                            <i class="bi bi-file-earmark-pdf" style="color:#c0392b"></i>
                            <span class="thumb-label">View PDF Menu</span>
                        @else
                            <img src="{{ $image->display_url }}" alt="Gallery image" style="width: 100%; height: 100%; object-fit: cover;">
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body p-0 position-relative text-center">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                <img src="" id="modalImage" class="img-fluid rounded shadow-lg" style="max-height: 90vh;">
            </div>
        </div>
    </div>
</div>
@endif
