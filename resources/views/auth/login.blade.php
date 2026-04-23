<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - MENU APP</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --brand-red: #c52c24;
            --brand-light-bg: #f4f7fe;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        .login-container {
            min-height: 100vh;
        }

        /* Custom Red Button */
        .btn-brand {
            background-color: var(--brand-red);
            color: white;
            border: none;
            padding: 12px;
            font-weight: 600;
        }

        .btn-brand:hover {
            background-color: #a3241d;
            color: white;
        }

        /* Google Button Style */
        .btn-google {
            background-color: var(--brand-light-bg);
            color: #2d3748;
            font-weight: 500;
            border: none;
        }

        /* Right Side Image Styling with the curve */
        .image-side {
            background-image: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&q=80&w=1470');
            background-size: cover;
            background-position: center;
            border-bottom-left-radius: 200px;
            /* Large curved edge */
            position: relative;
        }

        /* Central Logo Overlay */
        .logo-badge {
            width: 150px;
            height: 150px;
            background-color: var(--brand-red);
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        /* Text colors and fonts */
        .text-brand-red {
            color: var(--brand-red);
        }

        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .footer-text {
            font-size: 0.8rem;
            color: #718096;
        }

        /* Custom Input Styling */
        .form-control {
            border-radius: 12px;
            padding: 12px 15px;
            border-color: #e2e8f0;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(197, 44, 36, 0.1);
            border-color: var(--brand-red);
        }

        .divider:before,
        .divider:after {
            content: "";
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }
    </style>
</head>

<body>

    <div class="container-fluid p-0">
        <div class="row g-0 login-container">

            <!-- Left Side: Login Form -->
            <div class="col-12 col-lg-5 d-flex flex-column justify-content-center px-4 px-md-5 py-5 position-relative">
                <div class="mx-auto" style="max-width: 420px; width: 100%;">

                    <h2 class="fw-bold mb-1">Sign In</h2>
                    <p class="text-muted mb-4">Enter your email and password to sign in!</p>

                    <!-- Google Sign In -->
                    {{-- <button
                        class="btn btn-google w-100 py-3 mb-4 rounded-4 d-flex align-items-center justify-content-center">
                        <i class="bi bi-google me-2"></i>
                        Sign in with Google
                    </button>

                    <div class="divider d-flex align-items-center mb-4 text-muted">
                        <span class="mx-3 small">or</span>
                    </div> --}}

                    <form method="POST" action="{{ route('master.login.submit') }}">
                        @csrf
                        <!-- Email Input -->
                        <div class="mb-3">
                            <label class="form-label">Email<span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="mail@simmmple.com" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
        
                        <!-- Password Input -->
                        <div class="mb-3">
                            <label class="form-label">Password<span class="text-danger">*</span></label>
                            <div class="position-relative">
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Min. 8 characters" required>
                                <i class="bi bi-eye position-absolute top-50 end-0 translate-middle-y me-3 text-muted cursor-pointer" id="togglePassword"></i>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
        
                        <!-- Remember Me & Forgot Password -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="rememberMe" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label small" for="rememberMe" style="color: #2d3748;">
                                    Keep me logged in
                                </label>
                            </div>
                            <a href="{{ route('master.password.request') }}" class="text-brand-red text-decoration-none small fw-bold">Forgot
                                password?</a>
                        </div>
        
                        <!-- Login Button -->
                        <button type="submit" class="btn btn-brand w-100 rounded-4 mb-4 d-flex align-items-center justify-content-center" id="btnSubmit">
                            <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true" id="spinner"></span>
                            <span id="btnText">Login</span>
                        </button>
        
                        <!-- Registration Link -->
                        <p class="small mb-0 text-muted">
                            Not registered yet? <a href="{{ route('master.register') }}"
                                class="text-brand-red text-decoration-none fw-bold">Create an Account</a>
                        </p>
                    </form>
                </div>

                <!-- Footer -->
                <div class="position-absolute bottom-0 start-0 w-100 p-4 text-center text-lg-start ps-lg-5">
                    <p class="footer-text mb-0">© 2026 MENU All Rights Reserved. <a href="https://tukisoft.com.np" class="text-muted">Powered by Tuki Soft pvt. ltd.</a></p>
                </div>
            </div>

            <!-- Right Side: Background Image (Hidden on mobile) -->
            @include('auth.right-side')

        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.querySelector('form').addEventListener('submit', function() {
            const btn = document.getElementById('btnSubmit');
            const spinner = document.getElementById('spinner');
            const btnText = document.getElementById('btnText');
            
            btn.disabled = true;
            spinner.classList.remove('d-none');
            btnText.innerText = 'Signing in...';
        });

        // Password Toggle
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.querySelector('input[name="password"]');

        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.classList.toggle('bi-eye');
                this.classList.toggle('bi-eye-slash');
            });
        }
    </script>
</body>

</html>
