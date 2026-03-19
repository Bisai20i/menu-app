@php
    $is_super_admin = auth()->user()->is_super_admin;
@endphp
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo mb-2">

        <span class="d-flex align-items-center h-100">
            <img src="{{ asset('logo.png') }}" alt="logo" class="w-100 h-100">
            <p class="fw-bold display-5 mb-0 text-primary">MENU</p>
        </span>


        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item {{ request()->routeIs('master.dashboard') ? 'active' : '' }}">
            <a href="{{ route('master.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Menu Management</span>
        </li>
        <!-- Order Management -->
        {{-- <li class="menu-item {{ request()->routeIs('master.orders.*') ? 'active' : '' }}">
            <a href="{{ route('master.orders.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-receipt"></i>
                <div data-i18n="Orders">Orders</div>
            </a>
        </li> --}}
        @can('accessCreateMenu')
            <!-- Menu Categories -->
            <li class="menu-item {{ request()->is('*master/menu-categories*') ? 'active' : '' }}">
                <a href="{{ route('master.menu-categories.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-category"></i>
                    <div data-i18n="Menu Categories">Categories</div>
                </a>
            </li>

            <!-- Menu Items -->
            <li class="menu-item {{ request()->is('*master/menu-items*') ? 'active' : '' }}">
                <a href="{{ route('master.menu-items.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-food-menu"></i>
                    <div data-i18n="Menu Items">Menu Items</div>
                </a>
            </li>
        @endcan
        <!-- Menu Gallery -->
        <li class="menu-item {{ request()->routeIs('master.menu-gallery.*') ? 'active' : '' }}">
            <a href="{{ route('master.menu-gallery.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-image"></i>
                <div data-i18n="Gallery">Gallery</div>
            </a>
        </li>

        @can('accessCreateRestaurantTable')
            <!-- Restaurant Tables -->
            <li class="menu-item {{ request()->routeIs('master.restaurant-tables.*') ? 'active' : '' }}">
                <a href="{{ route('master.restaurant-tables.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-table"></i>
                    <div data-i18n="Tables">Restaurant Tables</div>
                </a>
            </li>

            <!-- Table QR Codes -->
            <li class="menu-item {{ request()->routeIs('master.table-qr') ? 'active' : '' }}">
                <a href="{{ route('master.table-qr') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-qr"></i>
                    <div data-i18n="QR Codes">Table QR Codes</div>
                </a>
            </li>
        @endcan

        @if ($is_super_admin)
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">System Management</span>
            </li>
            <li class="menu-item {{ request()->routeIs('master.testimonials*') ? 'active' : '' }}">
                <a href="{{ route('master.testimonials.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-message-dots"></i>
                    <div data-i18n="Testimonials">Testimonials</div>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('master.faqs*') ? 'active' : '' }}">
                <a href="{{ route('master.faqs.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-question-mark"></i>
                    <div data-i18n="Faqs">Faqs</div>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('master.subscription-plans*') ? 'active' : '' }}">
                <a href="{{ route('master.subscription-plans.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-money"></i>
                    <div data-i18n="Subscription Plans">Subscription Plans</div>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('master.admin-management.*') ? 'active' : '' }}">
                <a href="{{ route('master.admin-management.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-group"></i>
                    <div data-i18n="Admin Management">Admin Management</div>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('master.admin-subscriptions*') ? 'active' : '' }}">
                <a href="{{ route('master.admin-subscriptions.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user-check"></i>
                    <div data-i18n="Admin Subscriptions">Assign Subscriptions</div>
                </a>
            </li>
        @endif

        <!-- Billing -->
        <li class="menu-item {{ request()->routeIs('master.billing') ? 'active' : '' }}">
            <a href="{{ route('master.billing') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-receipt"></i>
                <div data-i18n="Billing">Billing</div>
            </a>
        </li>

        <!-- Profile -->

        <li class="menu-item {{ request()->routeIs('master.profile') ? 'active' : '' }}">
            <a href="{{ route('master.profile') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Profile">Manage Profile</div>
            </a>
        </li>

        <!-- Logout -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link border-0 bg-transparent w-100 text-start"
                data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class="menu-icon tf-icons bx bx-power-off"></i>
                <div data-i18n="Logout">Logout</div>
            </a>
        </li>
    </ul>
</aside>
