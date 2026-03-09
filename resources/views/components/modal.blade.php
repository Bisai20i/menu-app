@props([
    'id',
    'title' => '',
    'size' => '', // modal-sm, modal-lg, modal-xl
    'centered' => true,
    'static' => false,
    'scrollable' => false,
])

<div {{ $attributes->merge(['class' => 'modal fade', 'id' => $id, 'tabindex' => '-1', 'aria-labelledby' => $id . 'Label', 'aria-hidden' => 'true']) }} 
     @if($static) data-bs-backdrop="static" data-bs-keyboard="false" @endif>
    <div class="modal-dialog {{ $size }} {{ $centered ? 'modal-dialog-centered' : '' }} {{ $scrollable ? 'modal-dialog-scrollable' : '' }}">
        <div class="modal-content overflow-hidden border-0 shadow-lg" style="border-radius: 1.25rem;">
            <div class="modal-header border-0 pt-4 px-4 pb-2">
                <h5 class="modal-title fw-bold fs-4 text-dark text-center" id="{{ $id }}Label">{{ $title }}</h5>
                {{-- <button type="button" class="btn-close bg-white shadow-none" data-bs-dismiss="modal" aria-label="Close"></button> --}}
            </div>
            <div class="modal-body px-4 py-3">
                {{ $slot }}
            </div>
            @if(isset($footer))
                <div class="modal-footer border-0 pb-4 px-4 pt-2">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .modal-content {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
    }
    .modal-backdrop.show {
        opacity: 0.7;
    }
</style>
