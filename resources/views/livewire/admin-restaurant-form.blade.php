<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form wire:submit.prevent="save">
            <div class="row">
                <!-- Admin Information -->
                <div class="col-md-6 border-end">
                    <h5 class="mb-4 text-primary"><i class="bi bi-person-badge me-2"></i>Admin Credentials</h5>
                    
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="John Doe">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" wire:model="email" class="form-control @error('email') is-invalid @enderror" placeholder="john@example.com">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Admin Role</label>
                        <select wire:model="role" value="{{ $role ?? 'admin' }}" class="form-select @error('role') is-invalid @enderror">
                            <option value="">---Select Role ---</option>
                            <option value="superadmin">Super Admin</option>
                            <option value="admin">Admin</option>

                        </select>
                        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password {{ $adminId ? '(Leave blank to keep current)' : '' }}</label>
                        <input type="password" wire:model="password" class="form-control @error('password') is-invalid @enderror" placeholder="********">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Restaurant Settings -->
                <div class="col-md-6">
                    <h5 class="mb-4 text-primary ms-md-3"><i class="bi bi-shop me-2"></i>Restaurant Settings</h5>
                    
                    <div class="ms-md-3">
                        <div class="mb-3">
                            <label class="form-label">Restaurant Name</label>
                            <input type="text" wire:model="restaurant_name" class="form-control @error('restaurant_name') is-invalid @enderror" placeholder="Awesome Pizza">
                            @error('restaurant_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Currency Symbol</label>
                                <input type="text" wire:model="currency" class="form-control @error('currency') is-invalid @enderror" placeholder="NPR, $, €">
                                @error('currency') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tax Percentage (%)</label>
                                <input type="number" step="0.01" wire:model="tax_percentage" class="form-control @error('tax_percentage') is-invalid @enderror" placeholder="13.0">
                                @error('tax_percentage') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">Primary Brand Color</label>
                            <div class="d-flex align-items-center">
                                <input type="color" wire:model.live="primary_color" class="form-control form-control-color" title="Choose brand color" style="width: 60px; height: 45px;">
                                <code class="ms-3">{{ $primary_color }}</code>
                            </div>
                            @error('primary_color') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('master.admin-management.index') }}" class="btn btn-light border px-4">Cancel</a>
                <button type="submit" class="btn btn-primary px-4">
                    <span wire:loading.remove wire:target="save">
                        {{ $adminId ? 'Update Admin & Settings' : 'Create Admin & Restaurant' }}
                    </span>
                    <span wire:loading wire:target="save">
                        <span class="spinner-border spinner-border-sm me-1"></span> Processing...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
