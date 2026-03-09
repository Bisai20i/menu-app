@props([
    'type' => 'text',
    'name' => '',
    'label' => '',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'readonly' => false,
    'disabled' => false,
    'help' => '',
])

@php
    $hasError = $errors->has($name);
    $inputClasses = 'form-control' . ($hasError ? ' is-invalid' : '');
    $value = old($name, $value);
@endphp

@if($label)
    <div>
        <x-form.label :for="$name" :required="$required">
            {{ $label }}
        </x-form.label>
@else
    <div>
@endif

        <input 
            type="{{ $type == 'float' ? 'number' : $type }}" 
            @if($type=="float") step='0.01' @endif
            name="{{ $name }}" 
            id="{{ $name }}"
            value="{{ $value }}"
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            @if($readonly) readonly @endif
            @if($disabled) disabled @endif
            {{ $attributes->merge(['class' => $inputClasses]) }}
        >

        @if($help)
            <div class="form-text">{{ $help }}</div>
        @endif

        @error($name)
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror
    </div>