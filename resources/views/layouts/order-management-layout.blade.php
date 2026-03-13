<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('backend/assets') }}" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>{{ $title ?? config('app.name') }}</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/css/core.css') }}"
        class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/css/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('backend/assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('backend/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="{{ asset('backend/assets/vendor/js/helpers.js') }}"></script>

    <script src="{{ asset('backend/assets/js/config.js') }}"></script>

    <style>
        .btn-link.active {
            font-weight: 700;
            background-color: rgba(var(--bs-primary-rgb), 0.1);
            /* 10% opacity */
        }

        .btn-link:hover {
            font-weight: 700;
            background-color: rgba(var(--bs-primary-rgb), 0.1);
        }
    </style>
    @livewireStyles

    @stack('styles')

</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper">
        <div class="layout-container">
            <!-- Layout container -->
            <div class="w-100 h-100">
                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Header with Return Button -->
                    <div
                        class="container-fluid bg-white flex-wrap-reverse gap-2 py-2 py-md-3 d-flex justify-content-between align-items-center">

                        <div class="d-flex align-items-center">
                            <a href="{{ route('master.dashboard') }}" class="text-secondary ">
                                <i class="bx bx-chevron-left fs-1 me-1"></i>
                            </a>
                            <div>
                                <h4 class="fw-bold mb-0">Order Management</h4>
                                <p class="text-muted small mb-0">Real-time overview of all table orders</p>
                            </div>

                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            <ul class="navbar-nav flex-row align-items-center ms-auto">
                                <!-- Place this tag where you want the button to render. -->
                                <li class="nav-item lh-1 me-1">
                                    <a class="btn btn-link {{ request()->routeIs('master.orders.index') ? 'active' : '' }}" 
                                        href="{{ route('master.orders.index') }}">
                                        Order Management
                                    </a>
                                </li>

                                <li class="nav-item lh-1 me-1">
                                    <a class="btn btn-link {{ request()->routeIs('master.table-sessions') ? 'active' : '' }}" 
                                        href="{{ route('master.table-sessions') }}">
                                        Table Sessions
                                    </a>
                                </li>

                                @if (!request()->routeIs('master.notifications.index'))
                                    <!-- Notification Bell -->
                                    <li class="nav-item lh-1 me-1">
                                        <livewire:admin.notification-bell lazy />
                                    </li>
                                @endif

                            </ul>

                        </div>

                    </div>

                    <!-- Content -->
                    <div id="page-content" class="container-fluid flex-grow-1 py-3 py-md-4" style="min-height: 85vh;">
                        {{ $slot }}
                    </div>
                    <!-- / Content -->

                    @include('layouts.partials.footer')

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>


    </div>

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    {{-- <script src="{{ asset('backend/assets/vendor/libs/jquery/jquery.js') }}"></script> --}}
    <script src="{{ asset('backend/assets/vendor/js/bootstrap.js') }}"></script>
    {{-- <script src="{{ asset('backend/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script> --}}


    <!-- Main JS -->
    {{-- <script data-navigate-once src="{{ asset('backend/assets/js/main.js') }}"></script> --}}

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer data-navigate-once src="https://buttons.github.io/buttons.js"></script>

    @stack('scripts')
    @livewireScripts

</body>

</html>
