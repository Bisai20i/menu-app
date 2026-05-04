@extends('layouts.admin-layout')

@push('title', 'Menu Gallery')

@section('content')
<div>
    <div class="d-flex justify-content-between align-items-center">
        <h4 class="fw-bold pb-3 mb-0"><span class="text-muted fw-light">Menu /</span> Gallery</h4>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bx bx-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bx bx-error-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li><i class="bx bx-error-circle me-1"></i> {{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Professional Upload Section -->
    <div class="card mb-5 border-0 shadow-sm overflow-hidden">
        <div class="card-body p-0">
            <div class="row g-0">
                <!-- Dropzone Area -->
                <div class="col-lg-8">
                    <div id="dropzone" class="h-100 p-5 text-center transition border-end" 
                        style="border: 2px dashed #e1e4e8; background: #fcfdfe; cursor: pointer;"
                        onclick="document.getElementById('files').click()">
                        <div class="py-4">
                            <div class="upload-icon mb-3">
                                <i class="bx bx-cloud-upload display-4 text-primary"></i>
                            </div>
                            <h5 class="fw-bold mb-1">Click or Drag & Drop to Upload</h5>
                            <p class="text-muted small mb-3">Support for JPG, PNG, GIF, and PDF (Max 5MB each)</p>
                            <button type="button" class="btn btn-outline-primary btn-sm px-4 rounded-pill">
                                Browse Files
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Info & Submit Area -->
                <div class="col-lg-4 d-flex flex-column bg-light bg-opacity-50 p-4">
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-3 d-flex align-items-center">
                            <i class="bx bx-info-circle me-2 text-primary"></i> Media Details
                        </h6>
                        
                        <!-- Previews -->
                        <div id="previewGrid" class="preview-grid d-flex flex-wrap gap-2 mb-4" style="min-height: 80px;">
                            <div id="emptyPreview" class="w-100 h-100 py-2 d-flex align-items-center justify-content-center text-muted small border rounded bg-white italic" style="height: 80px;">
                                No files selected
                            </div>
                        </div>

                        <!-- External URL Input -->
                        <div class="mb-4">
                            <label for="external_url" class="form-label text-xs fw-bold text-uppercase ls-1">External Media URL</label>
                            <div class="input-group input-group-merge shadow-none border rounded">
                                <span class="input-group-text border-0 bg-transparent text-primary">
                                    <i class="bx bx-link-external"></i>
                                </span>
                                <input type="url" name="external_url" id="external_url" form="uploadForm"
                                    class="form-control border-0 bg-transparent ps-0" 
                                    placeholder="https://example.com/image.jpg"
                                    oninput="checkFormState()">
                            </div>
                            <div class="form-text text-xs" style="font-size: 0.65rem;">Direct links to images or PDFs (Google Drive, Bunny.net)</div>
                        </div>
                    </div>
                    
                    <form action="{{ route('master.menu-gallery.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                        @csrf
                        <input type="file" id="files" name="files[]" multiple class="d-none" accept=".jpg,.jpeg,.png,.gif,.pdf">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary d-flex align-items-center justify-content-center py-2 disabled" id="uploadBtn">
                                <i class="bx bx-plus-circle me-2"></i> Add to Gallery
                            </button>
                            <button type="button" class="btn btn-link btn-sm text-danger d-none" id="clearBtn">
                                <i class="bx bx-x me-1"></i> Clear All
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Professional Gallery Grid -->
    <div class="row g-4">
        @forelse ($gallery as $item)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                <div class="card h-100 border-0 shadow-sm overflow-hidden gallery-card transition {{ !$item->is_active ? 'inactive-card' : '' }}" 
                    id="card-{{ $item->id }}">
                    <!-- Media Tooltip/Overlay Wrapper -->
                    <div class="position-relative bg-light card-img-wrapper" style="height: 180px;">
                        @if ($item->media_type === 'pdf')
                            <div class="d-flex flex-column align-items-center justify-content-center h-100 p-3">
                                <i class="bx bxs-file-pdf text-danger" style="font-size: 3.5rem;"></i>
                                <span class="small text-muted mt-2 text-center text-truncate w-100">PDF Document</span>
                            </div>
                        @else
                            <img src="{{ $item->display_url }}" class="w-100 h-100 object-fit-cover transition-slow" alt="Menu Image">
                        @endif

                        <!-- Smooth Action Overlay (More professional) -->
                        <div class="card-action-overlay transition-all d-flex align-items-center justify-content-center gap-2">
                            <button type="button" class="btn btn-white btn-sm px-3 rounded-pill shadow-sm" 
                                onclick="viewMedia('{{ $item->display_url }}', '{{ $item->media_type }}')">
                                <i class="bx bx-show-alt me-1"></i> View
                            </button>
                            
                            <button type="button" class="btn btn-white btn-sm btn-icon rounded-circle shadow-sm text-danger" 
                                onclick="confirmDelete('{{ route('master.menu-gallery.destroy', $item->id) }}')">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>

                        <!-- Top-Right Switch (Standard Position) -->
                        <div class="position-absolute top-0 end-0 p-2 z-index-20">
                            <div class="form-check form-switch m-0 switch-lg">
                                <input class="form-check-input status-toggle cursor-pointer" type="checkbox" role="switch" 
                                    onchange="toggleStatus({{ $item->id }}, this)" {{ $item->is_active ? 'checked' : '' }}>
                            </div>
                        </div>

                        <!-- AJAX Loader Overlay -->
                        <div class="loading-overlay position-absolute top-0 start-0 w-100 h-100 d-none align-items-center justify-content-center bg-white bg-opacity-75 z-index-30 transition">
                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body p-2 bg-white">
                        <div class="d-flex align-items-center justify-content-between gap-2">
                            <!-- Sort Order (Cleaner) -->
                            <div class="input-group input-group-sm rounded bg-light border-0 px-2" style="max-width: 100px;">
                                <span class="input-group-text bg-transparent border-0 p-0 text-muted">
                                    <i class="bx bx-sort-alt-2" style="font-size: 0.85rem;"></i>
                                </span>
                                <input type="number" class="form-control form-control-sm border-0 bg-transparent fw-bold text-center sort-input" 
                                    value="{{ $item->sort_order }}" 
                                    onchange="updateOrder({{ $item->id }}, this.value, this)"
                                    style="font-size: 0.85rem;">
                            </div>

                            <div class="text-xs text-muted">
                                {{ $item->created_at->format('d M') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="bg-light rounded-4 p-5 border border-dashed">
                    <i class="bx bx-images display-1 text-muted opacity-25"></i>
                    <h5 class="mt-3 fw-bold">No items found</h5>
                    <p class="text-muted small">Upload your menu images or PDFs to get started!</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Media Viewer Modal -->
<div class="modal fade" id="mediaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header py-2">
                <h5 class="modal-title h6">Media Viewer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 text-center bg-light">
                <div id="imageViewer" class="p-3 d-none">
                    <img src="" id="modalImage" class="img-fluid rounded shadow-sm" style="max-height: 80vh;">
                </div>
                <div id="pdfViewer" class="d-none" style="height: 80vh;">
                    <iframe src="" id="modalPdf" class="w-100 h-100 border-0"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<x-modal id="deleteModal" title="Confirm Delete">
    <div class="text-center py-3">
        <div class="mb-4">
            <i class="bx bx-trash text-danger opacity-75" style="font-size: 5rem;"></i>
        </div>
        <h5 class="fw-bold mb-2">Delete this item?</h5>
        <p class="text-muted small px-4">This action cannot be undone. The media file will be permanently removed from your storage.</p>
    </div>
    <x-slot name="footer">
        <div class="d-flex w-100 gap-2">
            <button type="button" class="btn btn-label-secondary flex-grow-1" data-bs-dismiss="modal">Cancel</button>
            <form id="deleteForm" method="POST" class="flex-grow-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger w-100">Permanently Delete</button>
            </form>
        </div>
    </x-slot>
</x-modal>
 
 <!-- Single Reusable Image Validation Modal -->
 <x-image-validation-modal id="imageValidationModal" />

<style>
    .transition { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .transition-slow { transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1); }
    
    /* Gallery Card Styling */
    .gallery-card { border-radius: 12px; background: #fff; }
    .gallery-card:hover { transform: translateY(-5px); box-shadow: 0 0.75rem 1.5rem rgba(0,0,0,0.1) !important; }
    .gallery-card:hover .transition-slow { transform: scale(1.05); }
    .gallery-card:hover .card-action-overlay { opacity: 1; backdrop-filter: blur(2px); }
    
    .inactive-card { opacity: 0.65 !important; }
    .card-img-wrapper { overflow: hidden; }
    
    /* Action Overlay */
    .card-action-overlay {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.4);
        opacity: 0;
        z-index: 10; /* Lower than toggle */
    }
    
    .btn-white { background: #fff; border: none; color: #566a7f; }
    .btn-white:hover { background: #f8f9fa; color: #696cff; }
    .btn-icon { width: 34px; height: 34px; padding: 0; display: flex; align-items: center; justify-content: center; }
    
    /* Z-Index Hierarchy */
    .z-index-10 { z-index: 10; }
    .z-index-20 { z-index: 20; }
    .z-index-30 { z-index: 30; }
    
    /* Loader Overlay */
    .loading-overlay { border-radius: 12px 12px 0 0; }

    /* Switch customization */
    .switch-lg .form-check-input { width: 2.2em; height: 1.2em; margin-top: 0; border: none; background-color: rgba(0,0,0,0.1); }
    .switch-lg .form-check-input:checked { background-color: #696cff; }

    /* Info Section */
    .text-xs { font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }

    #dropzone:hover { border-color: #696cff !important; background: #f0f2ff !important; transition: all 0.2s ease; }
    .preview-grid div { animation: fadeIn 0.4s ease forwards; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .italic { font-style: italic; }
</style>

@push('scripts')
<script>
    const mediaModal = new bootstrap.Modal(document.getElementById('mediaModal'));
    const imageViewer = document.getElementById('imageViewer');
    const pdfViewer = document.getElementById('pdfViewer');
    const modalImage = document.getElementById('modalImage');
    const modalPdf = document.getElementById('modalPdf');
    const previewGrid = document.getElementById('previewGrid');
    const emptyPreview = document.getElementById('emptyPreview');
    const fileInput = document.getElementById('files');
    const uploadBtn = document.getElementById('uploadBtn');
    const clearBtn = document.getElementById('clearBtn');
    const dropzone = document.getElementById('dropzone');
    const externalUrl = document.getElementById('external_url');
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const deleteForm = document.getElementById('deleteForm');
    const validationModal = new bootstrap.Modal(document.getElementById('validationModal'));

    // Helper: Show validation modal with dynamic content
    function showFileValidationModal(title, message, rejectedFiles) {
        document.getElementById('imageValidationModal-title').textContent = title;
        document.getElementById('imageValidationModal-message').textContent = message;
        
        const listElement = document.getElementById('imageValidationModal-list');
        listElement.innerHTML = rejectedFiles
            .map(name => `<li class="list-group-item py-1 px-3 d-flex align-items-center"><i class="bx bx-x-circle text-danger me-2"></i><span class="text-truncate">${name}</span></li>`)
            .join('');
        
        imageValidationModal.show();
    }

    function confirmDelete(url) {
        deleteForm.action = url;
        deleteModal.show();
    }

    function checkFormState() {
        if (fileInput.files.length > 0 || externalUrl.value.trim() !== '') {
            uploadBtn.classList.remove('disabled');
        } else {
            uploadBtn.classList.add('disabled');
        }
    }

    // Drag & Drop handling
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, () => dropzone.classList.add('bg-light'), false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, () => dropzone.classList.remove('bg-light'), false);
    });

    dropzone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        handleFiles(files);
    }

    // Input change handling
    fileInput.addEventListener('change', function() {
        handleFiles(this.files);
    });

    function handleFiles(files) {
        previewGrid.innerHTML = '';
        const dt = new DataTransfer();
        let rejectedFiles = [];

        if (files.length > 0) {
            Array.from(files).forEach(file => {
                // Check file size (5MB = 5 * 1024 * 1024 bytes)
                if (file.size > 5 * 1024 * 1024) {
                    rejectedFiles.push(file.name);
                    return; // Skip this file
                }

                dt.items.add(file); // Add to our valid list

                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'position-relative';
                    div.style.width = '60px';
                    div.style.height = '60px';
                    
                    if (file.type.startsWith('image/')) {
                        div.innerHTML = `<img src="${e.target.result}" class="w-100 h-100 rounded border object-fit-cover shadow-sm" title="${file.name}">`;
                    } else if (file.type === 'application/pdf') {
                        div.innerHTML = `
                            <div class="w-100 h-100 rounded border bg-white d-flex flex-column align-items-center justify-content-center shadow-sm" title="${file.name}">
                                <i class="bx bxs-file-pdf text-danger fs-3"></i>
                            </div>`;
                    }
                    previewGrid.appendChild(div);
                }
                reader.readAsDataURL(file);
            });

            // Update file input with only valid files
            fileInput.files = dt.files;

            if (rejectedFiles.length > 0) {
                showFileValidationModal(
                    'Files Too Large',
                    'The following files exceed the 5MB limit and were not added:',
                    rejectedFiles
                );
            }

            if (fileInput.files.length > 0) {
                clearBtn.classList.remove('d-none');
            } else if (previewGrid.innerHTML === '') {
                previewGrid.appendChild(emptyPreview);
            }
        } else {
            resetUpload();
        }
        checkFormState();
    }

    clearBtn.addEventListener('click', resetUpload);

    function resetUpload() {
        fileInput.value = '';
        previewGrid.innerHTML = '';
        previewGrid.appendChild(emptyPreview);
        clearBtn.classList.add('d-none');
        checkFormState();
    }

    function toggleStatus(id, input) {
        const card = document.getElementById(`card-${id}`);
        const loader = card.querySelector('.loading-overlay');
        
        loader.classList.remove('d-none');
        loader.classList.add('d-flex');

        fetch(`{{ url('master/menu-gallery') }}/${id}/status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        }).then(res => res.json())
          .then(data => {
              loader.classList.add('d-none');
              loader.classList.remove('d-flex');
              if (data.success) {
                  if (!data.is_active) {
                      card.classList.add('inactive-card');
                  } else {
                      card.classList.remove('inactive-card');
                  }
              }
          }).catch(() => {
              loader.classList.add('d-none');
              loader.classList.remove('d-flex');
              input.checked = !input.checked; // Revert
          });
    }

    function updateOrder(id, order, input) {
        const card = document.getElementById(`card-${id}`);
        const loader = card.querySelector('.loading-overlay');
        
        loader.classList.remove('d-none');
        loader.classList.add('d-flex');

        fetch(`{{ url('master/menu-gallery') }}/${id}/order`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ sort_order: order })
        }).then(res => res.json())
          .then(data => {
              loader.classList.add('d-none');
              loader.classList.remove('d-flex');
              if (data.success) {
                  input.classList.add('text-success');
                  setTimeout(() => input.classList.remove('text-success'), 2000);
              }
          }).catch(() => {
              loader.classList.add('d-none');
              loader.classList.remove('d-flex');
              input.classList.add('text-danger');
              setTimeout(() => input.classList.remove('text-danger'), 2000);
          });
    }

    function viewMedia(url, type) {
        // Reset
        imageViewer.classList.add('d-none');
        pdfViewer.classList.add('d-none');
        modalImage.src = '';
        modalPdf.src = '';

        if (type === 'pdf') {
            modalPdf.src = url;
            pdfViewer.classList.remove('d-none');
        } else {
            modalImage.src = url;
            imageViewer.classList.remove('d-none');
        }

        mediaModal.show();
    }

    // Loading State for upload
    document.getElementById('uploadForm').addEventListener('submit', function() {
        const btn = document.getElementById('uploadBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Processing...';
    });
</script>
@endpush
@endsection
