<header class="hero text-center">
    <div class="container position-relative">
        <div class="hero-rule"></div>
        <p class="hero-eyebrow mb-2">Welcome to</p>
        <h1>{!! str_replace(' ', ' <em>', $restaurant->name) . (str_contains($restaurant->name, ' ') ? '</em>' : '') !!}</h1>
        <p class="hero-sub mt-2">{{ $restaurant->address ?? 'Seasonal menu — crafted with intention' }}</p>
    </div>
</header>
