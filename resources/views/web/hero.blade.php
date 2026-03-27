<!-- ═══════════════ HERO ═══════════════ -->
<section
    class="relative min-h-screen w-full flex flex-col
                    px-5 sm:px-8 md:px-12 lg:px-20 overflow-hidden bg-cover bg-center">

    <div class="relative w-full flex items-center justify-between pt-5 sm:pt-6 md:pt-8 z-50">

    <!-- LOGO -->
    <div class="w-14 h-14 md:w-16 md:h-16 bg-red-900/60 rounded-full
                flex items-center justify-center border border-white/20 nav-blur
                shadow-2xl cursor-pointer hover:scale-110 transition-transform duration-300 flex-shrink-0">
        <img src="{{ asset('frontend/images/logo-circle.png') }}" alt="Saral Menu Logo" class="w-full h-full object-contain rounded-full">
    </div>

    <!-- NAV PILL + DROPDOWN wrapper -->
    <div class="relative flex flex-col items-end">

        <nav class="flex items-center gap-3 sm:gap-5 md:gap-6
                    bg-white/10 nav-blur border border-white/20
                    px-4 sm:px-6 md:px-8 py-2.5 sm:py-3 rounded-full font-atma text-base sm:text-lg shadow-lg">
            <a href="#features" class="hidden md:block hover:text-brand-orange transition-colors duration-300 relative group">
                Features
                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-brand-orange transition-all duration-300 group-hover:w-full"></span>
            </a>
            <a href="#pricing" class="hidden md:block hover:text-brand-orange transition-colors duration-300 relative group">
                Pricing
                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-brand-orange transition-all duration-300 group-hover:w-full"></span>
            </a>
            <a href="#faq" class="hidden md:block hover:text-brand-orange transition-colors duration-300 relative group">
                FAQ
                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-brand-orange transition-all duration-300 group-hover:w-full"></span>
            </a>
            <button id="menuToggle"
                class="focus:outline-none hover:scale-110 transition-transform duration-300 p-1 touch-manipulation"
                aria-label="Toggle menu">
                <i class='bx bx-menu text-2xl sm:text-3xl'></i>
            </button>
        </nav>

        <!-- DROPDOWN — absolute so it floats below the pill without pushing layout -->
        <div id="navDropdown"
            class="dropdown-hidden absolute top-full right-0 mt-3 w-56 sm:w-64 p-5 sm:p-7
                    rounded-2xl sm:rounded-3xl bg-white/10 nav-blur border border-white/20 shadow-2xl z-50">
            <ul class="space-y-3 font-inter font-semibold text-sm sm:text-base">
                <li><a href="#features"     class="hover:text-brand-orange transition-colors duration-300 block py-1">Features</a></li>
                <li><a href="#how-it-works" class="hover:text-brand-orange transition-colors duration-300 block py-1">How It Works</a></li>
                <li><a href="#pricing"      class="hover:text-brand-orange transition-colors duration-300 block py-1">Subscription Pricing</a></li>
                <li><a href="#testimonials" class="hover:text-brand-orange transition-colors duration-300 block py-1 text-white/80">Loved By Teams</a></li>
                <li><a href="#contact"      class="hover:text-brand-orange transition-colors duration-300 block py-1 text-white/80">Contact Us</a></li>
            </ul>
        </div>

    </div>
</div>

    <!-- TWO-COLUMN BODY -->
    <div class="flex-1 flex items-center">
        <div class="w-full grid grid-cols-1 lg:grid-cols-2 gap-10 py-12 sm:py-16">

            <!-- COL 1: Text content -->
            <div class="flex flex-col justify-center max-w-xl text-center md:text-start">
                <p class="font-inter font-semibold text-[10px] sm:text-xs tracking-widest mb-4 sm:mb-6 opacity-90 reveal stagger-1">
                    MENUAPP / SPICY LIFE EDITION
                </p>

                <h1 class="hero-heading font-atma font-bold uppercase select-none tracking-tight reveal stagger-2">
                    <span class="text-white block drop-shadow-lg">Scan</span>
                    <span class="text-brand-orange block drop-shadow-lg">Order</span>
                </h1>

                <div class="mt-5 sm:mt-7 space-y-1.5 reveal stagger-3">
                    <p class="text-sm sm:text-base md:text-lg font-medium leading-snug">
                        The fastest way for guests to order from their table.
                    </p>
                    <p class="text-brand-orange font-bold text-sm sm:text-base animate-pulse">
                        No apps. No Friction.
                    </p>
                </div>

                <div class="mt-7 sm:mt-10 reveal stagger-4">
                    <button onclick="scrollToSection('contact')"
                        class="btn-hover inline-flex items-center gap-2 px-7 sm:px-10 py-3.5 sm:py-4
                               bg-white/10 border border-white/20 nav-blur rounded-xl sm:rounded-2xl
                               text-base sm:text-lg font-bold hover:bg-white/20 transition-all shadow-xl group touch-manipulation">
                        Get started
                        <i class='bx bx-right-arrow-alt text-lg group-hover:translate-x-1 transition-transform'></i>
                    </button>
                </div>
            </div>

            <!-- COL 2: Play button — centered; hidden on mobile, visible lg+ -->
            <div class="flex items-center min-h-[40vh] justify-center reveal-scale">
                <!-- Ripple rings -->
                <div class="relative flex items-center justify-center">
                    <div class="ripple"></div>
                    <div class="ripple"></div>
                    <div class="ripple"></div>

                    <!-- Bouncing play button -->
                    <div class="animate-slow-bounce relative">
                        <div class="absolute -top-10 -left-2 w-3 h-3 bg-brand-orange rounded-full animate-ping"></div>
                        <div class="absolute -top-5 -right-2 w-4 h-4 bg-brand-orange rounded-full animate-ping" style="animation-delay:.5s"></div>

                        <button id="openVideo"
                            class="btn-hover w-20 h-20 md:w-24 md:h-24 bg-white rounded-full flex items-center justify-center
                                   shadow-[0_0_60px_rgba(255,255,255,0.3)] group relative touch-manipulation">
                            <i class='bx bx-play text-3xl md:text-4xl text-brand-red group-hover:text-brand-orange transition-colors ml-1'></i>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Scroll indicator -->
    <div class="pb-6 justify-center animate-bounce hidden md:flex">
        <a href="#features" class="text-white/60 hover:text-white transition-colors">
            <i class='bx bx-chevron-down text-4xl'></i>
        </a>
    </div>

    <!-- VIDEO MODAL -->
    <div id="videoModal"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/95 hidden backdrop-blur-md p-4 opacity-0 transition-opacity duration-300">
        <button id="closeVideo"
            class="absolute top-4 right-4 sm:top-8 sm:right-8 text-white hover:text-brand-orange transition-colors z-10 p-2 touch-manipulation">
            <i class='bx bx-x text-4xl sm:text-5xl'></i>
        </button>
        <div class="w-full max-w-4xl aspect-video rounded-2xl overflow-hidden shadow-2xl border border-white/10 transform scale-95 transition-transform duration-300"
            id="videoContainer">
            <iframe id="videoIframe" class="w-full h-full" src="" frameborder="0"
                allow="autoplay; encrypted-media" allowfullscreen></iframe>
        </div>
    </div>
</section>