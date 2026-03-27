@extends('layouts.web')

@section('title', 'Explore Our Articles - Saral Menu')
@section('meta_description', 'Discover useful articles about restaurant management, digital menus, and business growth.')
@section('meta_keywords', 'restaurant tips, digital menu, business growth, food industry')

@section('content')
    <!-- ═══════════════ HERO SECTION ═══════════════ -->
    <header class="relative pt-32 pb-20 px-5 sm:px-8 md:px-12 lg:px-20 overflow-hidden">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] text-brand-red/10 pointer-events-none">
            <svg viewBox="0 0 200 200" fill="currentColor">
                <path d="M44.5,-76.3C57.4,-70.7,67.3,-58,74.7,-43.8C82.1,-29.7,87,-14.8,85.1,-0.6C83.2,13.6,74.5,27.2,65.3,39.9C56.1,52.6,46.4,64.4,34,71.1C21.6,77.8,6.5,79.4,-8.7,77.4C-23.9,75.4,-39.2,69.8,-51.7,60.8C-64.2,51.8,-74,39.4,-79.8,25.3C-85.6,11.2,-87.4,-4.6,-83.1,-19.1C-78.8,-33.6,-68.4,-46.8,-55.8,-52.4C-43.2,-58,-28.4,-56,-14.8,-60.7C-1.2,-65.4,11.2,-76.8,26.7,-81.4C42.2,-86,60.8,-83.8,44.5,-76.3Z" transform="translate(100 100)" />
            </svg>
        </div>

        <div class="max-w-7xl mx-auto relative z-10 text-center">
            <h1 class="font-atma font-bold text-5xl md:text-7xl mb-6 reveal uppercase tracking-tight heading-shadow">
                Our <span class="text-brand-yellow">Blog</span> & <span class="text-brand-orange">Articles</span>
            </h1>
            <p class="max-w-2xl mx-auto text-white/90 text-lg md:text-xl font-medium reveal stagger-1 mb-12">
                Insights, tips, and updates to help you grow your restaurant business in the digital age.
            </p>

            <!-- Search Area -->
            <div class="max-w-xl mx-auto relative reveal stagger-2">
                <div class="bg-white rounded-2xl p-2 flex items-center shadow-2xl border border-brand-beige">
                    <div class="pl-4 pr-2 text-brand-red text-2xl flex items-center">
                        <i class='bx bx-search'></i>
                    </div>
                    <input type="text" id="searchInput" placeholder="Search for articles..." 
                        class="w-full py-3 px-2 bg-transparent text-gray-900 border-none focus:outline-none font-medium placeholder:text-gray-400">
                    <button id="searchBtn" class="bg-brand-red text-white font-bold px-8 py-3 rounded-xl hover:bg-brand-darkRed transition-colors btn-hover flex items-center justify-center">
                        Search
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- ═══════════════ ARTICLES GRID ═══════════════ -->
    <section class="relative bg-brand-lightBeige py-20 px-5 sm:px-8 md:px-12 lg:px-20 text-gray-900 min-h-[600px]">
        <div class="absolute top-0 left-0 w-full h-20 bg-gradient-to-b from-transparent to-brand-lightBeige opacity-50 -translate-y-full pointer-events-none"></div>
        
        <div class="max-w-7xl mx-auto">
            <div id="articlesContainer" class="relative">
                @include('web.articles.article-list')
                
                <!-- Loading Overlay -->
                <div id="loadingOverlay" class="absolute inset-0 bg-white/50 backdrop-blur-sm z-20 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300">
                    <div class="flex flex-col items-center">
                        <div class="w-16 h-16 border-4 border-brand-red border-t-transparent rounded-full animate-spin"></div>
                        <p class="mt-4 font-atma font-bold text-brand-red">Searching articles...</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('web.partials.above-footer')
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const searchBtn = document.getElementById('searchBtn');
        const articlesContainer = document.getElementById('articlesContainer');
        const loadingOverlay = document.getElementById('loadingOverlay');

        let currentSearch = '';
        let currentPage = 1;
        let searchTimeout;

        function fetchArticles(page = 1, search = '') {
            currentPage = page;
            currentSearch = search;
            
            loadingOverlay.classList.remove('opacity-0', 'pointer-events-none');
            
            const url = new URL(window.location.origin + window.location.pathname);
            url.searchParams.set('page', page);
            if (search) {
                url.searchParams.set('search', search);
            }

            // Update URL bar without refreshing
            window.history.pushState({}, '', url);

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                articlesContainer.innerHTML = html;
                // Re-append loading overlay to container since it's wiped with innerHTML
                articlesContainer.appendChild(loadingOverlay);
                
                // Initialize events for new content
                initEvents();
                
                // Scroll to top of section
                document.querySelector('section').scrollIntoView({ behavior: 'smooth' });
                
                setTimeout(() => {
                    loadingOverlay.classList.add('opacity-0', 'pointer-events-none');
                }, 300);
            })
            .catch(error => {
                console.error('Error fetching articles:', error);
                loadingOverlay.classList.add('opacity-0', 'pointer-events-none');
            });
        }

        function initEvents() {
            // Pagination links
            document.querySelectorAll('.pagination-link').forEach(link => {
                link.addEventListener('click', function() {
                    const page = this.getAttribute('data-page');
                    fetchArticles(page, searchInput.value);
                });
            });
        }

        searchBtn.addEventListener('click', () => {
            fetchArticles(1, searchInput.value);
        });

        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                fetchArticles(1, searchInput.value);
            }, 500);
        });

        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                fetchArticles(1, searchInput.value);
            }
        });

        initEvents();
    });
</script>
@endpush
