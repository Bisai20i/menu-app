<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>@yield('title', 'Saral Menu - Interactive Hero')</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('meta_description', 'Interactive QR code menu for restaurants. Scan, Order, and Pay seamlessly with Saral Menu.')">
    <meta name="keywords" content="@yield('meta_keywords', 'qr menu, digital menu, restaurant order system, saral menu, smart menu')">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('meta_title', 'Saral Menu - Digitalize your Restaurant')">
    <meta property="og:description" content="@yield('meta_description', 'Interactive QR code menu for restaurants. Scan, Order, and Pay seamlessly with Saral Menu.')">
    <meta property="og:image" content="@yield('meta_image', asset('frontend/images/logo.png'))">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('meta_title', 'Saral Menu - Digitalize your Restaurant')">
    <meta property="twitter:description" content="@yield('meta_description', 'Interactive QR code menu for restaurants. Scan, Order, and Pay seamlessly with Saral Menu.')">
    <meta property="twitter:image" content="@yield('meta_image', asset('frontend/images/logo.png'))">

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
            background-attachment: fixed;
            background-position: center;
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

        .ripple:nth-child(1) { animation-delay: 0s; }
        .ripple:nth-child(2) { animation-delay: 1s; }
        .ripple:nth-child(3) { animation-delay: 2s; }

        @keyframes rippleExpand {
            0% { width: 100px; height: 100px; opacity: 0.8; }
            100% { width: 380px; height: 380px; opacity: 0; }
        }

        #navDropdown {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: top right;
        }

        .dropdown-hidden {
            opacity: 0;
            transform: scale(0.95) translateY(-10px);
            pointer-events: none;
        }

        .reveal { opacity: 0; transform: translateY(30px); transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1); }
        .reveal-left { opacity: 0; transform: translateX(-50px); transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1); }
        .reveal-right { opacity: 0; transform: translateX(50px); transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1); }
        .reveal-scale { opacity: 0; transform: scale(0.9); transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1); }

        .reveal.active, .reveal-left.active, .reveal-right.active, .reveal-scale.active {
            opacity: 1;
            transform: none;
        }

        .stagger-1 { transition-delay: 0.1s; }
        .stagger-2 { transition-delay: 0.2s; }
        .stagger-3 { transition-delay: 0.3s; }
        .stagger-4 { transition-delay: 0.4s; }

        .faq-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease;
            opacity: 0;
        }

        .faq-content.open { max-height: 500px; opacity: 1; }
        .faq-icon { transition: transform 0.3s ease; }
        .faq-icon.rotate { transform: rotate(45deg); }

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

        .btn-hover:active::before { width: 300px; height: 300px; }
        .btn-hover:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(0, 0, 0, .1), 0 8px 10px -6px rgba(0, 0, 0, .1); }
        .btn-hover:active { transform: translateY(0); }

        .card-hover { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        .card-hover:hover { transform: translateY(-8px); box-shadow: 0 20px 40px -10px rgba(0, 0, 0, .15); }

        .img-zoom { overflow: hidden; }
        .img-zoom img { transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1); }
        .img-zoom:hover img { transform: scale(1.05); }

        .nav-blur { backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }

        @media (hover: none) {
            .btn-hover:hover { transform: none; }
            .card-hover:hover { transform: none; box-shadow: none; }
        }

        .hero-heading { font-size: clamp(3.5rem, 18vw, 10rem); line-height: 0.85; }
        .feature-card-info { position: absolute; bottom: -2.5rem; }
        #features .cards-grid { padding-bottom: 5rem; }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
            .reveal, .reveal-left, .reveal-right, .reveal-scale { opacity: 1; transform: none; }
        }

        *:focus-visible { outline: 2px solid #f97316; outline-offset: 2px; }
        html { scroll-behavior: smooth; }
        .heading-shadow { text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3); }
        .text-stroke { -webkit-text-stroke: 0.5px rgba(0,0,0,0.5); }

        /* Custom styles for articles */
        .article-content img { border-radius: 1rem; margin-top: 2rem; margin-bottom: 2rem; }
        .article-content h2 { font-family: 'Atma', cursive; font-size: 2rem; color: #b91c1c; margin-top: 2.5rem; margin-bottom: 1rem; }
        .article-content p { line-height: 1.8; margin-bottom: 1.5rem; color: #374151; }
    </style>
    @stack('styles')
</head>

<body class="font-inter text-white overflow-x-hidden antialiased">
    
    @include('web.partials.navbar')

    @yield('content')

    @include('web.partials.footer')

    <!-- Toast -->
    <div id="toast"
        class="fixed bottom-5 right-5 bg-green-500 text-white px-5 py-3.5 rounded-xl shadow-2xl
                transform translate-y-20 opacity-0 transition-all duration-300 z-50
                font-inter font-medium text-sm flex items-center gap-2 max-w-[calc(100vw-2.5rem)]">
        <i class='bx bx-check-circle text-xl flex-shrink-0'></i>
        <span id="toastMessage">Success!</span>
    </div>

    @include('web.partials.scripts')
    @stack('scripts')
</body>

</html>
