<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('backend/assets/') }}" data-template="vertical-menu-template-free">


<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>@stack('title')</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />

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

    <!-- Helpers -->
    <script src="{{ asset('backend/assets/vendor/js/helpers.js') }}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('backend/assets/js/config.js') }}"></script>
    <!-- Include Bootstrap Icons CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>

<body>
    <!-- Layout wrapper -->

    @if (session()->has('success') || session()->has('error') || session()->has('info'))
        <div class="position-absolute toast-container top-3 end-0 p-3" style="z-index: 5000">
            @foreach (['success', 'error', 'info'] as $type)
                @if (session()->has($type))
                    <div class="bs-toast toast fade show bg-{{ $type == 'error' ? 'danger' : $type }}" role="alert"
                        aria-live="assertive" aria-atomic="true">
                        <div class="toast-header">
                            <i
                                class="bx 
                                                          @if ($type == 'success') bx-check-circle 
                                                          @elseif($type == 'error') bx-error 
                                                          @else bx-info-circle @endif 
                                                          me-2"></i>
                            <div class="me-auto fw-semibold text-capitalize">
                                {{ $type == 'error' ? 'Error' : ucfirst($type) }}
                            </div>
                            {{-- <small class="text-muted">{{ now()->diffForHumans() }}</small> --}}
                            <button type="button" class="btn-close" data-bs-dismiss="toast"
                                aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                            {{ session($type) }}
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif


    <div class="flex-grow-1 p-0">
        <!-- Layout Demo -->
        @yield('content')

        <!--/ Layout Demo -->
    </div>



    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('backend/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('backend/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('backend/assets/vendor/js/bootstrap.js') }}"></script>
    {{-- <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script> --}}

    <script src="{{ asset('backend/assets/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="{{ asset('backend/assets/js/main.js') }}"></script>

    @stack('scripts')

</body>

</html>
