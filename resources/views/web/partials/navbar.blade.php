<div id="mainNavbar" class="fixed top-0 left-0 w-full z-[100] px-5 sm:px-8 md:px-12 lg:px-20 py-3 sm:py-4 md:py-5 transition-all duration-300 pointer-events-none">
    <div class="max-w-7xl mx-auto w-full flex items-center justify-between pointer-events-auto">
        <!-- LOGO -->
        <a href="{{ request()->is('/') ? 'javascript:void(0)' : url('/') }}" class="w-14 h-14 md:w-16 md:h-16 bg-red-900/60 rounded-full
                    flex items-center justify-center border border-white/20 nav-blur
                    shadow-2xl cursor-pointer hover:scale-110 transition-transform duration-300 flex-shrink-0 overflow-hidden">
            <img src="{{ asset('frontend/images/logo-circle.png') }}" alt="Saral Menu Logo" class="w-full h-full object-contain">
        </a>

        <!-- NAV PILL + DROPDOWN wrapper -->
        <div class="relative flex flex-col items-end">
            <nav id="navPill" class="flex items-center gap-3 sm:gap-5 md:gap-6
                        bg-white/10 nav-blur border border-white/20
                        px-4 sm:px-6 md:px-8 py-2.5 sm:py-3 rounded-full font-atma text-white text-base sm:text-lg shadow-lg transition-all duration-300">
                <a href="{{ url('/#features') }}" class="hidden md:block hover:text-brand-orange fw-semibold transition-colors duration-300 relative group heading-shadow ">
                    Features
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-brand-orange transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="{{ url('/#pricing') }}" class="hidden md:block hover:text-brand-orange transition-colors duration-300 relative group heading-shadow ">
                    Pricing
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-brand-orange transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="{{ request()->routeIs('articles.index') ? 'javascript:void(0)' : route('articles.index') }}" class="hidden md:block hover:text-brand-orange transition-colors duration-300 relative group heading-shadow  {{ Request::is('articles*') ? 'text-brand-orange' : '' }}">
                    Articles
                    <span class="absolute bottom-0 left-0 w-full h-0.5 bg-brand-orange transition-all duration-300 {{ Request::is('articles*') ? 'w-full' : 'w-0' }} group-hover:w-full"></span>
                </a>
                <a href="{{ url('/#faq') }}" class="hidden md:block hover:text-brand-orange transition-colors duration-300 relative  group heading-shadow ">
                    FAQ
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-brand-orange transition-all duration-300 group-hover:w-full"></span>
                </a>
                <button id="menuToggle"
                    class="focus:outline-none hover:scale-110 transition-transform duration-300 p-1 touch-manipulation text-white heading-shadow "
                    aria-label="Toggle menu">
                    <i class='bx bx-menu text-2xl sm:text-3xl'></i>
                </button>
            </nav>

            <!-- DROPDOWN -->
            <div id="navDropdown"
                class="dropdown-hidden absolute top-full right-0 mt-3 w-56 sm:w-64 p-5 sm:p-7
                        rounded-2xl sm:rounded-3xl bg-white/10 nav-blur border border-white/20 shadow-2xl z-50 text-white">
                <ul class="space-y-3 font-inter font-semibold text-sm sm:text-base">
                    <li><a href="/#features"     class="hover:text-brand-orange transition-colors duration-300 block py-1 heading-shadow ">Features</a></li>
                    <li><a href="{{ request()->routeIs('articles.index') ? 'javascript:void(0)' : route('articles.index') }}" class="hover:text-brand-orange transition-colors duration-300 block py-1 text-brand-yellow font-bold heading-shadow ">Latest Articles</a></li>
                    <li><a href="/#how-it-works" class="hover:text-brand-orange transition-colors duration-300 block py-1 heading-shadow ">How It Works</a></li>
                    <li><a href="/#pricing"      class="hover:text-brand-orange transition-colors duration-300 block py-1 heading-shadow ">Subscription Pricing</a></li>
                    <li><a href="/#testimonials" class="hover:text-brand-orange transition-colors duration-300 block py-1 text-white/80 heading-shadow ">Loved By Teams</a></li>
                    <li><a href="/#contact"      class="hover:text-brand-orange transition-colors duration-300 block py-1 text-white/80 heading-shadow ">Contact Us</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
