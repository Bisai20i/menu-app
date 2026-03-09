<div x-data="{
    deleteId: null,
    deleteName: '',
    modal: null,
    selectedIds: [],
    allIds: {{ collect($items->items())->pluck('id') }},
    init() {
        if (this.$refs.confirmModal) {
            this.modal = new bootstrap.Modal(this.$refs.confirmModal);
        }
    },
    toggleAll() {
        if (this.selectedIds.length === this.allIds.length) {
            this.selectedIds = [];
        } else {
            this.selectedIds = [...this.allIds];
        }
    }
}" @item-deleted.window="modal.hide()" @items-deleted.window="modal.hide()"
    class="container-fluid p-0">

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bx bx-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 py-3">
            <div class="row g-3 align-items-center">
                <!-- Search -->
                <div class="col-md-3">
                    <div class="input-group border rounded">
                        <span class="input-group-text border-0 bg-transparent"><i
                                class="bx bx-search text-muted"></i></span>
                        <input wire:model.live.debounce.300ms="search" type="text"
                            class="form-control border-0 shadow-none" placeholder="Search...">
                    </div>
                </div>

                <!-- Dynamic Filters -->
                @foreach ($filterConfigs as $field => $config)
                    <div class="col-md-2">
                        <select wire:model.live="filters.{{ $field }}" class="form-select shadow-none">
                            <option value="">All {{ $config['label'] }}</option>
                            @foreach ($config['options'] as $val => $label)
                                <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                @endforeach

                <div class="col-md-auto ms-auto d-flex gap-2">
                    <!-- Check length using Alpine's selectedIds -->
                    <template x-if="selectedIds.length > 0">
                        <button
                            @click="deleteName = selectedIds.length + ' selected items'; deleteId = 'bulk'; modal.show()"
                            class="btn btn-outline-danger btn-sm">
                            <i class="bx bx-trash"></i> Bulk Delete (<span x-text="selectedIds.length"></span>)
                        </button>
                    </template>

                    @if ($search !== '' || !empty(array_filter($filters)))
                        <button wire:click="clearFilters"
                            class="btn btn-link text-decoration-none text-muted fw-bold btn-sm">
                            <i class="bx bx-reset"></i> Reset
                        </button>
                    @endif

                    <select wire:model.live="perPage" class="form-select form-select-sm w-auto shadow-none">
                        <option value="10">10 Rows</option>
                        <option value="25">25 Rows</option>
                        <option value="50">50 Rows</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4" width="40">
                            <input type="checkbox" class="form-check-input" @click="toggleAll()"
                                :checked="selectedIds.length === allIds.length && allIds.length > 0">
                        </th>
                        @foreach ($columns as $key => $col)
                            <th class="border-0"
                                @if ($col['sortable'] && count($items)) style="cursor:pointer" wire:click="sortBy('{{ $key }}')" @endif>
                                {{ $col['label'] }}
                                @if ($col['sortable'])
                                    <i
                                        class="bx {{ $sortField === $key ? ($sortDirection === 'asc' ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt') : 'bx-sort' }} ms-1"></i>
                                @endif
                             </th>
                        @endforeach
                        <th class="border-0 text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="position-relative">
                    {{-- Loading overlay moved inside a TR for valid HTML or handled differently --}}
                    @if(false) {{-- Placeholder for future refined loading UI --}}
                        <div wire:loading.flex
                            class="position-absolute top-0 start-0 w-100 h-100 bg-white bg-opacity-75 align-items-center justify-content-center"
                            style="z-index: 5;">
                            <div class="spinner-border text-primary spinner-border-sm"></div>
                        </div>
                    @endif

                    @forelse($items as $item)
                        <tr wire:key="row-{{ $item->id }}">
                            <td class="ps-4">
                                <input type="checkbox" class="form-check-input shadow-none"
                                    :value="{{ $item->id }}" x-model.number="selectedIds">
                            </td>
                            @foreach ($columns as $key => $col)
                                <td>
                                    @if ($col['type'] === 'toggleable')
                                        <div class="d-flex gap-2">
                                            {{-- <label class="form-label">On</label> --}}
                                            <div class="form-check form-switch">

                                                <input class="form-check-input shadow-none" type="checkbox"
                                                    role="switch"
                                                    wire:click="toggleBoolean({{ $item->id }}, '{{ $key }}')"
                                                    {{ $item->$key ? 'checked' : '' }}>

                                            </div>
                                            {{-- <label class="form-label">Off</label> --}}
                                        </div>
                                    @elseif($col['type'] === 'badge')
                                        @php $badgeClass = $badges[$key][$item->$key] ?? 'bg-light text-dark border'; @endphp
                                        <span class="badge {{ $badgeClass }} px-2 py-1">
                                            {{ strtoupper($item->$key) }}
                                        </span>
                                    @elseif($col['type'] === 'date')
                                        <span class="text-muted small">{{ $item->$key?->format('d M, Y') }}</span>
                                    @elseif($col['type'] === 'image')
                                        @if ($item->$key)
                                            <img src="{{ asset('storage/' . $item->$key) }}"
                                                alt="{{ $item->name ?? 'Image' }}" class="rounded shadow-sm"
                                                style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted"
                                                style="width: 40px; height: 40px;">
                                                <i class="bx bx-image-alt"></i>
                                            </div>
                                        @endif
                                    @elseif($col['type'] === 'relation')
                                        @php
                                            $relation = $col['relation'];
                                            $field = $col['field'];
                                            $relatedModel = $item->$relation;
                                        @endphp
                                        <span class="text-dark">{{ $relatedModel->$field ?? '-' }}</span>
                                    @else
                                        <span
                                            class="{{ $key === 'name' ? 'fw-bold' : '' }}">{{ $item->$key }}</span>
                                    @endif
                                </td>
                            @endforeach
                            <td class="text-end pe-4">
                                <div class="btn-group btn-group-sm rounded shadow-sm">
                                    <a href="{{ route($routePrefix . '.edit', $item->id) }}"
                                        class="btn btn-white border-end">
                                        <i class="bx bx-edit-alt text-primary"></i>
                                    </a>
                                    <button type="button"
                                        @click="deleteId = {{ $item->id }}; deleteName = 'this record'; modal.show()"
                                        class="btn btn-white text-danger">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($columns) + 2 }}" class="text-center py-5 text-muted">No records
                                found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing {{ $items->firstItem() }} to {{ $items->lastItem() }} of {{ $items->total() }}
                </div>
                {{ $items->links() }}
            </div>
        </div>
    </div>

    <!-- Alpine Controlled Modal -->
    <div class="modal fade" x-ref="confirmModal" x-init="modal = new bootstrap.Modal($el)" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow">
                <div class="modal-body text-center p-4">
                    <div class="text-danger mb-3"><i class="bx bx-error-circle display-4"></i></div>
                    <h5 class="fw-bold">Confirm Action</h5>
                    <p class="text-muted small mb-4">Are you sure you want to delete <span x-text="deleteName"></span>?
                    </p>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-danger"
                            @click="deleteId === 'bulk' ? $wire.deleteSelected(selectedIds) : $wire.deleteItem(deleteId)"
                            wire:loading.attr="disabled">
                            <span wire:loading class="spinner-border spinner-border-sm me-1"></span> Confirm Delete
                        </button>
                        <button type="button" class="btn btn-light border" @click="modal.hide()">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
