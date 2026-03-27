@extends('layouts.admin-layout')

@push('title', 'Contact Messages')

@section('content')
    <div x-data="contactManager()">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-3">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mb-1">
                        <li class="breadcrumb-item text-muted">Communications</li>
                        <li class="breadcrumb-item active">Contact Messages</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0">Contact Messages</h3>
                <p class="text-muted mb-0">Messages from potential customers and users.</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm overflow-hidden mb-4">
            <div class="card-body">
                <form action="{{ route('master.contacts.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-12 col-md-4">
                        {{-- <label class="form-label fw-semibold">Search</label> --}}
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-search"></i></span>
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by name or email...">
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        {{-- <label class="form-label fw-semibold">Filter Status</label> --}}
                        <select name="filter" class="form-select" onchange="this.form.submit()">
                            <option value="all" {{ request('filter') == 'all' || !request('filter') ? 'selected' : '' }}>All Messages</option>
                            <option value="unread" {{ request('filter') == 'unread' ? 'selected' : '' }}>Unread</option>
                            <option value="read" {{ request('filter') == 'read' ? 'selected' : '' }}>Read</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-5 d-flex gap-2 justify-content-md-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-filter-alt me-1"></i> Apply Filters
                        </button>
                        <a href="{{ route('master.contacts.index') }}" class="btn btn-label-secondary text-nowrap">
                            <i class="bx bx-refresh me-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3">Status</th>
                            <th class="py-3">Name</th>
                            <th class="py-3">Email</th>
                            <th class="py-3">Received At</th>
                            <th class="py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($contacts as $contact)
                            <tr :class="isRead({{ $contact->id }}) ? 'text-muted' : 'fw-bold'" id="contact-row-{{ $contact->id }}">
                                <td>
                                    <span class="badge" 
                                          :class="isRead({{ $contact->id }}) ? 'bg-label-secondary' : 'bg-label-primary'"
                                          x-text="isRead({{ $contact->id }}) ? 'Read' : 'New'">
                                        {{ $contact->read_at ? 'Read' : 'New' }}
                                    </span>
                                </td>
                                <td>{{ $contact->name }}</td>
                                <td>{{ $contact->email }}</td>
                                <td>{{ $contact->created_at->format('M d, Y h:i A') }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <button @click="viewContact({{ json_encode($contact) }})" class="btn btn-sm btn-icon btn-label-primary" title="View Message">
                                            <i class="bx bx-show"></i>
                                        </button>
                                        <button @click="toggleRead({{ $contact->id }})" 
                                                class="btn btn-sm btn-icon" 
                                                :class="isRead({{ $contact->id }}) ? 'btn-label-warning' : 'btn-label-success'"
                                                :title="isRead({{ $contact->id }}) ? 'Mark as Unread' : 'Mark as Read'">
                                            <i class="bx" :class="isRead({{ $contact->id }}) ? 'bx-envelope-open' : 'bx-envelope'"></i>
                                        </button>
                                        <form action="{{ route('master.contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this message?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon btn-label-danger" title="Delete">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bx bx-envelope fs-1 mb-2"></i>
                                        <p>No contact messages found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($contacts->hasPages())
                <div class="card-footer border-top">
                    {{ $contacts->links() }}
                </div>
            @endif
        </div>

        <!-- Contact Detail Modal -->
        <div class="modal fade" id="contactModal" tabindex="-1" aria-hidden="true" x-ref="modal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" x-text="activeContact ? 'Message from ' + activeContact.name : ''"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" x-show="activeContact">
                        <div class="mb-3">
                            <label class="form-label text-muted small text-uppercase fw-bold">Email</label>
                            <p class="mb-0" x-text="activeContact?.email"></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small text-uppercase fw-bold">Date Received</label>
                            <p class="mb-0" x-text="formatDate(activeContact?.created_at)"></p>
                        </div>
                        <div class="mb-0">
                            <label class="form-label text-muted small text-uppercase fw-bold">Message</label>
                            <div class="p-3 bg-light rounded text-wrap" style="white-space: pre-wrap;" x-text="activeContact?.message"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Close</button>
                        <button @click="toggleRead(activeContact.id); bootstrap.Modal.getInstance($refs.modal).hide()" 
                                class="btn" 
                                :class="activeContact && isRead(activeContact.id) ? 'btn-warning' : 'btn-success'"
                                x-text="activeContact && isRead(activeContact.id) ? 'Mark as Unread' : 'Mark as Read'">
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function contactManager() {
            return {
                activeContact: null,
                readStatus: {!! json_encode($contacts->getCollection()->mapWithKeys(fn($c) => [$c->id => (bool)$c->read_at])) !!},
                
                isRead(id) {
                    return this.readStatus[id] || false;
                },

                viewContact(contact) {
                    this.activeContact = contact;
                    const modal = new bootstrap.Modal(this.$refs.modal);
                    modal.show();
                    
                    // Automatically mark as read when viewing if not already read
                    if (!this.isRead(contact.id)) {
                        this.toggleRead(contact.id);
                    }
                },

                async toggleRead(id) {
                    try {
                        let toggleUrl = ("{{ route('master.contacts.toggle-read', ':id') }}").replace(':id', id);

                        const response = await fetch(toggleUrl, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        });
                        const data = await response.json();
                        if (data.success) {
                            this.readStatus[id] = data.is_read;
                        }
                    } catch (e) {
                        console.error('Error toggling read status:', e);
                    }
                },

                formatDate(dateStr) {
                    if (!dateStr) return '';
                    const date = new Date(dateStr);
                    return date.toLocaleString('en-US', { 
                        month: 'short', 
                        day: 'numeric', 
                        year: 'numeric',
                        hour: 'numeric',
                        minute: '2-digit',
                        hour12: true
                    });
                }
            }
        }
    </script>
    @endpush
@endsection
