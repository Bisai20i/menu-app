@props([
    'name',
    'label',
    'value' => [],
    'required' => false,
    'keyPlaceholder' => 'Key',
    'valuePlaceholder' => 'Value',
    'valueType' => 'text',
    'help' => null,
])

@php
    $jsonValue = is_array($value) ? $value : json_decode($value, true) ?? [];
@endphp

<div class="col-12 mb-3"
     x-data="jsonField(
        '{{ $name }}',
        {{ json_encode($jsonValue) }}
     )">

    @if (isset($label))
        <label class="fw-bold mb-2">
            {{ $label }}
            @if ($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <template x-for="(row, index) in rows" :key="index">
        <div class="row g-2 mb-2 align-items-center">
            <div class="col-md-5">
                <input type="text" class="form-control shadow-none" :name="`${fieldName}[${index}][key]`" x-model="row.key"
                    placeholder="{{ $keyPlaceholder }}">
            </div>

            <div class="col-md-5">
                <template x-if="'{{ $valueType }}' === 'boolean'">
                    <select class="form-select shadow-none" :name="`${fieldName}[${index}][value]`" x-model="row.value">
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </template>

                <template x-if="'{{ $valueType }}' !== 'boolean'">
                    <input :type="'{{ $valueType }}'" class="form-control shadow-none"
                        :name="`${fieldName}[${index}][value]`" x-model="row.value" placeholder="{{ $valuePlaceholder }}">
                </template>
            </div>

            <div class="col-md-2 text-end">
                <button type="button" class="btn btn-outline-danger w-100" @click="remove(index)">
                    <i class="bx bx-trash"></i> Remove
                </button>
            </div>
        </div>
    </template>

    <button type="button" class="btn btn-outline-primary btn-sm mt-2" @click="add()">
        <i class="bx bx-plus"></i> Add Feature
    </button>

    @if ($help)
        <div class="form-text mt-1">{{ $help }}</div>
    @endif
</div>

<script>
    function jsonField(fieldName, initial) {
        return {
            fieldName,

            rows: Object.entries(initial).map(([k, v]) => ({
                key: k,
                value: v
            })),

            add() {
                this.rows.push({ key: '', value: '' })
            },

            remove(index) {
                this.rows.splice(index, 1)
            }
        }
    }
</script>

