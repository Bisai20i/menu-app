@extends('layouts.web')

@section('title', ($article->meta_title ?? $article->title) . ' - Saral Menu')
@section('meta_title', $article->meta_title ?? $article->title)
@section('meta_description', $article->meta_description ?? Str::limit(strip_tags($article->content), 160))
@section('meta_keywords', $article->meta_keywords)
@section('meta_image', $article->thumbnail ? Storage::url($article->thumbnail) : asset('frontend/images/logo.png'))

@section('content')
    <!-- ═══════════════ ARTICLE HERO ═══════════════ -->
    <header class="relative pt-32 pb-20 px-5 sm:px-8 md:px-12 lg:px-20 overflow-hidden">
        <div class="absolute top-[-10%] right-[-10%] w-[40%] h-[40%] text-brand-orange/10 pointer-events-none">
            <svg viewBox="0 0 200 200" fill="currentColor">
                <path d="M44.5,-76.3C57.4,-70.7,67.3,-58,74.7,-43.8C82.1,-29.7,87,-14.8,85.1,-0.6C83.2,13.6,74.5,27.2,65.3,39.9C56.1,52.6,46.4,64.4,34,71.1C21.6,77.8,6.5,79.4,-8.7,77.4C-23.9,75.4,-39.2,69.8,-51.7,60.8C-64.2,51.8,-74,39.4,-79.8,25.3C-85.6,11.2,-87.4,-4.6,-83.1,-19.1C-78.8,-33.6,-68.4,-46.8,-55.8,-52.4C-43.2,-58,-28.4,-56,-14.8,-60.7C-1.2,-65.4,11.2,-76.8,26.7,-81.4C42.2,-86,60.8,-83.8,44.5,-76.3Z" transform="translate(100 100)" />
            </svg>
        </div>

        <div class="max-w-4xl mx-auto relative z-10">
            <nav class="flex items-center gap-2 text-white/70 text-sm font-medium mb-8 reveal">
                <a href="/" class="hover:text-white transition-colors">Home</a>
                <i class='bx bx-chevron-right'></i>
                <a href="{{ route('articles.index') }}" class="hover:text-white transition-colors">Articles</a>
                <i class='bx bx-chevron-right'></i>
                <span class="text-brand-yellow truncate">{{ $article->title }}</span>
            </nav>

            <h1 class="font-atma font-bold text-4xl md:text-6xl mb-8 reveal stagger-1 leading-tight heading-shadow">
                {{ $article->title }}
            </h1>

            <div class="flex flex-wrap items-center gap-6 reveal stagger-2">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full border-2 border-brand-yellow overflow-hidden p-0.5">
                        <div class="w-full h-full bg-brand-red rounded-full flex items-center justify-center">
                            <i class='bx bxs-user-circle text-white text-3xl'></i>
                        </div>
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm">Saral Menu Team</p>
                        <p class="text-white/60 text-xs">Published on {{ $article->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
                <div class="hidden sm:block w-px h-10 bg-white/20"></div>
                <div class="flex items-center gap-4">
                    <span class="flex items-center gap-1.5 text-white/80 text-sm font-medium bg-white/10 px-3 py-1.5 rounded-lg border border-white/10">
                        <i class='bx bx-time text-brand-yellow'></i> 5 min read
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- ═══════════════ MAIN CONTENT ═══════════════ -->
    <main class="relative bg-white py-16 px-5 sm:px-8 md:px-12 lg:px-20 text-gray-900 border-t-8 border-brand-red">
        <div class="max-w-4xl mx-auto">
            @if($article->thumbnail)
                <div class="relative -mt-32 mb-16 rounded-[2.5rem] overflow-hidden shadow-2xl border-4 border-white reveal stagger-3">
                    <img src="{{ Storage::url($article->thumbnail) }}" alt="{{ $article->title }}" class="w-full aspect-[21/9] object-cover">
                </div>
            @endif

            <article class="article-content prose prose-lg max-w-none reveal stagger-4 prose-headings:font-atma prose-headings:font-bold prose-headings:text-brand-red prose-p:text-gray-600 prose-a:text-brand-red prose-img:rounded-3xl shadow-brand-red/10 prose-strong:text-gray-900">
                {!! $article->content !!}
            </article>

            <!-- Social Share & Navigation -->
            <div class="mt-16 pt-10 border-t border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-8 reveal">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-bold text-white/60 uppercase tracking-widest mr-2">Share:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                       target="_blank" rel="noopener noreferrer"
                       class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center border border-white/20 hover:bg-brand-orange hover:border-brand-orange hover:text-white transition-all duration-300"
                       title="Share on Facebook">
                        <i class='bx bxl-facebook text-xl'></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($article->title) }}" 
                       target="_blank" rel="noopener noreferrer"
                       class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center border border-white/20 hover:bg-brand-orange hover:border-brand-orange hover:text-white transition-all duration-300"
                       title="Share on Twitter">
                        <i class='bx bxl-twitter text-xl'></i>
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" 
                       target="_blank" rel="noopener noreferrer"
                       class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center border border-white/20 hover:bg-brand-orange hover:border-brand-orange hover:text-white transition-all duration-300"
                       title="Share on LinkedIn">
                        <i class='bx bxl-linkedin text-xl'></i>
                    </a>
                    <button id="copyLinkBtn" 
                            data-url="{{ url()->current() }}"
                            class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center border border-white/20 hover:bg-brand-orange hover:border-brand-orange hover:text-white transition-all duration-300"
                            title="Copy Link">
                        <i class='bx bx-link text-xl'></i>
                    </button>
                </div>
                <div class="flex items-center gap-2">
                    @foreach(explode(',', $article->meta_keywords) as $keyword)
                        @if(!empty(trim($keyword)))
                            <span class="bg-brand-lightBeige text-brand-red text-xs font-bold px-3 py-1.5 rounded-lg border border-brand-beige">{{ trim($keyword) }}</span>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </main>

    @if($similarArticles->isNotEmpty())
    <!-- ═══════════════ SIMILAR ARTICLES ═══════════════ -->
    <section class="bg-brand-lightBeige py-20 px-5 sm:px-8 md:px-12 lg:px-20">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between mb-12 reveal">
                <h2 class="font-atma font-bold text-3xl sm:text-4xl text-brand-red">Similar <span class="text-gray-900">Articles</span></h2>
                <a href="{{ route('articles.index') }}" class="font-bold text-brand-red hover:underline flex items-center gap-2">
                    View All <i class='bx bx-right-arrow-alt text-2xl'></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($similarArticles as $similar)
                    <article class="bg-white rounded-[2rem] overflow-hidden shadow-sm border border-brand-beige group hover:border-brand-red transition-all duration-300 reveal-scale stagger-{{ $loop->iteration }}">
                        <div class="aspect-video relative overflow-hidden bg-brand-lightBeige">
                            @if($similar->thumbnail)
                                <img src="{{ Storage::url($similar->thumbnail) }}" alt="{{ $similar->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class='bx bx-news text-4xl text-brand-red opacity-20'></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <h3 class="font-atma font-bold text-lg text-gray-900 mb-4 line-clamp-2 group-hover:text-brand-red transition-colors">
                                <a href="{{ route('articles.show', $similar->slug) }}">{{ $similar->title }}</a>
                            </h3>
                            <a href="{{ route('articles.show', $similar->slug) }}" class="text-brand-red font-bold text-xs uppercase tracking-widest flex items-center gap-1 group-hover:gap-2 transition-all">
                                Read More <i class='bx bx-right-arrow-alt text-lg'></i>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @include('web.partials.above-footer')
@endsection
