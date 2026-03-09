@props([
    'name' => '',
    'label' => '',
    'value' => '',
    'placeholder' => '',
    'rows' => 4,
    'required' => false,
    'readonly' => false,
    'disabled' => false,
    'help' => '',
])

@php
    $hasError = $errors->has($name);
    $textareaClasses = 'form-control' . ($hasError ? ' is-invalid' : '');
    $value = old($name, $value);
@endphp

@if ($label)
    <div>
        <x-form.label :for="$name" :required="$required">
            {{ $label }}
        </x-form.label>
    @else
        <div>
@endif

<textarea name="{{ $name }}" id="{{ $name }}" rows="{{ $rows }}" placeholder="{{ $placeholder }}"
    @if ($required) required @endif @if ($readonly) readonly @endif
    @if ($disabled) disabled @endif {{ $attributes->merge(['class' => $textareaClasses]) }}>{{ $value }}</textarea>

@if ($help)
    <div class="form-text">{{ $help }}</div>
@endif

@error($name)
    <div class="invalid-feedback d-block">
        {{ $message }}
    </div>
@enderror
</div>
