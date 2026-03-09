<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - MENU</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --brand-red: #c52c24;
            --brand-light-bg: #f4f7fe;
            --text-main: #2d3748;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            color: var(--text-main);
        }

        .signup-container {
            min-height: 100vh;
        }

        /* Custom Red Button */
        .btn-brand {
            background-color: var(--brand-red);
            color: white;
            border: none;
            padding: 14px;
            font-weight: 600;
            transition: 0.3s;
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
            padding: 12px;
        }

        /* Right Side Image Styling with the large curve */
        .image-side {
            background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&q=80&w=1470');
            background-size: cover;
            background-position: center;
            border-bottom-left-radius: 250px; /* Signature curve */
            position: relative;
        }

        /* Central Logo Overlay */
        .logo-badge {
            width: 160px;
            height: 160px;
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
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        }

        /* Form labels and links */
        .text-brand-red { color: var(--brand-red); }
        .form-label { font-weight: 600; font-size: 0.85rem; margin-bottom: 8px; }
        .footer-text { font-size: 0.75rem; color: #a3aed0; }
        
        /* Custom Input Styling */
        .form-control {
            border-radius: 12px;
            padding: 12px 16px;
            border-color: #e2e8f0;
            font-size: 0.9rem;
        }
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(197, 44, 36, 0.1);
            border-color: var(--brand-red);
        }

        /* Visual Separator "or" */
        .divider:before, .divider:after {
            content: "";
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        /* Checkbox color */
        .form-check-input:checked {
            background-color: var(--brand-red);
            border-color: var(--brand-red);
        }
    </style>
</head>
<body>

<div class="container-fluid p-0">
    <div class="row g-0 signup-container">
        
        <!-- Left Side: Sign Up Form -->
        <div class="col-12 col-lg-5 d-flex flex-column justify-content-center px-4 px-md-5 py-5 position-relative">
            <div class="mx-auto" style="max-width: 400px; width: 100%;">
                
                <h2 class="fw-bold mb-1" style="font-size: 2.2rem;">Sign Up</h2>
                <p class="text-muted mb-4">Enter your email and password to sign up!</p>

                <!-- Google Sign In -->
                <button class="btn btn-google w-100 rounded-4 mb-4 d-flex align-items-center justify-content-center">
                    <i class="bi bi-google me-2"></i>
                    <span style="font-size: 0.9rem;">Sign in with Google</span>
                </button>

                <div class="divider d-flex align-items-center mb-4 text-muted">
                    <span class="mx-3 small text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">or</span>
                </div>

                <form method="POST" action="{{ route('master.register.submit') }}">
                    @csrf
                    <!-- Name Input -->
                    <div class="mb-3">
                        <label class="form-label">Name<span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter Your Name" value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email Input -->
                    <div class="mb-3">
                        <label class="form-label">Email<span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="mail@simmmple.com" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div class="mb-3">
                        <label class="form-label">Password<span class="text-danger">*</span></label>
                        <div class="position-relative">
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Min. 8 characters" required>
                            <i class="bi bi-eye position-absolute top-50 end-0 translate-middle-y me-3 text-muted" style="cursor: pointer;" id="togglePassword"></i>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Confirm Password Input -->
                    <div class="mb-4">
                        <label class="form-label">Confirm Password<span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm your password" required>
                    </div>

                    <!-- Terms Checkbox -->
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input me-2 @error('terms') is-invalid @enderror" type="checkbox" name="terms" id="terms" required>
                            <label class="form-check-label small" for="terms">
                                I Agree to the <a href="#" class="text-dark text-decoration-underline">terms & policy</a>
                            </label>
                            @error('terms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Signup Button -->
                    <button type="submit" class="btn btn-brand w-100 rounded-4 mb-4 d-flex align-items-center justify-content-center" id="btnSubmit">
                        <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true" id="spinner"></span>
                        <span id="btnText">Signup</span>
                    </button>

                    <!-- Login Redirect -->
                    <p class="small mb-0">
                        Have an account? <a href="{{ route('master.login') }}" class="text-brand-red text-decoration-none fw-bold">Sign In</a>
                    </p>
                </form>
            </div>

            <!-- Sticky Footer at bottom left -->
            <div class="mt-5 pt-4 text-center text-lg-start">
                <p class="footer-text mb-0">© 2026 MENU All Rights Reserved. <a href="https://tukisoft.com.np" class="text-muted">Powered by Tuki Soft pvt. ltd.</a></p>
            </div>
        </div>

        <!-- Right Side: Decorative Image (Hidden on smaller screens) -->
        <div class="col-lg-7 d-none d-lg-block image-side">
            <!-- Central Logo Badge -->
            <div class="logo-badge">
                <div class="text-center">
                    <!-- Custom Flame/Fork Icon Representation -->
                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 55px; height: 55px;">
                         <i class="bi bi-fire text-danger fs-3"></i>
                    </div>
                    <h3 class="fw-bold mb-0" style="letter-spacing: 1px;">MENU</h3>
                    <p class="mb-0 fw-bold" style="font-size: 8px; letter-spacing: 2px;">HANOVER AND TYKE</p>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.querySelector('form').addEventListener('submit', function() {
        const btn = document.getElementById('btnSubmit');
        const spinner = document.getElementById('spinner');
        const btnText = document.getElementById('btnText');
        
        btn.disabled = true;
        spinner.classList.remove('d-none');
        btnText.innerText = 'Creating account...';
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