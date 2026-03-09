<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $restaurant->name }} — Menu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300;1,400&family=Jost:wght@300;400&display=swap"
        rel="stylesheet" />

    <style>
        :root {
            --gold: {{ $palette['primary'] }};
            --charcoal: #1e1a16;
        }

        body {
            background: var(--charcoal);
            font-family: 'Jost', sans-serif;
            font-weight: 300;
        }

        .hero {
            padding: 3.5rem 0 2.5rem;
            text-align: center;
        }

        .hero h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 300;
            color: #fff;
            text-transform: capitalize;
        }

        .hero h1 em {
            font-style: italic;
            color: var(--gold);
        }

        .gold-rule {
            border: none;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
            margin: 0;
        }

        /* Each menu page fills full width, stacked vertically */
        .menu-page {
            width: 100%;
        }

        .menu-page img {
            width: 100%;
            display: block;
        }

        .menu-page iframe {
            width: 100%;
            height: 100vh;
            display: block;
            border: none;
        }

        footer {
            padding: 2rem 0;
            text-align: center;
        }

        .footer-text {
            font-size: .68rem;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, .2);
        }

        .no-content {
            padding: 5rem 0;
            text-align: center;
            color: rgba(255, 255, 255, .3);
            text-transform: uppercase;
            letter-spacing: 0.2rem;
            font-size: 0.8rem;
        }
    </style>
</head>

<body>

    <header class="hero">
        <div class="container">
            <h1>
                @if(str_contains($restaurant->name, ' '))
                    @php
                        $parts = explode(' ', $restaurant->name, 2);
                    @endphp
                    {{ $parts[0] }} <em>{{ $parts[1] }}</em>
                @else
                    <em>{{ $restaurant->name }}</em>
                @endif
            </h1>
        </div>
    </header>

    <hr class="gold-rule">

    <div class="container-fluid px-0">
        @forelse($galleryImages as $image)
            <div class="menu-page">
                @if($image->media_type === 'application/pdf')
                    <iframe src="{{ $image->display_url }}" title="Menu Page"></iframe>
                @else
                    <img src="{{ $image->display_url }}" alt="Menu Page" loading="lazy">
                @endif
            </div>
        @empty
            <div class="no-content">
                No menu pages available
            </div>
        @endforelse
    </div>

    <hr class="gold-rule">

    <footer>
        <p class="footer-text mb-0">
            {{ $restaurant->name }} &nbsp;·&nbsp; {{ $restaurant->address ?? '' }}
        </p>
    </footer>

</body>

</html>

</html>
