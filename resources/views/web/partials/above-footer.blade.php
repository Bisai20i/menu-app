<!-- ═══════════════ CONTACT ═══════════════ -->
<section id="contact"
    class="relative bg-cover bg-center pt-14 sm:pt-20 md:pt-24
               min-h-[520px] flex flex-col justify-between"
    style="background-image: url('{{ asset('frontend/images/above-footer.png') }}');">


    <div
        class="max-w-7xl mx-auto w-full grid grid-cols-1 lg:grid-cols-2 gap-10 sm:gap-12 items-center sm:px-8 md:px-12 lg:px-20">
        <!-- Left -->
        <div class="text-white text-center lg:text-left reveal-left">
            <h2
                class="font-atma font-bold text-4xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl leading-[0.9] uppercase mb-5 sm:mb-7 heading-shadow">
                Let's get your<br>restaurant<br>digital
            </h2>
            <p class="font-inter text-base sm:text-lg md:text-xl font-medium opacity-90 max-w-sm mx-auto lg:mx-0">
                Tell us what you need. We'll reply within one business day.
            </p>
        </div>

        <!-- Right: Form (no <form> needed but kept for semantics; using submit handler) -->
        <form id="contactForm" class="flex flex-col gap-5 max-w-md lg:ml-auto w-full reveal-right p-4 md:p-0">
            @csrf
            <div class="flex flex-col gap-1.5">
                <label for="name"
                    class="text-white text-sm font-bold uppercase tracking-widest opacity-80 pl-1">Name</label>
                <input type="text" id="name" name="name" placeholder="John Doe" required
                    class="w-full px-5 py-3.5 rounded-xl bg-white text-gray-900 font-inter
                               focus:outline-none focus:ring-4 focus:ring-brand-orange/50 transition-all shadow-lg text-sm sm:text-base">
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="email"
                    class="text-white text-sm font-bold uppercase tracking-widest opacity-80 pl-1">Email</label>
                <input type="email" id="email" name="email" placeholder="john@example.com" required
                    class="w-full px-5 py-3.5 rounded-xl bg-white text-gray-900 font-inter
                               focus:outline-none focus:ring-4 focus:ring-brand-orange/50 transition-all shadow-lg text-sm sm:text-base">
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="message"
                    class="text-white text-sm font-bold uppercase tracking-widest opacity-80 pl-1">Message</label>
                <textarea id="message" name="message" placeholder="How can we help you?" rows="4" required
                    class="w-full px-5 py-3.5 rounded-xl bg-white text-gray-900 font-inter
                               focus:outline-none focus:ring-4 focus:ring-brand-orange/50 transition-all shadow-lg resize-none text-sm sm:text-base"></textarea>
            </div>

            <!-- Anti-Spam -->
            <div class="hidden">
                <input type="text" name="honey_pot" tabindex="-1" autocomplete="off">
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="math_answer" class="text-white text-sm font-bold uppercase tracking-widest opacity-80 pl-1">
                    What is {{ $val1 ?: 5 }} + {{ $val2 ?: 3 }}?
                </label>
                <input type="number" id="math_answer" name="math_answer" placeholder="Your answer" required
                    data-answer="{{ session('contact_math_answer') }}"
                    class="w-full px-5 py-3.5 rounded-xl bg-white text-gray-900 font-inter
                               focus:outline-none focus:ring-4 focus:ring-brand-orange/50 transition-all shadow-lg text-sm sm:text-base">
            </div>

            <button type="submit" id="submitBtn"
                class="btn-hover w-full py-4 bg-brand-yellow hover:bg-[#e88f0a] text-white
                           font-inter font-bold text-base sm:text-lg rounded-xl shadow-xl transition-all disabled:opacity-70 disabled:cursor-not-allowed">
                <span id="btnText">Send Message</span>
                <span id="loadingSpan" class="hidden flex items-center justify-center gap-2">
                    <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4" fill="none"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    Sending...
                </span>
            </button>
        </form>
    </div>

    <!-- CTA Bar -->
    <div
        class="mt-10 sm:mt-14 w-full bg-black/40 nav-blur border border-white/10
                    p-5 sm:p-7 md:p-10 flex flex-col sm:flex-row items-center justify-between
                    gap-5 sm:gap-8 mx-0 reveal-scale sm:px-8 md:px-12 lg:px-20">
        <div class="text-white font-inter text-center sm:text-left">
            <h4 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-black uppercase tracking-tight">
                Ready to go <span class="text-brand-yellow">Digital?</span>
            </h4>
            <p class="text-xs sm:text-sm md:text-base font-bold opacity-80 uppercase tracking-widest mt-1">
                Start free - no credit card needed.
            </p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <a href="{{ route('master.register') }}"
                class="btn-hover px-7 sm:px-10 py-3 sm:py-4 bg-white text-brand-orange font-inter font-bold
                           text-sm sm:text-base rounded-full hover:bg-gray-100 transition shadow-lg whitespace-nowrap touch-manipulation">
                Get Your QR Code
            </a>
            <button onclick="openVideoModal()"
                class="btn-hover px-7 sm:px-10 py-3 sm:py-4 border border-white/40 bg-white/10 nav-blur
                           text-white font-inter font-bold text-sm sm:text-base rounded-full
                           hover:bg-white/20 transition whitespace-nowrap touch-manipulation">
                See Live Demo
            </button>
        </div>
    </div>
</section>
