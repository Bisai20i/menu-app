@props([
    'name' => '',
    'label' => '',
    'options' => [],
    'selected' => '',
    'placeholder' => 'Select an option',
    'required' => false,
    'disabled' => false,
    'help' => '',
    'valueKey' => 'id',
    'labelKey' => 'name',
])

@php
    $hasError = $errors->has($name);
    $selectClasses = 'form-select' . ($hasError ? ' is-invalid' : '');
    $selected = old($name, $selected);
@endphp

@if ($label)
    <div>
        <x-form.label :for="$name" :required="$required">
            {{ $label }}
        </x-form.label>
    @else
        <div>
@endif

<select name="{{ $name }}" id="{{ $name }}" @if ($required) required @endif
    @if ($disabled) disabled @endif {{ $attributes->merge(['class' => $selectClasses]) }}>
    @if ($placeholder)
        <option value="">{{ $placeholder }}</option>
    @endif

    @foreach ($options as $key => $option)
        @if (is_array($option) || is_object($option))
            @php
                $optionValue = is_array($option) ? $option[$valueKey] : $option->$valueKey;
                $optionLabel = is_array($option) ? $option[$labelKey] : $option->$labelKey;
            @endphp
            <option value="{{ $optionValue }}" {{ $selected == $optionValue ? 'selected' : '' }}>
                {{ $optionLabel }}
            </option>
        @else
            <option value="{{ $key }}" {{ $selected == $key ? 'selected' : '' }}>
                {{ $option }}
            </option>
        @endif
    @endforeach
</select>

@if ($help)
    <div class="form-text">{{ $help }}</div>
@endif

@error($name)
    <div class="invalid-feedback d-block">
        {{ $message }}
    </div>
@enderror
</div>
