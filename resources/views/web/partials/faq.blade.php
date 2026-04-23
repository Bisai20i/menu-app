<!-- ═══════════════ FAQ ═══════════════ -->
<section id="faq" class="relative py-16 sm:py-20 px-5 sm:px-8 md:px-12 lg:px-20">
    <div class="max-w-3xl mx-auto">

        <h2 class="font-atma font-bold text-2xl sm:text-3xl md:text-4xl text-center text-white mb-8 sm:mb-12 reveal heading-shadow">
            FREQUENTLY ASKED QUESTIONS
        </h2>

        <div class="space-y-3 sm:space-y-4">
            @foreach ($faqs as $index => $faq)
                <!-- FAQ Item {{ $index + 1 }} -->
                <div
                    class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 overflow-hidden reveal stagger-{{ ($index % 4) + 1 }}">
                    <button
                        class="faq-toggle w-full flex items-center justify-between p-4 sm:p-5 text-left hover:bg-gray-50 transition-colors touch-manipulation"
                        aria-expanded="false">
                        <div class="flex items-center gap-3 pr-3">
                            <span
                                class="font-atma font-bold text-brand-red text-base sm:text-xl flex-shrink-0">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="font-medium text-gray-800 text-sm sm:text-base">{{ $faq->question }}</span>
                        </div>
                        <i class='bx bx-plus text-brand-red text-xl sm:text-2xl faq-icon flex-shrink-0'></i>
                    </button>
                    <div class="faq-content px-4 sm:px-5 pb-4 pl-12 sm:pl-16">
                        <p class="text-gray-600 text-sm sm:text-base leading-relaxed">{{ $faq->answer }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
