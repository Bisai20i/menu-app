<!-- ═══════════════ FEATURES ═══════════════ -->
<section id="features"
    class="relative bg-brand-cream py-16 sm:py-20 md:py-24 px-5 sm:px-8 md:px-12 lg:px-20 overflow-hidden">
    <!-- Decorative blob -->
    <div class="absolute top-0 left-0 w-full h-full pointer-events-none opacity-40">
        <svg class="absolute top-[-10%] left-[-10%] w-[60%] h-[60%] text-[#f8e3c3]" viewBox="0 0 200 200"
            fill="currentColor">
            <path
                d="M45.7,-77.4C58.1,-70.5,66.4,-55.9,73.1,-41.2C79.8,-26.5,84.9,-11.7,83.2,2.5C81.5,16.7,73,30.3,64.2,43.7C55.4,57.1,46.3,70.3,34,76.5C21.7,82.7,6.2,81.9,-9.2,78.7C-24.6,75.5,-39.9,69.9,-52.3,60.5C-64.7,51.1,-74.2,37.9,-79.8,23.3C-85.4,8.7,-87.1,-7.3,-82.9,-21.9C-78.7,-36.5,-68.6,-49.7,-55.9,-56.5C-43.2,-63.3,-27.9,-63.7,-13.4,-65.7C1.1,-67.7,15.6,-71.3,45.7,-77.4Z"
                transform="translate(100 100)" />
        </svg>
    </div>

    <div class="max-w-7xl mx-auto relative z-10">
        <h2
            class="font-atma font-bold text-3xl sm:text-4xl md:text-5xl text-brand-red text-center mb-14 sm:mb-16 md:mb-20 uppercase tracking-wide reveal">
            Features
        </h2>

        <!-- Cards: single column → 2-col md → 3-col lg -->
        <!-- Each card wraps image + info box. Bottom padding gives room for info box -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-24 sm:gap-y-28 pb-10">

            <!-- Card 1 -->
            <div class="relative flex flex-col items-start reveal stagger-1 card-hover group">
                <div
                    class="rounded-[2rem] overflow-hidden shadow-2xl w-full aspect-[4/5] border-4 border-white/20 img-zoom">
                    <img src="{{ asset('frontend/images/feature-1.png') }}" alt="QR Menu"
                        class="w-full h-full object-cover" loading="lazy">
                </div>
                <div
                    class="absolute -bottom-[4.5rem] left-4 right-6 bg-[#fffbf2] p-5 sm:p-6
                               rounded-[2rem]
                                shadow-xl group-hover:shadow-2xl transition-shadow">
                    <h3
                        class="font-inter font-extrabold text-brand-orange text-sm sm:text-base uppercase mb-2 leading-tight">
                        No Apps To Install</h3>
                    <p class="font-inter text-gray-700 text-xs sm:text-sm leading-relaxed">Guests scan and order
                        instantly—no downloads, no friction.</p>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="relative flex flex-col items-center reveal stagger-2 card-hover group">
                <div
                    class="rounded-[2rem] overflow-hidden shadow-2xl w-full aspect-[4/5] border-4 border-white/20 img-zoom">
                    <img src="{{ asset('frontend/images/feature-2.png') }}" alt="Order Tracking"
                        class="w-full h-full object-cover" loading="lazy">
                </div>
                <div
                    class="absolute -bottom-[4.5rem] left-4 right-4 bg-[#fffbf2] p-5 sm:p-6
                                rounded-[2rem] shadow-xl group-hover:shadow-2xl transition-shadow">
                    <h3
                        class="font-inter font-extrabold text-brand-orange text-sm sm:text-base uppercase mb-2 leading-tight">
                        Order Directly & Track Status</h3>
                    <p class="font-inter text-gray-700 text-xs sm:text-sm leading-relaxed">Explore menu items, add to
                        cart and place orders. All in real time.</p>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="relative flex flex-col items-end reveal stagger-3 card-hover group md:col-span-2 lg:col-span-1">
                <div
                    class="rounded-[2rem] overflow-hidden shadow-2xl w-full aspect-[4/5] border-4 border-white/20 img-zoom">
                    <img src="{{ asset('frontend/images/feature-3.png') }}"
                        alt="Real-time Menu" class="w-full h-full object-cover" loading="lazy">
                </div>
                <div
                    class="absolute -bottom-[4.5rem] right-4 left-6 bg-[#fffbf2] p-5 sm:p-6
                                rounded-[2rem]
                                shadow-xl group-hover:shadow-2xl transition-shadow">
                    <h3
                        class="font-inter font-extrabold text-brand-orange text-sm sm:text-base uppercase mb-2 leading-tight">
                        Real-Time Menu</h3>
                    <p class="font-inter text-gray-700 text-xs sm:text-sm leading-relaxed">Update prices, photos, and
                        availability instantly—no reprinting.</p>
                </div>
            </div>
        </div>
    </div>
</section>
