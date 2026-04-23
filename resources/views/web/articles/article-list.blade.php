<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    @forelse($articles as $article)
        <article class="bg-white rounded-3xl overflow-hidden shadow-lg border border-brand-beige group hover:border-brand-red transition-all duration-300 reveal-scale active">
            <div class="aspect-video relative overflow-hidden">
                @if($article->thumbnail)
                    <img src="{{ Storage::url($article->thumbnail) }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                @else
                    <div class="w-full h-full bg-brand-lightBeige flex items-center justify-center">
                        <i class='bx bx-news text-5xl text-brand-red opacity-20'></i>
                    </div>
                @endif
                <div class="absolute top-4 left-4">
                    <span class="bg-brand-red text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-lg">New</span>
                </div>
            </div>
            <div class="p-6 md:p-8">
                <div class="flex items-center gap-2 mb-4 text-gray-500 text-xs font-medium">
                    <i class='bx bx-calendar'></i>
                    <span>{{ $article->created_at->format('M d, Y') }}</span>
                    <span class="mx-1">•</span>
                    <i class='bx bx-time'></i>
                    <span>5 min read</span> 
                </div>
                <h3 class="font-atma font-bold text-2xl text-gray-900 mb-3 group-hover:text-brand-red transition-colors line-clamp-2">
                    {{ $article->title }}
                </h3>
                <p class="text-gray-600 text-sm mb-6 line-clamp-3">
                    {{ Str::limit(strip_tags($article->content), 120) }}
                </p>
                <a href="{{ route('articles.show', $article->slug) }}" class="inline-flex items-center gap-2 text-brand-red font-bold text-sm hover:gap-3 transition-all duration-300">
                    Read More <i class='bx bx-right-arrow-alt text-xl'></i>
                </a>
            </div>
        </article>
    @empty
        <div class="col-span-full py-20 text-center">
            <i class='bx bx-search-alt text-6xl text-brand-red opacity-20 mb-4'></i>
            <h3 class="font-atma font-bold text-2xl text-gray-900">No articles found</h3>
            <p class="text-gray-500">We couldn't find any articles matching your search.</p>
        </div>
    @endforelse
</div>

@if($articles->hasPages())
    <div class="mt-16 flex justify-center">
        <nav class="flex items-center gap-2">
            @if ($articles->onFirstPage())
                <span class="w-12 h-12 flex items-center justify-center rounded-xl bg-gray-100 text-gray-400 cursor-not-allowed">
                    <i class='bx bx-chevron-left text-2xl'></i>
                </span>
            @else
                <button data-page="{{ $articles->currentPage() - 1 }}" class="pagination-link w-12 h-12 flex items-center justify-center rounded-xl bg-white border border-brand-beige text-brand-red hover:bg-brand-red hover:text-white transition-all shadow-sm">
                    <i class='bx bx-chevron-left text-2xl'></i>
                </button>
            @endif

            @foreach ($articles->links()->elements[0] ?? [] as $page => $url)
                @if ($page == $articles->currentPage())
                    <span class="w-12 h-12 flex items-center justify-center rounded-xl bg-brand-red text-white font-bold shadow-lg shadow-brand-red/30">
                        {{ $page }}
                    </span>
                @else
                    <button data-page="{{ $page }}" class="pagination-link w-12 h-12 flex items-center justify-center rounded-xl bg-white border border-brand-beige text-gray-600 hover:border-brand-red hover:text-brand-red transition-all shadow-sm">
                        {{ $page }}
                    </button>
                @endif
            @endforeach

            @if ($articles->hasMorePages())
                <button data-page="{{ $articles->currentPage() + 1 }}" class="pagination-link w-12 h-12 flex items-center justify-center rounded-xl bg-white border border-brand-beige text-brand-red hover:bg-brand-red hover:text-white transition-all shadow-sm">
                    <i class='bx bx-chevron-right text-2xl'></i>
                </button>
            @else
                <span class="w-12 h-12 flex items-center justify-center rounded-xl bg-gray-100 text-gray-400 cursor-not-allowed">
                    <i class='bx bx-chevron-right text-2xl'></i>
                </span>
            @endif
        </nav>
    </div>
@endif
