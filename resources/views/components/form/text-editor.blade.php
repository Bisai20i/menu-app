@props([
    'name' => '',
    'label' => '',
    'value' => '',
    'placeholder' => 'Write something...',
    'required' => false,
    'height' => '300px',
    'help' => '',
])

@php
    $hasError = $errors->has($name);
    $editorId = 'editor-' . $name;
    $value = old($name, $value);
@endphp

@once('summernote')
    @push('scripts')
        <link href="{{ asset('backend/summer-note/summernote-bs5.min.css') }}" rel="stylesheet">
        <script src="{{ asset('backend/summer-note/summernote-bs5.min.js') }}"></script>
    @endpush
@endonce


@if ($label)
    <div>
        <x-form.label :for="$name" :required="$required">
            {{ $label }}
        </x-form.label>
    @else
        <div>
@endif
<textarea name="{{ $name }}" id="{{ $editorId }}" rows="10"
    class="w-100 form-control {{ $hasError ? 'is-invalid' : '' }}"> {{ $value }}</textarea>

{{-- <div class="editor-container {{ $hasError ? 'is-invalid' : '' }}">
            <div id="{{ $editorId }}" {{ $attributes->merge(['class' => '']) }}></div>
        </div>

        <input type="hidden" name="{{ $name }}" id="{{ $name }}" value="{{ $value }}"> --}}

@if ($help)
    <div class="form-text">{{ $help }}</div>
@endif

@error($name)
    <div class="invalid-feedback d-block">
        {{ $message }}
    </div>
@enderror
</div>
@push('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#{{ $editorId }}').summernote({
                placeholder: '{{ $placeholder }}',
                tabsize: 2,
                height: "{{ $height }}"
            });
            console.log('summer note initilized')
        })
    </script>
@endpush
