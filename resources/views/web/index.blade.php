<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MenuApp - The Last Menu Your Restaurant Will Ever Need</title>
    <meta name="description"
        content="QR code menus and table ordering for modern restaurants. No apps to install. Start free today.">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500&family=Inter:wght@400;500;600&family=Space+Grotesk:wght@500;600;700&display=swap"
        rel="stylesheet">

    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'bg-primary': '#ffff',
                        'bg-secondary': '#FFFDFC',
                        'accent': '#C62828',
                        'text-primary': '#1F2937',
                        'text-secondary': '#6B7280',
                    },
                    fontFamily: {
                        'display': ['Space Grotesk', 'sans-serif'],
                        'body': ['Inter', 'sans-serif'],
                        'mono': ['IBM Plex Mono', 'monospace'],
                    },
                    borderRadius: {
                        'card': '28px',
                        'button': '14px',
                    }
                }
            }
        }
    </script>

    <style>
        /* Custom Styles */
        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            /* background-color: #F8F5EF; */
            color: #1F2937;
            overflow-x: hidden;
        }

        /* Noise Overlay */
        .noise-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 9999;
            opacity: 0.025;
            mix-blend-mode: multiply;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
        }

        /* Typography */
        .font-display {
            font-family: 'Space Grotesk', sans-serif;
        }

        .font-mono {
            font-family: 'IBM Plex Mono', monospace;
        }

        /* Hero H1 */
        .hero-h1 {
            font-size: clamp(56px, 7vw, 112px);
            line-height: 0.92;
            letter-spacing: -0.02em;
        }

        .hero-h2 {
            font-size: clamp(40px, 5vw, 80px);
            line-height: 0.95;
        }

        .section-h2 {
            font-size: clamp(32px, 4vw, 64px);
            line-height: 1;
        }

        /* Neon Glow */
        .neon-glow {
            box-shadow: 0 0 20px rgba(198, 40, 40, 0.3), 0 0 40px rgba(198, 40, 40, 0.1);
        }

        .neon-text-glow {
            text-shadow: 0 0 20px rgba(198, 40, 40, 0.4);
        }

        /* Card Styles */
        .card-dark {
            background: #FFFFFF;
            border-radius: 28px;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
        }

        .card-border {
            border: 1px solid rgba(15, 23, 42, 0.08);
        }

        /* Glassmorphism */
        .glass {
            background: rgb(255, 255, 255);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
        }

        .glass-pro {
            background: rgb(255, 248, 246);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(198, 40, 40, 0.28);
            box-shadow: 0 28px 70px rgba(198, 40, 40, 0.12);
        }

        /* Button Styles */
        .btn-primary {
            background: #C62828;
            color: #FFFFFF;
            border-radius: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 8px 30px rgba(198, 40, 40, 0.3);
        }

        .btn-outline {
            border: 1px solid rgba(31, 41, 55, 0.14);
            color: #1F2937;
            background: rgba(255, 255, 255, 0.72);
            border-radius: 14px;
            transition: all 0.3s ease;
        }

        .btn-outline:hover {
            border-color: #C62828;
            color: #C62828;
            background: rgba(198, 40, 40, 0.06);
        }

        /* Form Styles */
        .form-input {
            background: rgba(248, 245, 239, 0.88);
            border: 1px solid rgba(15, 23, 42, 0.1);
            border-radius: 12px;
            color: #1F2937;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #C62828;
            box-shadow: 0 0 0 3px rgba(198, 40, 40, 0.1);
        }

        /* Floating Label */
        .floating-label-group {
            position: relative;
        }

        .floating-label {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #6B7280;
            transition: all 0.2s ease;
            pointer-events: none;
        }

        .floating-label-group input:focus~.floating-label,
        .floating-label-group input:not(:placeholder-shown)~.floating-label,
        .floating-label-group textarea:focus~.floating-label,
        .floating-label-group textarea:not(:placeholder-shown)~.floating-label {
            top: 0;
            font-size: 12px;
            background: #FFFFFF;
            padding: 0 4px;
            color: #C62828;
        }

        /* Accordion */
        .accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease;
        }

        .accordion-content.open {
            max-height: 500px;
        }

        .accordion-icon {
            transition: transform 0.3s ease;
        }

        .accordion-item.open .accordion-icon {
            transform: rotate(45deg);
        }

        /* Toggle Switch */
        .toggle-switch {
            width: 56px;
            height: 28px;
            background: rgba(15, 23, 42, 0.12);
            border-radius: 14px;
            position: relative;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .toggle-switch.active {
            background: #C62828;
        }

        .toggle-switch::after {
            content: '';
            position: absolute;
            width: 22px;
            height: 22px;
            background: #FFFFFF;
            border-radius: 50%;
            top: 3px;
            left: 3px;
            transition: transform 0.3s ease;
        }

        .toggle-switch.active::after {
            transform: translateX(28px);
            background: #F8F5EF;
        }

        /* Scroll Reveal Animation Classes */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-left {
            opacity: 0;
            transform: translateX(-50px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .reveal-left.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .reveal-right {
            opacity: 0;
            transform: translateX(50px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .reveal-right.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .reveal-scale {
            opacity: 0;
            transform: scale(0.9);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .reveal-scale.visible {
            opacity: 1;
            transform: scale(1);
        }

        /* Parallax Layer */
        .parallax-layer {
            will-change: transform;
        }

        /* Masonry Grid */
        .masonry-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        @media (max-width: 1024px) {
            .masonry-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .masonry-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Staggered masonry items */
        .masonry-item:nth-child(2) {
            transform: translateY(40px);
        }

        .masonry-item:nth-child(3) {
            transform: translateY(20px);
        }

        /* Navigation */
        .nav-fixed {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            padding: 24px 4vw;
            transition: all 0.3s ease;
        }

        .nav-fixed.scrolled {
            background: rgba(248, 245, 239, 0.92);
            backdrop-filter: blur(10px);
            padding: 16px 4vw;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        }

        /* Hamburger Menu */
        .hamburger-line {
            width: 24px;
            height: 2px;
            background: #1F2937;
            transition: all 0.3s ease;
        }

        /* Section Styles */
        section {
            position: relative;
        }

        /* Pinned Section */
        .pinned-section {
            min-height: 100vh;
            width: 100vw;
        }

        /* Image Treatment */
        .img-mono {
            filter: grayscale(100%) contrast(1.1);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #F3EEE6;
        }

        ::-webkit-scrollbar-thumb {
            background: #D4D4D8;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #C62828;
        }

        /* Neon Bar Animation */
        @keyframes neonPulse {

            0%,
            100% {
                opacity: 0.75;
            }

            50% {
                opacity: 1;
            }
        }

        .neon-pulse {
            animation: neonPulse 3s ease-in-out infinite;
        }

        /* Mobile Menu */
        .mobile-menu {
            position: fixed;
            top: 0;
            right: -100%;
            width: 100%;
            height: 100vh;
            background: rgba(248, 245, 239, 0.98);
            z-index: 200;
            transition: right 0.4s ease;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 32px;
            color: #1F2937;
        }

        .mobile-menu.open {
            right: 0;
        }
    </style>
</head>

<body>
    <!-- Noise Overlay -->
    <div class="noise-overlay"></div>

    <!-- Navigation -->
    <nav class="nav-fixed" id="navbar">
        <div class="flex justify-between items-center">
            <a href="#" class="font-mono text-sm tracking-[0.14em] uppercase text-text-primary">MenuApp</a>
            <div class="flex items-center gap-6">
                <a href="#contact"
                    class="hidden sm:block text-sm text-text-secondary hover:text-accent transition-colors">
                    Create Digital Menu
                </a>
                <button class="flex flex-col gap-1.5 p-2" id="menuToggle" aria-label="Toggle menu">
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <button class="absolute top-6 right-6 p-2" id="menuClose" aria-label="Close menu">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
        <a href="#features" class="text-2xl font-display uppercase hover:text-accent transition-colors">Features</a>
        <a href="#how-it-works" class="text-2xl font-display uppercase hover:text-accent transition-colors">How It
            Works</a>
        <a href="#pricing" class="text-2xl font-display uppercase hover:text-accent transition-colors">Pricing</a>
        <a href="#testimonials"
            class="text-2xl font-display uppercase hover:text-accent transition-colors">Testimonials</a>
        <a href="#faq" class="text-2xl font-display uppercase hover:text-accent transition-colors">FAQ</a>
        <a href="#contact" class="btn-primary px-8 py-3 mt-4">Get Started</a>
    </div>

    <!-- Section 1: Hero (Neon Split) -->
    <section class="pinned-section relative overflow-hidden" id="hero">
        <!-- Left Photo Panel -->
        <div class="absolute left-0 top-0 w-full lg:w-[56vw] h-full parallax-layer" id="heroPhoto">
            <img src="{{ asset('frontend/images/hero_bar_scene.jpg') }}" alt="Nightlife bar scene"
                class="w-full h-full object-cover img-mono">
            <div class="absolute inset-0 bg-gradient-to-r from-transparent to-bg-primary/80 lg:to-bg-primary"></div>
        </div>

        <!-- Right Dark Panel -->
        <div class="absolute right-0 top-0 w-full lg:w-[44vw] h-full bg-bg-primary flex flex-col justify-center px-8 lg:px-12"
            id="heroRightPanel">
            <!-- Neon Vertical Bar (Desktop) -->
            <div class="hidden lg:block absolute left-0 top-0 w-[1.2vw] h-full bg-accent neon-glow neon-pulse"></div>

            <!-- Mobile Neon Bar -->
            <div class="lg:hidden absolute top-0 left-0 w-full h-1 bg-accent neon-glow"></div>

            <!-- Content -->
            <div class="relative z-10 pt-20 lg:pt-0">
                <p class="font-mono text-xs tracking-[0.14em] uppercase text-text-secondary mb-6 reveal">
                    MenuApp / Nightlife Edition
                </p>
                <h1 class="font-display font-bold uppercase text-text-primary hero-h1 mb-8">
                    <span class="block reveal" style="transition-delay: 0.1s">Scan.</span>
                    <span class="block reveal" style="transition-delay: 0.2s">Order.</span>
                </h1>
                <p class="text-text-secondary text-lg max-w-md mb-10 reveal" style="transition-delay: 0.3s">
                    The fastest way for guests to order from their table. No apps. No friction.
                </p>
                <a href="#contact" class="btn-primary inline-block px-8 py-4 reveal" style="transition-delay: 0.4s">
                    Get started
                </a>
            </div>
        </div>
    </section>

    <!-- Section 2: Feature Triptych -->
    <section class="pinned-section relative bg-bg-primary py-20 lg:py-0 flex items-center" id="features">
        <div class="w-full px-4 lg:px-[4vw]">
            <!-- Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
                <!-- Top Left - Photo -->
                <div class="relative h-[40vh] lg:h-[38vh] rounded-card overflow-hidden reveal-left">
                    <img src="{{ asset('frontend/images/feature_cocktail.jpg') }}" alt="Cocktail"
                        class="w-full h-full object-cover img-mono">
                </div>

                <!-- Top Right - Text Card -->
                <div class="card-dark p-8 lg:p-10 flex flex-col justify-center h-auto lg:h-[38vh] reveal-right">
                    <h2 class="font-display font-bold uppercase text-text-primary section-h2 mb-4">
                        No Apps To Install
                    </h2>
                    <p class="text-text-secondary">
                        Guests scan and order instantly—no downloads, no friction. Just point, tap, and enjoy.
                    </p>
                </div>

                <!-- Bottom Left - Text Card -->
                <div class="card-dark p-8 lg:p-10 flex flex-col justify-center h-auto lg:h-[36vh] reveal-left"
                    style="transition-delay: 0.1s">
                    <h2 class="font-display font-bold uppercase text-text-primary section-h2 mb-4">
                        Order Directly & Track Status
                    </h2>
                    <p class="text-text-secondary">
                        Explore various menu items, Add to cart and Place the order. All in real time.
                    </p>
                </div>

                <!-- Bottom Right - Photo -->
                <div class="relative h-[40vh] lg:h-[36vh] rounded-card overflow-hidden reveal-right"
                    style="transition-delay: 0.1s">
                    <img src="{{ asset('frontend/images/feature_bar_scene.jpg') }}" alt="Bar scene"
                        class="w-full h-full object-cover img-mono">
                </div>
            </div>

            <!-- Neon Rule -->
            <div class="hidden lg:block w-full h-[3px] bg-accent mt-6 neon-pulse reveal-scale"
                style="transition-delay: 0.2s"></div>
        </div>
    </section>

    <!-- Section 3: Bento Capabilities -->
    <section class="pinned-section relative bg-bg-primary py-20 lg:py-0 flex items-center" id="capabilities">
        <div class="w-full px-4 lg:px-[4vw]">
            <!-- Top Banner -->
            <div class="relative h-[30vh] lg:h-[34vh] rounded-card overflow-hidden mb-4 lg:mb-6 reveal">
                <img src="{{ asset('frontend/images/bento_banner.jpg') }}" alt="Bar panorama"
                    class="w-full h-full object-cover img-mono">
                <div class="absolute inset-0 bg-gradient-to-t from-bg-primary/60 to-transparent"></div>
            </div>

            <!-- Bottom Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 relative">
                <!-- Neon Divider (Desktop) -->
                <div
                    class="hidden lg:block absolute left-1/2 top-0 w-[3px] h-full bg-accent neon-pulse transform -translate-x-1/2 z-10">
                </div>

                <!-- Left Card -->
                <div class="card-dark p-8 lg:p-10 h-auto lg:h-[46vh] flex flex-col justify-center reveal-left">
                    <h2 class="font-display font-bold uppercase text-text-primary section-h2 mb-4">
                        Real-Time Menu
                    </h2>
                    <p class="text-text-secondary">
                        86 items instantly. Update prices, photos, and availability in seconds. Your menu stays fresh
                        without reprinting.
                    </p>
                </div>

                <!-- Right Card -->
                <div class="card-dark p-8 lg:p-10 h-auto lg:h-[46vh] flex flex-col justify-center reveal-right">
                    <h2 class="font-display font-bold uppercase text-text-primary section-h2 mb-4">
                        Table-Specific QR
                    </h2>
                    <p class="text-text-secondary">
                        Each table gets its own code—orders land exactly where they should. No confusion, no mix-ups.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 4: Nightlife Mosaic -->
    <section class="pinned-section relative bg-bg-primary py-20 lg:py-0 flex items-center" id="mosaic">
        <div class="w-full px-4 lg:px-[4vw]">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 lg:gap-6">
                <!-- Left Tall Photo -->
                <div class="lg:col-span-4 relative h-[50vh] lg:h-[80vh] rounded-card overflow-hidden reveal-left">
                    <img src="{{ asset('frontend/images/mosaic_dj.jpg') }}" alt="DJ performing"
                        class="w-full h-full object-cover img-mono">
                </div>

                <!-- Right Side Grid -->
                <div class="lg:col-span-8 grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
                    <!-- Top Center Photo -->
                    <div class="relative h-[40vh] lg:h-[38vh] rounded-card overflow-hidden reveal-scale">
                        <img src="{{ asset('frontend/images/mosaic_crowd.jpg') }}" alt="Crowd"
                            class="w-full h-full object-cover img-mono">
                    </div>

                    <!-- Top Right Card -->
                    <div
                        class="card-dark p-8 lg:p-10 flex flex-col justify-center h-auto lg:h-[38vh] relative reveal-right">
                        <!-- Neon Corner Ticks -->
                        <div class="absolute top-4 left-4 w-4 h-4 border-l-2 border-t-2 border-accent"></div>
                        <div class="absolute bottom-4 right-4 w-4 h-4 border-r-2 border-b-2 border-accent"></div>

                        <h2 class="font-display font-bold uppercase text-text-primary text-2xl lg:text-3xl mb-4">
                            Built For Busy Nights
                        </h2>
                        <p class="text-text-secondary">
                            Handles spikes, loud music, and dim light without missing an order.
                        </p>
                    </div>

                    <!-- Bottom Center Card -->
                    <div class="card-dark p-8 lg:p-10 flex flex-col justify-center h-auto lg:h-[38vh] reveal-left"
                        style="transition-delay: 0.1s">
                        <h2 class="font-display font-bold uppercase text-text-primary text-2xl lg:text-3xl mb-4">
                            Staff Dashboard
                        </h2>
                        <p class="text-text-secondary">
                            See orders by table, course, and time—clear, fast, and actionable.
                        </p>
                    </div>

                    <!-- Bottom Right Photo -->
                    <div class="relative h-[40vh] lg:h-[38vh] rounded-card overflow-hidden reveal-right"
                        style="transition-delay: 0.1s">
                        <img src="{{ asset('frontend/images/mosaic_bar_counter.jpg') }}" alt="Bar counter"
                            class="w-full h-full object-cover img-mono">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 5: How It Works (Flowing) -->
    <section class="relative bg-bg-secondary py-20 lg:py-32" id="how-it-works">
        <div class="w-full px-4 lg:px-[4vw]">
            <!-- Heading -->
            <div class="text-center mb-16 reveal">
                <h2 class="font-display font-bold uppercase text-text-primary section-h2 mb-4">
                    How It Works
                </h2>
                <p class="text-text-secondary text-lg">
                    Three steps to a smoother service.
                </p>
            </div>

            <!-- Steps -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                <!-- Step 1 -->
                <div class="card-dark card-border p-6 lg:p-8 reveal" style="transition-delay: 0.1s">
                    <div class="relative mb-6">
                        <img src="{{ asset('frontend/images/step_scan.jpg') }}" alt="Scan QR code"
                            class="w-full h-48 object-cover rounded-2xl img-mono">
                        <span
                            class="absolute -top-4 -left-2 font-display font-bold text-7xl text-accent opacity-20">01</span>
                    </div>
                    <h3 class="font-display font-bold text-xl uppercase text-text-primary mb-3">
                        Scan the QR
                    </h3>
                    <p class="text-text-secondary">
                        Point the camera. Menu loads in under a second. No app required.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="card-dark card-border p-6 lg:p-8 reveal" style="transition-delay: 0.2s">
                    <div class="relative mb-6">
                        <img src="{{ asset('frontend/images/step_order.jpg') }}" alt="Order on phone"
                            class="w-full h-48 object-cover rounded-2xl img-mono">
                        <span
                            class="absolute -top-4 -left-2 font-display font-bold text-7xl text-accent opacity-20">02</span>
                    </div>
                    <h3 class="font-display font-bold text-xl uppercase text-text-primary mb-3">
                        Explore Menu & Order
                    </h3>
                    <p class="text-text-secondary">
                        Add items, track status, and checkout securely from the table.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="card-dark card-border p-6 lg:p-8 reveal" style="transition-delay: 0.3s">
                    <div class="relative mb-6">
                        <img src="{{ asset('frontend/images/step_kitchen.jpg') }}" alt="Kitchen display"
                            class="w-full h-48 object-cover rounded-2xl img-mono">
                        <span
                            class="absolute -top-4 -left-2 font-display font-bold text-7xl text-accent opacity-20">03</span>
                    </div>
                    <h3 class="font-display font-bold text-xl uppercase text-text-primary mb-3">
                        Kitchen Notified
                    </h3>
                    <p class="text-text-secondary">
                        Orders print or display by course and table. No missed tickets.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 6: Pricing (Flowing) -->
    <section class="relative bg-bg-primary py-20 lg:py-32" id="pricing">
        <div class="w-full px-4 lg:px-[4vw]">
            <!-- Heading -->
            <div class="text-center mb-12 reveal">
                <h2 class="font-display font-bold uppercase text-text-primary section-h2 mb-4">
                    Simple Pricing
                </h2>
                <p class="text-text-secondary text-lg">
                    Start free. Upgrade when you're ready.
                </p>
            </div>

            <!-- Toggle -->
            {{-- <div class="flex justify-center items-center gap-4 mb-12 reveal">
                <span class="text-sm text-text-secondary" id="monthlyLabel">Monthly</span>
                <div class="toggle-switch" id="pricingToggle"></div>
                <span class="text-sm text-text-primary" id="yearlyLabel">Yearly <span
                        class="text-accent text-xs">(Save 20%)</span></span>
            </div> --}}

            <!-- Plans -->
            <div class="grid grid-cols-1 lg:grid-cols-{{ $plans->count() }} gap-6 lg:gap-8 max-w-5xl mx-auto">
                @foreach ($plans as $plan)
                    <div
                        class="{{ $loop->last && $plans->count() > 1 ? 'glass-pro' : 'glass' }} p-8 lg:p-10 rounded-card relative reveal-{{ $loop->first ? 'left' : 'right' }}">
                        @if ($loop->last && $plans->count() > 1)
                            <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                                <span
                                    class="bg-accent text-white text-xs font-mono uppercase tracking-wider px-4 py-1 rounded-full">Recommended</span>
                            </div>
                        @endif
                        <div class="mb-6">
                            <h3 class="font-display font-bold text-2xl uppercase text-text-primary mb-2">
                                {{ $plan->name }}</h3>
                            <div class="flex items-baseline gap-2">
                                <span class="font-display font-bold text-5xl text-text-primary">
                                    @if ($plan->price == 0)
                                        Free
                                    @else
                                        {{ $plan->currency }} {{ number_format($plan->price, 0) }}
                                    @endif
                                </span>
                                @if ($plan->price > 0)
                                    <span class="text-text-secondary">/{{ $plan->duration_unit }}</span>
                                @endif
                            </div>
                        </div>
                        <ul class="space-y-4 mb-8">
                            @foreach ($plan->features as $key => $value)
                                <li class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-text-secondary">{{ $value }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <div>
                            <a href="#contact"
                                class="block py-4 px-8 text-center {{ $loop->last && $plans->count() > 1 ? 'btn-primary' : 'btn-outline' }}">
                                {{ $plan->price == 0 ? 'Start Free' : 'Go ' . $plan->name }}
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Section 7: Testimonials (Flowing) -->
    <section class="relative bg-bg-secondary py-20 lg:py-32" id="testimonials">
        <div class="w-full px-4 lg:px-[4vw]">
            <!-- Heading -->
            <div class="text-center mb-16 reveal">
                <h2 class="font-display font-bold uppercase text-text-primary section-h2 mb-4">
                    Loved By Teams
                </h2>
            </div>

            <!-- Masonry Grid -->
            <div class="masonry-grid max-w-6xl mx-auto">
                @foreach ($testimonials as $testimonial)
                    <div class="masonry-item card-dark card-border p-8 rounded-2xl reveal"
                        style="transition-delay: {{ $loop->index * 0.1 }}s">
                        <div class="flex items-center gap-4 mb-6">
                            @if ($testimonial->avatar)
                                <img src="{{ asset('storage/' . $testimonial->avatar) }}"
                                    alt="{{ $testimonial->name }}"
                                    class="w-12 h-12 rounded-full object-cover img-mono">
                            @else
                                <div
                                    class="w-12 h-12 rounded-full bg-accent/20 flex items-center justify-center font-bold text-accent">
                                    {{ substr($testimonial->name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <p class="font-semibold text-text-primary">{{ $testimonial->name }}</p>
                                <p class="text-sm text-text-secondary">{{ $testimonial->designation }}</p>
                            </div>
                        </div>
                        <p class="text-text-primary text-lg leading-relaxed">
                            "{{ $testimonial->content }}"
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Section 8: FAQ (Flowing) -->
    <section class="relative bg-bg-primary py-20 lg:py-32" id="faq">
        <div class="w-full px-4 lg:px-[4vw]">
            <!-- Heading -->
            <div class="text-center mb-16 reveal">
                <h2 class="font-display font-bold uppercase text-text-primary section-h2 mb-4">
                    Questions & Answers
                </h2>
            </div>

            <!-- Accordion -->
            <div class="max-w-3xl mx-auto space-y-4">
                @foreach ($faqs as $faq)
                    <div class="accordion-item card-dark card-border rounded-2xl overflow-hidden reveal"
                        style="transition-delay: {{ $loop->index * 0.1 }}s" data-accordion>
                        <button class="w-full p-6 flex items-center justify-between text-left">
                            <div class="flex items-center gap-4">
                                <span
                                    class="font-display font-bold text-2xl text-text-secondary transition-colors accordion-number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="font-semibold text-text-primary">{{ $faq->question }}</span>
                            </div>
                            <svg class="w-5 h-5 text-text-secondary accordion-icon" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                        </button>
                        <div class="accordion-content">
                            <div class="px-6 pb-6 pl-16">
                                <p class="text-text-secondary">{{ $faq->answer }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Section 9: Contact (Flowing) -->
    <section class="relative bg-bg-secondary py-20 lg:py-32" id="contact">
        <div class="w-full px-4 lg:px-[4vw]">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 max-w-6xl mx-auto">
                <!-- Left Column - Text -->
                <div class="reveal-left">
                    <h2 class="font-display font-bold uppercase text-text-primary section-h2 mb-6">
                        Let's Get Your Restaurant Digital.
                    </h2>
                    <p class="text-text-secondary text-lg mb-10">
                        Tell us what you need. We'll reply within one business day.
                    </p>

                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-accent/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-text-secondary">Email</p>
                                <p class="text-text-primary">hello@menuapp.io</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-accent/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-text-secondary">Office</p>
                                <p class="text-text-primary">123 Innovation Street, Tech City</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Form -->
                <div class="card-dark p-8 lg:p-10 rounded-card reveal-right">
                    <form id="contactForm" class="space-y-6">
                        <div class="floating-label-group">
                            <input type="text" id="name" name="name" placeholder=" "
                                class="form-input w-full px-4 py-4" required>
                            <label for="name" class="floating-label">Your Name</label>
                        </div>

                        <div class="floating-label-group">
                            <input type="email" id="email" name="email" placeholder=" "
                                class="form-input w-full px-4 py-4" required>
                            <label for="email" class="floating-label">Email Address</label>
                        </div>

                        <div class="floating-label-group">
                            <textarea id="message" name="message" placeholder=" " rows="4"
                                class="form-input w-full px-4 py-4 resize-none" required></textarea>
                            <label for="message" class="floating-label">Your Message</label>
                        </div>

                        <button type="submit" class="btn-primary w-full py-4">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="relative bg-bg-primary py-12 border-t border-slate-900/8">
        <div class="w-full px-4 lg:px-[4vw]">
            <div class="flex flex-col lg:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-8">
                    <a href="#"
                        class="font-mono text-sm tracking-[0.14em] uppercase text-text-primary">MenuApp</a>
                    <div class="hidden sm:flex items-center gap-6 text-sm text-text-secondary">
                        <a href="#features" class="hover:text-accent transition-colors">Features</a>
                        <a href="#pricing" class="hover:text-accent transition-colors">Pricing</a>
                        <a href="#faq" class="hover:text-accent transition-colors">FAQ</a>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <a href="#"
                        class="w-10 h-10 rounded-full bg-slate-900/5 flex items-center justify-center hover:bg-accent/20 transition-colors">
                        <svg class="w-5 h-5 text-text-secondary" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                        </svg>
                    </a>
                    <a href="#"
                        class="w-10 h-10 rounded-full bg-slate-900/5 flex items-center justify-center hover:bg-accent/20 transition-colors">
                        <svg class="w-5 h-5 text-text-secondary" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                        </svg>
                    </a>
                    <a href="#"
                        class="w-10 h-10 rounded-full bg-slate-900/5 flex items-center justify-center hover:bg-accent/20 transition-colors">
                        <svg class="w-5 h-5 text-text-secondary" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                        </svg>
                    </a>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-slate-900/8 text-center">
                <p class="text-sm text-text-secondary">
                    Designed and Developed by <span class="text-accent">Tuki Soft Pvt. Ltd.</span>
                </p>
                <p class="text-xs text-text-secondary/60 mt-2">
                    © MenuApp. Built for fast service.
                </p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="{{ asset('frontend/js/main.js') }}"></script>
</body>

</html>
