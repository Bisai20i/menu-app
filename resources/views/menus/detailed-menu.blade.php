<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>{{ $restaurant->name }} — Menu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet"/>

    @include('menus.partials.styles')
</head>
<body>

    @include('menus.partials.hero')
    @include('menus.partials.nav')

    <main class="py-5" style="min-height: 70vh;">
        <div class="container">
            <div class="row g-4">
                @foreach($categories as $category)
                    <div class="col-12" id="cat-{{ $category->id }}">
                        <div class="category-card">
                            <div class="category-header">
                                <span class="section-label">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }} — {{ $category->description ?? 'Discover' }}</span>
                                <h2>{!! preg_replace('/(\S+)$/', '<em>$1</em>', $category->name) !!}</h2>
                                <p class="category-desc mb-0">{{ $category->description }}</p>
                            </div>
                            <div class="menu-items">
                                @foreach($category->menuItems as $item)
                                    @include('menus.partials.menu-item', ['item' => $item])
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>

    @include('menus.partials.gallery')
    @include('menus.partials.footer')
    @include('menus.partials.scripts')

</body>
</html>