<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('backend/assets') }}" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>@stack('title')</title>

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
    @livewireStyles

    @stack('styles')
</head>

<body>
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
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            @include('layouts.partials.sidebar')

            <!-- Layout container -->
            <div class="layout-page">
                @include('layouts.partials.navbar')

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->

                    <div class="container-fluid flex-grow-1 container-p-y">

                        @yield('content')


                    </div>
                    <!-- / Content -->

                    @include('layouts.partials.footer')

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Logout Confirmation Modal -->
        <x-modal id="logoutModal" title="Confirm Logout">
            <div class="text-center py-3">
                <div class="mb-4">
                    <i class="bx bx-log-out-circle text-warning opacity-75" style="font-size: 5rem;"></i>
                </div>
                <h5 class="fw-bold mb-2">Ready to Leave?</h5>
                <p class="text-muted small px-4">Are you sure you want to end your current session? You will need to log back in to access the admin panel.</p>
            </div>
            <x-slot name="footer">
                <div class="d-flex w-100 gap-2">
                    <button type="button" class="btn btn-label-secondary flex-grow-1" data-bs-dismiss="modal">Stay Logged In</button>
                    <form action="{{ route('master.logout') }}" method="POST" class="flex-grow-1">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100">Yes, Logout</button>
                    </form>
                </div>
            </x-slot>
        </x-modal>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('backend/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('backend/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('backend/assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('backend/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('backend/assets/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->


    <!-- Main JS -->
    <script src="{{ asset('backend/assets/js/main.js') }}"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    @stack('scripts')
    @livewireScripts

    <script>
        $(document).ready(function() {
            // Auto-dismiss toasts after 6 seconds
            $('.bs-toast').each(function() {
                var toast = new bootstrap.Toast(this);
                toast.show(); // Ensure the toast is shown before hiding
                setTimeout(function() {
                    toast.hide();
                }, 6000); // 6000 milliseconds = 6 seconds
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            function setLoading(el) {
                el.setAttribute("disabled", "true");
                el.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            Processing...
        `;
            }

            // Handle redirect links
            document.querySelectorAll(".redirect-link").forEach(link => {
                link.addEventListener("click", function(e) {
                    e.preventDefault();
                    setLoading(this);
                    // Redirect after short delay
                    setTimeout(() => {
                        window.location.href = this.href;
                    }, 400);
                });
            });

            // Handle form submit buttons
            document.querySelectorAll("form").forEach(form => {
                form.addEventListener("submit", function() {
                    const btn = form.querySelector("button[type=submit]");
                    if (btn) setLoading(btn);
                });
            });
        });
    </script>

</body>

</html>
