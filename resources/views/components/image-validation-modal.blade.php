@props([
    'id' => 'imageValidationModal',
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pb-4">
                <div class="mb-3">
                    <i class="bx bx-error-circle text-warning opacity-75" style="font-size: 4rem;"></i>
                </div>
                <h5 class="fw-bold mb-2" id="{{ $id }}-title">Validation Error</h5>
                <p class="text-muted small mb-3" id="{{ $id }}-message">
                    <!-- Message will be set dynamically -->
                </p>
                <ul id="{{ $id }}-list" class="list-group list-group-flush text-start small mb-0 border rounded overflow-hidden">
                    <!-- Items will be listed here -->
                </ul>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal">Understood</button>
            </div>
        </div>
    </div>
</div>

