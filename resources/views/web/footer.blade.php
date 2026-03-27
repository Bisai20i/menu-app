<!-- ═══════════════ FOOTER ═══════════════ -->
<footer class="bg-[#0a0a0a] text-white py-12 sm:py-16 md:py-20 px-5 sm:px-8 md:px-12 lg:px-20 font-inter">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-8 sm:gap-10 lg:gap-12 mb-10 sm:mb-14">

            <!-- Branding (full width on mobile) -->
            <div class="space-y-4 col-span-2 sm:col-span-2 lg:col-span-1">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('frontend/images/logo.png') }}" alt="Saral Menu" class="w-20 h-20">
                </div>
                <p class="text-gray-400 text-sm max-w-xs leading-relaxed">
                    Scan, order, done. No apps, no friction—just seamless table ordering.
                </p>
                <div class="flex gap-3">
                    <a href="#"
                        class="w-9 h-9 border border-white/20 rounded-full flex items-center justify-center hover:bg-white hover:text-black transition-all duration-300 group"
                        aria-label="Facebook">
                        <i class='bx bxl-facebook text-lg group-hover:scale-110 transition-transform'></i>
                    </a>
                    <a href="#"
                        class="w-9 h-9 border border-white/20 rounded-full flex items-center justify-center hover:bg-white hover:text-black transition-all duration-300 group"
                        aria-label="Twitter">
                        <i class='bx bxl-twitter text-lg group-hover:scale-110 transition-transform'></i>
                    </a>
                    <a href="#"
                        class="w-9 h-9 border border-white/20 rounded-full flex items-center justify-center hover:bg-white hover:text-black transition-all duration-300 group"
                        aria-label="Instagram">
                        <i class='bx bxl-instagram text-lg group-hover:scale-110 transition-transform'></i>
                    </a>
                    <a href="#"
                        class="w-9 h-9 border border-white/20 rounded-full flex items-center justify-center hover:bg-white hover:text-black transition-all duration-300 group"
                        aria-label="LinkedIn">
                        <i class='bx bxl-linkedin text-lg group-hover:scale-110 transition-transform'></i>
                    </a>
                </div>
            </div>

            <!-- Product -->
            <div>
                <h5 class="text-brand-yellow font-bold text-sm sm:text-base uppercase mb-4">Product</h5>
                <ul class="space-y-2 text-gray-400 font-medium text-sm">
                    <li><a href="#features" class="hover:text-white transition-colors duration-300">Features</a></li>
                    <li><a href="#how-it-works" class="hover:text-white transition-colors duration-300">How It Works</a>
                    </li>
                    <li><a href="#pricing" class="hover:text-white transition-colors duration-300">Subscription
                            Plans</a></li>
                    <li><a href="#testimonials" class="hover:text-white transition-colors duration-300">Testimonials</a>
                    </li>
                    <li><a href="#faq" class="hover:text-white transition-colors duration-300">FAQ</a></li>
                </ul>
            </div>

            <!-- Links -->
            <div>
                <h5 class="text-brand-yellow font-bold text-sm sm:text-base uppercase mb-4">Links</h5>
                <ul class="space-y-2 text-gray-400 font-medium text-sm">
                    <li><a href="#" class="hover:text-white transition-colors duration-300">Privacy Policy</a>
                    </li>
                    <li><a href="#" class="hover:text-white transition-colors duration-300">Terms & Condition</a>
                    </li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h5 class="text-brand-yellow font-bold text-sm sm:text-base uppercase mb-4">Contact Us</h5>
                <ul class="space-y-3 text-gray-400 font-medium text-sm">
                    <li class="flex items-center gap-2 group cursor-pointer">
                        <i
                            class='bx bx-envelope text-brand-orange text-lg group-hover:scale-110 transition-transform flex-shrink-0'></i>
                        <span class="group-hover:text-white transition-colors break-all">hello@menuapp.io</span>
                    </li>
                    <li class="flex items-start gap-2 group cursor-pointer">
                        <i
                            class='bx bx-map text-brand-orange text-lg flex-shrink-0 mt-0.5 group-hover:scale-110 transition-transform'></i>
                        <span class="group-hover:text-white transition-colors">Sundar margha, Pokhara</span>
                    </li>
                    <li class="flex items-center gap-2 group cursor-pointer">
                        <i
                            class='bx bx-phone text-brand-orange text-lg flex-shrink-0 group-hover:scale-110 transition-transform'></i>
                        <span class="group-hover:text-white transition-colors">9700000000 | 9800000000</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="pt-6 border-t border-white/10 text-center text-gray-500 text-xs sm:text-sm font-medium">
            © 2026. MenuApp. All rights reserved
        </div>
    </div>
</footer>
