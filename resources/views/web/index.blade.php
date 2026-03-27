<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Saral Menu - Interactive Hero</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Atma:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        atma: ['Atma', 'cursive'],
                        inter: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            red: '#b91c1c',
                            darkRed: '#880a0a',
                            orange: '#f97316',
                            yellow: '#f9a01b',
                            cream: '#fdf2e9',
                            beige: '#f9d7a5',
                            lightBeige: '#fde6b6',
                        }
                    },
                    animation: {
                        'slow-bounce': 'customBounce 3s infinite ease-in-out',
                        'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
                        'scale-in': 'scaleIn 0.6s ease-out forwards',
                        'slide-in-left': 'slideInLeft 0.8s ease-out forwards',
                        'slide-in-right': 'slideInRight 0.8s ease-out forwards',
                    },
                    keyframes: {
                        customBounce: {
                            '0%, 100%': {
                                transform: 'translateY(0)'
                            },
                            '50%': {
                                transform: 'translateY(-20px)'
                            },
                        },
                        fadeInUp: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(30px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            },
                        },
                        scaleIn: {
                            '0%': {
                                opacity: '0',
                                transform: 'scale(0.9)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'scale(1)'
                            },
                        },
                        slideInLeft: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateX(-50px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateX(0)'
                            },
                        },
                        slideInRight: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateX(50px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateX(0)'
                            },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            min-height: 100vh;
            background-image: linear-gradient(rgba(185, 28, 28, 0.45), rgba(136, 10, 10, 0.65)),
                url({{ asset('frontend/images/hero.png') }});
            background-repeat: no-repeat;
            background-size: cover;
            /* ensures the image fills the screen */
            background-attachment: fixed;
            /* keeps the image fixed while scrolling */
            background-position: center;
            /* centers the image */
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #b91c1c;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #880a0a;
        }

        /* Ripple Effect */
        .ripple {
            position: absolute;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            pointer-events: none;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: rippleExpand 3s infinite;
        }

        .ripple:nth-child(1) {
            animation-delay: 0s;
        }

        .ripple:nth-child(2) {
            animation-delay: 1s;
        }

        .ripple:nth-child(3) {
            animation-delay: 2s;
        }

        @keyframes rippleExpand {
            0% {
                width: 100px;
                height: 100px;
                opacity: 0.8;
            }

            100% {
                width: 380px;
                height: 380px;
                opacity: 0;
            }
        }

        /* Dropdown */
        #navDropdown {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: top right;
        }

        .dropdown-hidden {
            opacity: 0;
            transform: scale(0.95) translateY(-10px);
            pointer-events: none;
        }

        /* Scroll reveal */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .reveal-left {
            opacity: 0;
            transform: translateX(-50px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .reveal-right {
            opacity: 0;
            transform: translateX(50px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .reveal-scale {
            opacity: 0;
            transform: scale(0.9);
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .reveal.active,
        .reveal-left.active,
        .reveal-right.active,
        .reveal-scale.active {
            opacity: 1;
            transform: none;
        }

        .stagger-1 {
            transition-delay: 0.1s;
        }

        .stagger-2 {
            transition-delay: 0.2s;
        }

        .stagger-3 {
            transition-delay: 0.3s;
        }

        .stagger-4 {
            transition-delay: 0.4s;
        }

        /* FAQ */
        .faq-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease;
            opacity: 0;
        }

        .faq-content.open {
            max-height: 500px;
            opacity: 1;
        }

        .faq-icon {
            transition: transform 0.3s ease;
        }

        .faq-icon.rotate {
            transform: rotate(45deg);
        }

        /* Button hover */
        .btn-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-hover::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-hover:active::before {
            width: 300px;
            height: 300px;
        }

        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, .1), 0 8px 10px -6px rgba(0, 0, 0, .1);
        }

        .btn-hover:active {
            transform: translateY(0);
        }

        /* Card hover */
        .card-hover {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, .15);
        }

        /* Image hover zoom */
        .img-zoom {
            overflow: hidden;
        }

        .img-zoom img {
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .img-zoom:hover img {
            transform: scale(1.05);
        }

        /* Nav blur */
        .nav-blur {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        /* Touch device: disable hover lifts */
        @media (hover: none) {
            .btn-hover:hover {
                transform: none;
            }

            .card-hover:hover {
                transform: none;
                box-shadow: none;
            }
        }

        /* ─── KEY FIX: hero heading fluid size ─── */
        .hero-heading {
            font-size: clamp(3.5rem, 18vw, 10rem);
            line-height: 0.85;
        }

        /* Features cards: uniform bottom offset on all screens */
        .feature-card-info {
            position: absolute;
            bottom: -2.5rem;
            /* 40px, fixed */
        }

        /* ─── FEATURES SECTION: extra bottom padding for info boxes ─── */
        #features .cards-grid {
            padding-bottom: 5rem;
            /* space for overlapping info boxes */
        }

        /* Reduced motion */
        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }

            .reveal,
            .reveal-left,
            .reveal-right,
            .reveal-scale {
                opacity: 1;
                transform: none;
            }
        }

        *:focus-visible {
            outline: 2px solid #f97316;
            outline-offset: 2px;
        }

        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body class="font-inter text-white overflow-x-hidden antialiased">

    @include('web.hero')


    @include('web.features')


    @include('web.how-it-works')

    <!-- ═══════════════ PRICING ═══════════════ -->
    <section id="pricing"
        class="relative bg-brand-lightBeige py-16 sm:py-20 md:py-24 px-5 sm:px-8 md:px-12 lg:px-20 overflow-hidden text-gray-900">
        <div class="absolute top-0 left-0 w-full h-full pointer-events-none opacity-40">
            <svg class="absolute top-[-10%] right-[-10%] w-[60%] h-[60%] text-[#f8e3c3]" viewBox="0 0 200 200"
                fill="currentColor">
                <path
                    d="M45.7,-77.4C58.1,-70.5,66.4,-55.9,73.1,-41.2C79.8,-26.5,84.9,-11.7,83.2,2.5C81.5,16.7,73,30.3,64.2,43.7C55.4,57.1,46.3,70.3,34,76.5C21.7,82.7,6.2,81.9,-9.2,78.7C-24.6,75.5,-39.9,69.9,-52.3,60.5C-64.7,51.1,-74.2,37.9,-79.8,23.3C-85.4,8.7,-87.1,-7.3,-82.9,-21.9C-78.7,-36.5,-68.6,-49.7,-55.9,-56.5C-43.2,-63.3,-27.9,-63.7,-13.4,-65.7C1.1,-67.7,15.6,-71.3,45.7,-77.4Z"
                    transform="translate(100 100)" />
            </svg>
        </div>

        <div class="max-w-5xl mx-auto relative z-10">
            <div class="text-center mb-10 sm:mb-14 reveal">
                <h2 class="font-atma font-bold text-3xl sm:text-4xl md:text-5xl text-center text-brand-red mb-2 sm:mb-4 reveal">Simple
                    Pricing</h2>
                <p class="font-inter text-brand-red text-base sm:text-lg font-medium">Start free. Upgrade when you're
                    ready.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8 lg:gap-12">

                <!-- Starter -->
                <div
                    class="bg-brand-beige border border-[#e5c496] rounded-[2rem] p-7 sm:p-10 md:p-12 flex flex-col shadow-sm card-hover reveal-left">
                    <span class="font-inter font-bold text-base sm:text-lg uppercase tracking-wider mb-3">Starter</span>
                    <h3 class="font-inter font-bold text-5xl sm:text-6xl mb-8">Free</h3>
                    <ul class="space-y-4 mb-10 flex-grow">
                        <li class="flex items-center gap-3 text-base sm:text-lg font-medium"><i
                                class='bx bx-check text-brand-red text-2xl flex-shrink-0'></i>One QR code</li>
                        <li class="flex items-center gap-3 text-base sm:text-lg font-medium"><i
                                class='bx bx-check text-brand-red text-2xl flex-shrink-0'></i>Basic analytics</li>
                    </ul>
                    <button
                        class="btn-hover w-full py-3.5 bg-white text-gray-900 font-bold text-base sm:text-lg rounded-xl shadow-md touch-manipulation">Start
                        Free</button>
                </div>

                <!-- Pro -->
                <div
                    class="bg-brand-beige border-2 border-brand-red rounded-[2rem] p-7 sm:p-10 md:p-12 flex flex-col shadow-sm card-hover relative reveal-right">
                    <div
                        class="absolute -top-3.5 right-6 bg-brand-red text-white text-xs font-bold px-4 py-1 rounded-full shadow-lg animate-pulse">
                        POPULAR</div>
                    <span class="font-inter font-bold text-base sm:text-lg uppercase tracking-wider mb-3">Pro</span>
                    <div class="mb-8">
                        <span class="font-inter font-bold text-brand-red text-4xl sm:text-5xl">NPR 1,000</span>
                        <span class="font-inter text-brand-red text-base font-medium opacity-80">/ month</span>
                    </div>
                    <ul class="space-y-4 mb-10 flex-grow">
                        <li class="flex items-center gap-3 text-base sm:text-lg font-medium"><i
                                class='bx bx-check text-brand-red text-2xl flex-shrink-0'></i>Table-specific QRs</li>
                        <li class="flex items-center gap-3 text-base sm:text-lg font-medium"><i
                                class='bx bx-check text-brand-red text-2xl flex-shrink-0'></i>Order tracking + payments
                        </li>
                        <li class="flex items-center gap-3 text-base sm:text-lg font-medium"><i
                                class='bx bx-check text-brand-red text-2xl flex-shrink-0'></i>Priority support</li>
                    </ul>
                    <button
                        class="btn-hover w-full py-3.5 bg-brand-red text-white font-bold text-base sm:text-lg rounded-xl shadow-md hover:bg-[#a32626] touch-manipulation">Go
                        Pro</button>
                </div>
            </div>
        </div>
    </section>

    @include('web.testimonials')

    @include('web.faq')

    @include('web.above-footer')

    @include('web.footer')

    <!-- Toast -->
    <div id="toast"
        class="fixed bottom-5 right-5 bg-green-500 text-white px-5 py-3.5 rounded-xl shadow-2xl
                transform translate-y-20 opacity-0 transition-all duration-300 z-50
                font-inter font-medium text-sm flex items-center gap-2 max-w-[calc(100vw-2.5rem)]">
        <i class='bx bx-check-circle text-xl flex-shrink-0'></i>
        <span id="toastMessage">Success!</span>
    </div>

    @include('web.scripts')
</body>

</html>
