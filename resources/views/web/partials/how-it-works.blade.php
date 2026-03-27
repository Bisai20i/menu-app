<!-- ═══════════════ HOW IT WORKS ═══════════════ -->
<section id="how-it-works"
    class="py-16 sm:py-20 md:py-24 px-5 sm:px-8 md:px-12 lg:px-20 relative overflow-hidden">
    <h2
        class="font-atma font-bold text-3xl sm:text-4xl md:text-5xl text-center mb-8 sm:mb-12 reveal heading-shadow">
        How It Works
    </h2>

    <div class="max-w-5xl mx-auto space-y-10 sm:space-y-14 md:space-y-16">

        <!-- Step 1 -->
        <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-0 reveal-left">
            <div
                class="relative z-10 w-24 h-24 sm:w-40 sm:h-40 md:w-48 md:h-48 lg:w-56 lg:h-56
                            flex-shrink-0 rounded-full border-4 border-brand-red overflow-hidden shadow-2xl group cursor-pointer">
                <img src="{{ asset('frontend/images/scan-qr.png') }}" alt="Scan QR"
                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                    loading="lazy">
            </div>
            <div
                class="bg-[#fffbf2] sm:-ml-20 md:-ml-28 
                            pr-4 pl-4 sm:pl-24 md:pl-32  py-5 sm:py-7 md:py-9
                            rounded-md sm:rounded-full shadow-xl w-full flex-1 text-center sm:text-left">
                <h3 class="font-inter font-extrabold text-brand-red text-base sm:text-lg md:text-2xl uppercase mb-1.5">
                    Scan the QR</h3>
                <p class="font-inter text-gray-800 text-sm sm:text-base leading-snug">Point the camera. Menu loads in
                    under a second. No app required.</p>
            </div>
        </div>

        <!-- Step 2 -->
        <div class="flex flex-col sm:flex-row-reverse items-center gap-4 sm:gap-0 reveal-right">
            <div
                class="relative z-10 w-24 h-24 sm:w-40 sm:h-40 md:w-48 md:h-48 lg:w-56 lg:h-56
                            flex-shrink-0 rounded-full border-4 border-brand-red overflow-hidden shadow-2xl group cursor-pointer">
                <img src="{{ asset('frontend/images/explore-menu.png') }}" alt="Explore Menu"
                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                    loading="lazy">
            </div>
            <div
                class="bg-[#fffbf2] sm:-mr-20 md:-mr-28 
                            pr-4 pl-4 sm:pr-24 md:pr-32  py-5 sm:py-7 md:py-9
                            rounded-md sm:rounded-full shadow-xl w-full flex-1 text-center sm:text-right">
                <h3 class="font-inter font-extrabold text-brand-red text-base sm:text-lg md:text-2xl uppercase mb-1.5">
                    Explore Menu & Order</h3>
                <p class="font-inter text-gray-800 text-sm sm:text-base leading-snug">Add items, track status, and
                    checkout securely from the table.</p>
            </div>
        </div>

        <!-- Step 3 -->
        <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-0 reveal-left">
            <div
                class="relative z-10 w-24 h-24 sm:w-40 sm:h-40 md:w-48 md:h-48 lg:w-56 lg:h-56
                            flex-shrink-0 rounded-full border-4 border-brand-red overflow-hidden shadow-2xl group cursor-pointer">
                <img src="{{ asset('frontend/images/kitchen-notified.png') }}"
                    alt="Kitchen Notification"
                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                    loading="lazy">
            </div>
            <div
                class="bg-[#fffbf2] sm:-ml-20 md:-ml-28 
                           pr-4 pl-4 sm:pl-24 md:pl-32  py-5 sm:py-7 md:py-9
                            rounded-md sm:rounded-full shadow-xl w-full flex-1 text-center sm:text-left">
                <h3 class="font-inter font-extrabold text-brand-red text-base sm:text-lg md:text-2xl uppercase mb-1.5">
                    Kitchen Notified</h3>
                <p class="font-inter text-gray-800 text-sm sm:text-base leading-snug">Orders print or display by course
                    and table. No missed tickets.</p>
            </div>
        </div>
    </div>
</section>
