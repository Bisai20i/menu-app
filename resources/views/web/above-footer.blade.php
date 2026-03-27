<!-- ═══════════════ CONTACT ═══════════════ -->
<section id="contact"
    class="relative bg-cover bg-center pt-14 sm:pt-20 md:pt-24
               min-h-[520px] flex flex-col justify-between"
    style="background-image: url('{{ asset('frontend/images/above-footer.png') }}');">
    

    <div class="max-w-7xl mx-auto w-full grid grid-cols-1 lg:grid-cols-2 gap-10 sm:gap-12 items-center sm:px-8 md:px-12 lg:px-20">
        <!-- Left -->
        <div class="text-white text-center lg:text-left reveal-left">
            <h2
                class="font-atma text-4xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl leading-[0.9] uppercase mb-5 sm:mb-7">
                Let's get your<br>restaurant<br>digital
            </h2>
            <p class="font-inter text-base sm:text-lg md:text-xl font-medium opacity-90 max-w-sm mx-auto lg:mx-0">
                Tell us what you need. We'll reply within one business day.
            </p>
        </div>

        <!-- Right: Form (no <form> needed but kept for semantics; using submit handler) -->
        <form id="contactForm" class="flex flex-col gap-3 max-w-md lg:ml-auto w-full reveal-right p-4 md:p-0">
            <input type="text" placeholder="Your Name" required
                class="w-full px-5 py-3.5 rounded-xl bg-white text-gray-900 font-inter
                           focus:outline-none focus:ring-4 focus:ring-brand-orange/50 transition-all shadow-lg text-sm sm:text-base">
            <input type="email" placeholder="Email Address" required
                class="w-full px-5 py-3.5 rounded-xl bg-white text-gray-900 font-inter
                           focus:outline-none focus:ring-4 focus:ring-brand-orange/50 transition-all shadow-lg text-sm sm:text-base">
            <textarea placeholder="Your Message" rows="4" required
                class="w-full px-5 py-3.5 rounded-xl bg-white text-gray-900 font-inter
                           focus:outline-none focus:ring-4 focus:ring-brand-orange/50 transition-all shadow-lg resize-none text-sm sm:text-base"></textarea>
            <button type="submit"
                class="btn-hover w-full py-3.5 bg-brand-yellow hover:bg-[#e88f0a] text-white
                           font-inter font-bold text-base sm:text-lg rounded-xl shadow-xl touch-manipulation">
                Send Message
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
            <button onclick="scrollToSection('contact')"
                class="btn-hover px-7 sm:px-10 py-3 sm:py-4 bg-white text-brand-orange font-inter font-bold
                           text-sm sm:text-base rounded-full hover:bg-gray-100 transition shadow-lg whitespace-nowrap touch-manipulation">
                Get Your QR Code
            </button>
            <button onclick="openVideoModal()"
                class="btn-hover px-7 sm:px-10 py-3 sm:py-4 border border-white/40 bg-white/10 nav-blur
                           text-white font-inter font-bold text-sm sm:text-base rounded-full
                           hover:bg-white/20 transition whitespace-nowrap touch-manipulation">
                See Live Demo
            </button>
        </div>
    </div>
</section>
