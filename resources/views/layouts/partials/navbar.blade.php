{{-- Mobile-Responsive Navbar --}}

<style>
    /* ── Base navbar tweaks ── */
    #layout-navbar {
        position: sticky;
        top: 0;
        z-index: 1030;
        padding: 0.5rem 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    /* ── Search input ── */
    .navbar-search-wrapper {
        display: flex;
        align-items: center;
        position: relative;
        background: white;
        border-radius: 8px;
        padding: 0.3rem 0.75rem;
        flex: 1;
        max-width: 320px;
        transition: box-shadow 0.2s;
    }

    /* .navbar-search-wrapper:focus-within {
        box-shadow: 0 0 0 3px rgba(105, 108, 255, 0.2);
    } */

    .navbar-search-wrapper .bx-search {
        color: var(--bs-secondary, #6c757d);
        flex-shrink: 0;
    }

    .navbar-search-wrapper input {
        background: transparent;
        border: none !important;
        box-shadow: none !important;
        padding: 0 0.5rem;
        font-size: 0.875rem;
        width: 100%;
    }

    .navbar-search-wrapper input:focus {
        outline: none;
    }

    /* ── Global search dropdown ── */
    .global-search-results {
        position: absolute;
        top: calc(100% + 0.35rem);
        left: 0;
        right: 0;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 12px 34px rgba(0, 0, 0, 0.12);
        border: 1px solid rgba(0, 0, 0, 0.06);
        z-index: 2000;
        display: none;
        max-height: 360px;
        overflow-y: auto;
        padding: 0.35rem 0;
    }

    .global-search-result-item {
        display: block;
        padding: 0.55rem 0.85rem;
        font-size: 0.875rem;
        text-decoration: none;
        color: inherit;
    }

    .global-search-result-item:hover {
        background: rgba(105, 108, 255, 0.08);
    }

    .global-search-empty {
        padding: 0.75rem 0.85rem;
        font-size: 0.85rem;
        color: var(--bs-secondary, #6c757d);
    }

    /* ── Right-side icons ── */
    .navbar-nav-right {
        width: 100%;
        gap: 0.5rem;
    }

    /* ── Avatar ── */
    .avatar img {
        object-fit: cover;
    }

    /* ── Dropdown polish ── */
    .dropdown-menu {
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        border: 1px solid rgba(0, 0, 0, 0.06);
        min-width: 220px;
        padding: 0.5rem 0;
        animation: dropFade 0.15s ease;
    }

    @keyframes dropFade {
        from { opacity: 0; transform: translateY(-6px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .dropdown-item {
        padding: 0.6rem 1rem;
        font-size: 0.875rem;
        border-radius: 6px;
        margin: 0 0.25rem;
        width: calc(100% - 0.5rem);
        transition: background 0.15s;
    }

    /* ── Mobile (≤ 767px) ── */
    @media (max-width: 767.98px) {
        #layout-navbar {
            padding: 0.5rem 0.75rem;
        }

        /* Stack: [hamburger] [search] [icons] */
        .navbar-nav-right {
            flex-wrap: nowrap;
            align-items: center;
        }

        /* Search stretches to fill available space */
        .navbar-search-wrapper {
            max-width: 100%;
            flex: 1 1 auto;
        }

        /* Hide "Order Management" text button on very small screens,
           show a compact icon-only version instead */
        /* Hide buttons on very small screens */
        .btn-order-full, .btn-history-full { display: none !important; }
        .btn-order-icon, .btn-history-icon { display: inline-flex !important; }

        /* Tighten spacing between icon items */
        .navbar-nav.flex-row { gap: 0.25rem; }

        /* Ensure dropdown opens upward if near bottom */
        .dropdown-menu-end { right: 0; left: auto; }
    }

    /* ── Tablet (768 – 1199px) ── */
    @media (min-width: 768px) and (max-width: 1199.98px) {
        .navbar-search-wrapper { max-width: 220px; }
        .btn-order-full  { display: inline-flex !important; }
        .btn-order-icon  { display: none !important; }
    }

    /* ── Desktop (≥ 1200px) ── */
    @media (min-width: 1200px) {
        .btn-order-full, .btn-history-full { display: inline-flex !important; }
        .btn-order-icon, .btn-history-icon { display: none !important; }
    }

    /* Icon-only buttons (mobile) */
    .btn-order-icon, .btn-history-icon {
        display: none;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        padding: 0;
    }

    /* ── Notification bell hit-target ── */
    .nav-item .bx-bell {
        font-size: 1.25rem;
    }

    /* ── Online indicator ── */
    .avatar-online::after {
        bottom: 2px;
        right: 2px;
        width: 10px;
        height: 10px;
    }
</style>

<!-- Navbar -->
<nav class="layout-navbar container-fluid navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">

    {{-- ── Hamburger (visible below xl) ── --}}
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-2 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0" href="javascript:void(0)" aria-label="Toggle sidebar menu">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    {{-- ── Main content row ── --}}
    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

        @php
            $user = auth('admin')->user();
            $searchItems = \App\Helpers\GlobalSearchHelper::itemsForAdmin($user);
            $searchEnabled = !empty($searchItems);
        @endphp

        {{-- Search --}}
        @if ($searchEnabled)
            <div class="navbar-search-wrapper me-2" id="global-search-wrapper">
                <i class="bx bx-search fs-5 lh-0"></i>
                <input id="global-search-input"
                       type="text"
                       class="form-control"
                       placeholder="Search pages…"
                       aria-label="Search pages"
                       autocomplete="off"
                       spellcheck="false" />
                <div id="global-search-results"
                     class="global-search-results"
                     role="listbox"
                     aria-label="Global search results"></div>
            </div>
        @endif
        {{-- /Search --}}

        @php
            $canManageOrder = ($user->hasActiveSubscription() || $user->is_super_admin) && isset($user->restaurant_id);
        @endphp

        <ul class="navbar-nav flex-row align-items-center ms-auto gap-1">

            {{-- ── Order Management ── --}}
            @if ($canManageOrder)
                {{-- Orders Button --}}
                <li class="nav-item lh-1">
                    <a class="btn btn-outline-primary btn-order-full d-flex align-items-center justify-content-center"
                       href="{{ route('master.orders.index') }}" title="Real-time Orders">
                        <i class="bx bx-list-ul me-1"></i> Order Management
                    </a>
                    <a class="btn btn-outline-primary btn-order-icon"
                       href="{{ route('master.orders.index') }}"
                       title="Order Management">
                        <i class="bx bx-list-ul"></i>
                    </a>
                </li>

                {{-- History Button --}}
                <li class="nav-item lh-1">
                    <a class="btn btn-outline-secondary btn-history-full d-flex align-items-center justify-content-center"
                       href="{{ route('master.order-history.index') }}" title="Order History">
                        <i class="bx bx-history me-1"></i> History
                    </a>
                    <a class="btn btn-outline-secondary btn-history-icon"
                       href="{{ route('master.order-history.index') }}"
                       title="Order History">
                        <i class="bx bx-history"></i>
                    </a>
                </li>
            @endif

            {{-- ── Notification Bell ── --}}
            @if (!request()->routeIs('master.notifications.index'))
                <li class="nav-item lh-1">
                    <livewire:admin.notification-bell lazy />
                </li>
            @endif

            {{-- ── User Dropdown ── --}}
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-1"
                   href="javascript:void(0);"
                   data-bs-toggle="dropdown"
                   aria-expanded="false"
                   aria-label="User menu">
                    <div class="avatar avatar-online">
                        <img src="{{ auth('admin')->user()->image_url }}"
                             alt="{{ auth('admin')->user()->name }}"
                             class="w-px-40 h-auto rounded-circle" />
                    </div>
                </a>

                <ul class="dropdown-menu dropdown-menu-end">
                    {{-- Profile header --}}
                    <li>
                        <a class="dropdown-item" href="{{ route('master.profile') }}">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ auth('admin')->user()->image_url }}"
                                             alt="{{ auth('admin')->user()->name }}"
                                             class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <span class="fw-semibold d-block text-truncate">
                                        {{ auth('admin')->user()->name }}
                                    </span>
                                    <small class="text-muted">
                                        {{ ucfirst(auth('admin')->user()->role) }}
                                    </small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li><div class="dropdown-divider"></div></li>

                    {{-- Manage Profile --}}
                    <li>
                        <a class="dropdown-item" href="{{ route('master.profile') }}">
                            <i class="bx bx-user me-2"></i>
                            <span class="align-middle">Manage Profile</span>
                        </a>
                    </li>

                    {{-- Billing --}}
                    <li>
                        <a class="dropdown-item" href="{{ route('master.billing') }}">
                            <i class="bx bx-credit-card me-2"></i>
                            <span class="align-middle">Billing</span>
                        </a>
                    </li>

                    <li><div class="dropdown-divider"></div></li>

                    {{-- Log Out --}}
                    <li>
                        <a href="javascript:void(0);"
                           class="dropdown-item text-danger"
                           data-bs-toggle="modal"
                           data-bs-target="#logoutModal">
                            <i class="bx bx-power-off me-2"></i>
                            <span class="align-middle">Log Out</span>
                        </a>
                    </li>
                </ul>
            </li>
            {{-- /User --}}

        </ul>
    </div>
</nav>
<!-- / Navbar -->

@if ($searchEnabled)
    @push('scripts')
        <script>
            (() => {
                const input = document.getElementById('global-search-input');
                if (!input) return;

                const wrapper = document.getElementById('global-search-wrapper');
                const resultsEl = document.getElementById('global-search-results');
                const items = @json($searchItems);

                let debounceTimer = null;
                let lastMatches = [];

                const clearResults = () => {
                    if (!resultsEl) return;
                    resultsEl.innerHTML = '';
                    resultsEl.style.display = 'none';
                };

                const tokenize = (q) => q
                    .trim()
                    .toLowerCase()
                    .split(/\s+/)
                    .filter(Boolean);

                const scoreItem = (item, q, tokens) => {
                    const label = (item.label || '').toLowerCase();
                    const keywords = (item.keywords || '').toLowerCase();

                    if (label === q) return 100;
                    if (label.startsWith(q)) return 85;
                    if (keywords.includes(q)) return 55;

                    // Multi-token match: all tokens must be present in label/keywords.
                    if (!tokens.length) return 0;
                    let hitCount = 0;
                    for (const t of tokens) {
                        if (label.includes(t) || keywords.includes(t)) hitCount++;
                    }
                    return hitCount === tokens.length ? 40 + hitCount : 0;
                };

                const render = (matches, q) => {
                    if (!resultsEl) return;

                    resultsEl.innerHTML = '';

                    if (!q) {
                        clearResults();
                        return;
                    }

                    if (!matches.length) {
                        const empty = document.createElement('div');
                        empty.className = 'global-search-empty';
                        empty.textContent = 'No matching pages.';
                        resultsEl.appendChild(empty);
                        resultsEl.style.display = 'block';
                        return;
                    }

                    for (const match of matches) {
                        const a = document.createElement('a');
                        a.href = match.url;
                        a.className = 'global-search-result-item';
                        a.textContent = match.label;
                        resultsEl.appendChild(a);
                    }

                    resultsEl.style.display = 'block';
                };

                input.addEventListener('input', () => {
                    if (debounceTimer) clearTimeout(debounceTimer);

                    debounceTimer = setTimeout(() => {
                        const q = input.value || '';
                        const qTrim = q.trim();
                        if (!qTrim) {
                            lastMatches = [];
                            clearResults();
                            return;
                        }

                        const qLower = qTrim.toLowerCase();
                        const tokens = tokenize(qLower);

                        const matches = items
                            .map((item) => ({
                                item,
                                score: scoreItem(item, qLower, tokens)
                            }))
                            .filter((x) => x.score > 0)
                            .sort((a, b) => b.score - a.score)
                            .slice(0, 8)
                            .map((x) => x.item);

                        lastMatches = matches;
                        render(matches, qLower);
                    }, 150);
                });

                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' && lastMatches.length > 0) {
                        e.preventDefault();
                        window.location.href = lastMatches[0].url;
                    }
                    if (e.key === 'Escape') {
                        clearResults();
                    }
                });

                document.addEventListener('click', (e) => {
                    if (!wrapper || !resultsEl) return;
                    if (!wrapper.contains(e.target)) {
                        clearResults();
                    }
                });
            })();
        </script>
    @endpush
@endif