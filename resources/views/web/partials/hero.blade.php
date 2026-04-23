<!-- ═══════════════ HERO ═══════════════ -->
<section
    class="relative min-h-screen w-full flex flex-col
                    px-5 sm:px-8 md:px-12 lg:px-20 pt-20 overflow-hidden bg-cover bg-center">

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