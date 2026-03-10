@props([
    'name',
    'value' => [],
    'required' => false,
    'placeholder' => 'Enter value',
    'type' => 'text',
    'help' => null,
])

@php
    $arrayValue = is_array($value) ? $value : json_decode($value, true) ?? [];
@endphp

<div class="col-12 mb-3"
     x-data="arrayField(
        '{{ $name }}',
        {{ json_encode($arrayValue) }}
     )">

    <template x-for="(row, index) in rows" :key="index">
        <div class="row g-2 mb-2 align-items-center">
            <div class="col">
                <input :type="'{{ $type }}'" 
                       class="form-control shadow-none" 
                       :name="`${fieldName}[]`" 
                       x-model="rows[index]"
                       placeholder="{{ $placeholder }}"
                       :required="index === 0 && {{ $required ? 'true' : 'false' }}">
            </div>

            <div class="col-auto">
                <button type="button" class="btn btn-outline-danger btn-sm" @click="remove(index)">
                    <i class="bx bx-trash"></i>
                </button>
            </div>
        </div>
    </template>

    <div class="d-flex justify-content-between align-items-center mt-2">
        <button type="button" class="btn btn-outline-primary btn-sm" @click="add()">
            <i class="bx bx-plus me-1"></i> Add Item
        </button>
        
        @if ($help)
            <div class="form-text small m-0">{{ $help }}</div>
        @endif
    </div>
</div>

<script>
    if (typeof arrayField !== 'function') {
        function arrayField(fieldName, initial) {
            return {
                fieldName,
                rows: Array.isArray(initial) && initial.length > 0 ? [...initial] : [''],

                add() {
                    this.rows.push('')
                },

                remove(index) {
                    if (this.rows.length > 1) {
                        this.rows.splice(index, 1)
                    } else {
                        this.rows[0] = '';
                    }
                }
            }
        }
    }
</script>
