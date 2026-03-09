@props([
    'name' => '',
    'label' => '',
    'value' => '',
    'aspectRatio' => 16/9,
    'required' => false,
    'help' => '',
    'previewWidth' => '100%',
    'previewHeight' => '300px',
    'maxWidth' => 1920,
    'maxHeight' => 1080,
    'quality' => 0.9,
])

@php
    $hasError = $errors->has($name);
    $inputId = 'file-' . $name;
    $cropperId = 'cropper-' . $name;
    $modalId = 'modal-' . $name;
    $previewId = 'preview-' . $name;
    $hiddenInputId = 'hidden-' . $name;
@endphp

@once
    @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
    <style>
        .cropper-modal-backdrop {
            background-color: rgba(0, 0, 0, 0.8);
        }
        .cropper-preview-container {
            position: relative;
            overflow: hidden;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            background: #f8f9fa;
        }
        .cropper-preview-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .cropper-preview-remove {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
        }
        .cropper-image-container {
            max-height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .cropper-image-container img {
            max-width: 100%;
            max-height: 70vh;
        }
    </style>
    @endpush
@endonce

@if($label)
    <div class="mb-3">
        <x-form.label :for="$inputId" :required="$required">
            {{ $label }}
        </x-form.label>
@else
    <div class="mb-3">
@endif

        <input 
            type="file" 
            id="{{ $inputId }}"
            accept="image/*"
            class="form-control {{ $hasError ? 'is-invalid' : '' }}"
            @if($required) required @endif
            {{ $attributes->except(['class']) }}
        >

        <input type="hidden" name="{{ $name }}" id="{{ $hiddenInputId }}">

        @if($help)
            <div class="form-text">{{ $help }}</div>
        @endif

        @error($name)
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror

        <!-- Preview Container -->
        <div id="{{ $previewId }}" class="cropper-preview-container mt-3" style="width: {{ $previewWidth }}; height: {{ $previewHeight }}; display: none;">
            <button type="button" class="btn btn-danger btn-sm cropper-preview-remove">
                <i class="bi bi-x-lg"></i> Remove
            </button>
            <img src="" alt="Preview">
        </div>
    </div>

<!-- Cropper Modal -->
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crop Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="cropper-image-container">
                    <img id="{{ $cropperId }}" src="" alt="Image to crop">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="crop-{{ $name }}">Crop & Save</button>
            </div>
        </div>
    </div>
</div>

@once
    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <script>
        class ImageCropper {
            constructor(config) {
                this.config = config;
                this.cropper = null;
                this.modal = null;
                this.init();
            }

            init() {
                this.fileInput = document.getElementById(this.config.fileInputId);
                this.cropperImage = document.getElementById(this.config.cropperId);
                this.previewContainer = document.getElementById(this.config.previewId);
                this.previewImage = this.previewContainer.querySelector('img');
                this.hiddenInput = document.getElementById(this.config.hiddenInputId);
                this.cropButton = document.getElementById(this.config.cropButtonId);
                this.removeButton = this.previewContainer.querySelector('.cropper-preview-remove');
                
                this.modal = new bootstrap.Modal(document.getElementById(this.config.modalId));
                
                this.attachEventListeners();
                this.loadExistingImage();
            }

            attachEventListeners() {
                this.fileInput.addEventListener('change', (e) => this.handleFileSelect(e));
                this.cropButton.addEventListener('click', () => this.handleCrop());
                this.removeButton.addEventListener('click', () => this.removeImage());
                
                document.getElementById(this.config.modalId).addEventListener('hidden.bs.modal', () => {
                    this.destroyCropper();
                });
            }

            handleFileSelect(e) {
                const file = e.target.files[0];
                if (!file || !file.type.startsWith('image/')) {
                    return;
                }

                const reader = new FileReader();
                reader.onload = (event) => {
                    this.cropperImage.src = event.target.result;
                    this.modal.show();
                    this.initCropper();
                };
                reader.readAsDataURL(file);
            }

            initCropper() {
                if (this.cropper) {
                    this.cropper.destroy();
                }

                this.cropper = new Cropper(this.cropperImage, {
                    aspectRatio: this.config.aspectRatio,
                    viewMode: 2,
                    dragMode: 'move',
                    autoCropArea: 1,
                    restore: false,
                    guides: true,
                    center: true,
                    highlight: false,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: false,
                });
            }

            handleCrop() {
                if (!this.cropper) return;

                const canvas = this.cropper.getCroppedCanvas({
                    maxWidth: this.config.maxWidth,
                    maxHeight: this.config.maxHeight,
                    fillColor: '#fff',
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high',
                });

                canvas.toBlob((blob) => {
                    const reader = new FileReader();
                    reader.onloadend = () => {
                        const base64data = reader.result;
                        this.hiddenInput.value = base64data;
                        this.previewImage.src = base64data;
                        this.previewContainer.style.display = 'block';
                        this.modal.hide();
                    };
                    reader.readAsDataURL(blob);
                }, 'image/jpeg', this.config.quality);
            }

            destroyCropper() {
                if (this.cropper) {
                    this.cropper.destroy();
                    this.cropper = null;
                }
                this.fileInput.value = '';
            }

            removeImage() {
                this.previewContainer.style.display = 'none';
                this.previewImage.src = '';
                this.hiddenInput.value = '';
                this.fileInput.value = '';
            }

            loadExistingImage() {
                const existingValue = this.hiddenInput.value || '{{ $value }}';
                if (existingValue) {
                    this.previewImage.src = existingValue;
                    this.previewContainer.style.display = 'block';
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            @foreach(['name' => $name, 'inputId' => $inputId, 'cropperId' => $cropperId, 'modalId' => $modalId, 'previewId' => $previewId, 'hiddenInputId' => $hiddenInputId] as $key => $id)
            @endforeach
            
            new ImageCropper({
                fileInputId: '{{ $inputId }}',
                cropperId: '{{ $cropperId }}',
                modalId: '{{ $modalId }}',
                previewId: '{{ $previewId }}',
                hiddenInputId: '{{ $hiddenInputId }}',
                cropButtonId: 'crop-{{ $name }}',
                aspectRatio: {{ $aspectRatio }},
                maxWidth: {{ $maxWidth }},
                maxHeight: {{ $maxHeight }},
                quality: {{ $quality }},
            });
        });
    </script>
    @endpush
@endonce