<style>
    /* ── Design Tokens ── */
    :root {
        --cream:    #faf6f0;
        --parchment:#f0e8d8;
        --gold:     {{ $palette['primary'] }};
        --gold-lt:  {{ $palette['safe_accent'] }};
        --gold-safe: {{ $palette['safe_primary'] }};
        --gold-contrast: {{ $palette['contrast_text'] }};
        --charcoal: {{ $palette['deep'] }};
        --muted:    #7a6e63;
        --border:   rgba({{ $palette['primary_rgb'] }}, .25);
        --card-bg:  #ffffff;
        --radius:   4px;
    }

    /* ── Base ── */
    body {
        background-color: var(--cream);
        font-family: 'Jost', sans-serif;
        color: var(--charcoal);
        font-weight: 300;
    }
    h1,h2,h3,h4,h5 { font-family: 'Cormorant Garamond', serif; }

    /* ── Hero ── */
    .hero {
        background-color: var(--charcoal);
        padding: 5rem 0 4rem;
        position: relative;
        overflow: hidden;
    }
    .hero::before {
        content: '';
        position: absolute; inset: 0;
        background:
            radial-gradient(ellipse 80% 60% at 50% 0%, rgba(184,145,42,.18) 0%, transparent 70%);
    }
    .hero-rule {
        width: 56px; height: 1px;
        background: var(--gold);
        margin: 0 auto 1.25rem;
    }
    .hero-eyebrow {
        font-family: 'Jost', sans-serif;
        font-size: .72rem;
        letter-spacing: .22em;
        text-transform: uppercase;
        color: var(--gold-lt);
    }
    .hero h1 {
        font-size: clamp(2.8rem, 6vw, 5rem);
        font-weight: 300;
        color: #fff;
        line-height: 1.05;
    }
    .hero h1 em { font-style: italic; color: var(--gold-lt); }
    .hero-sub {
        color: rgba(255,255,255,.55);
        font-size: .9rem;
        letter-spacing: .06em;
    }

    /* ── Section label ── */
    .section-label {
        font-family: 'Jost', sans-serif;
        font-size: .65rem;
        letter-spacing: .22em;
        text-transform: uppercase;
        color: var(--gold);
        display: block;
        margin-bottom: .35rem;
    }

    /* ── Category Nav Pills ── */
    .cat-nav { gap: .5rem; }
    .cat-nav .btn {
        font-family: 'Jost', sans-serif;
        font-size: .78rem;
        letter-spacing: .1em;
        text-transform: uppercase;
        border-radius: 2px;
        padding: .45rem 1.1rem;
        border: 1px solid var(--border);
        color: var(--muted);
        background: transparent;
        transition: all .2s;
    }
    .cat-nav .btn:hover,
    .cat-nav .btn.active {
        background: var(--gold);
        border-color: var(--gold);
        color: var(--gold-contrast);
    }

    /* ── Category Card ── */
    .category-card {
        border: 1px solid var(--border);
        border-radius: var(--radius);
        background: var(--card-bg);
        overflow: hidden;
        transition: box-shadow .25s;
    }
    .category-card:hover { box-shadow: 0 8px 32px rgba(30,26,22,.1); }

    .category-header {
        background: var(--gold);
        padding: 1.6rem 1.75rem 1.4rem;
        position: relative;
    }
    .category-header::after {
        content: '';
        position: absolute; bottom: 0; left: 1.75rem; right: 1.75rem;
        height: 1px;
        background: rgba(255, 255, 255, 0.2);
    }
    .category-header .section-label {
        color: var(--gold-contrast);
        opacity: 0.8;
    }
    .category-header h2 {
        font-size: 1.7rem;
        font-weight: 300;
        color: var(--gold-contrast);
        margin: 0;
        line-height: 1.1;
    }
    .category-header h2 em { font-style: italic; color: var(--gold-contrast); opacity: 0.9; }
    .category-desc {
        color: var(--gold-contrast);
        opacity: 0.7;
        font-size: .83rem;
        margin: .5rem 0 0;
        letter-spacing: .03em;
    }

    /* ── Menu Item Row ── */
    .menu-items { padding: .25rem 0; }
    .menu-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1.1rem 1.75rem;
        border-bottom: 1px solid rgba(0,0,0,.05);
        transition: background .15s;
    }
    .menu-item:last-child { border-bottom: none; }
    .menu-item:hover { background: rgba(184,145,42,.04); }

    .item-img-wrap {
        flex-shrink: 0;
        width: 72px; height: 72px;
        border-radius: var(--radius);
        overflow: hidden;
        border: 1px solid var(--border);
        position: relative;
        cursor: pointer;
    }
    .item-img-placeholder {
        width: 100%; height: 100%;
        background: var(--parchment);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 3px;
        color: var(--gold);
        font-size: .6rem;
        letter-spacing: .05em;
        text-transform: uppercase;
    }
    .item-img-placeholder i { font-size: 1.4rem; opacity: .7; }
    /* When a real image is present */
    .item-img-wrap img {
        width: 100%; height: 100%;
        object-fit: cover;
    }

    /* PDF badge */
    .pdf-badge {
        position: absolute; top: 3px; right: 3px;
        background: #e74c3c;
        color: #fff;
        font-size: .5rem;
        font-family: 'Jost', sans-serif;
        font-weight: 500;
        letter-spacing: .05em;
        padding: 1px 4px;
        border-radius: 2px;
    }

    .item-body { flex: 1; min-width: 0; }
    .item-name {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.15rem;
        font-weight: 600;
        line-height: 1.2;
        margin: 0 0 .2rem;
        color: var(--charcoal);
    }
    .item-desc {
        font-size: .82rem;
        color: var(--muted);
        margin: 0;
        line-height: 1.5;
    }

    .item-price {
        flex-shrink: 0;
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.2rem;
        font-weight: 400;
        color: var(--gold);
        white-space: nowrap;
        padding-top: .15rem;
    }

    /* ── Image Gallery Section ── */
    .gallery-section { background: var(--parchment); }
    .gallery-heading {
        font-size: clamp(1.8rem, 4vw, 2.8rem);
        font-weight: 300;
    }
    .gallery-heading em { font-style: italic; color: var(--gold-safe); }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 1rem;
    }

    .gallery-card {
        border-radius: var(--radius);
        overflow: hidden;
        border: 1px solid var(--border);
        background: var(--card-bg);
        transition: transform .2s, box-shadow .2s;
        cursor: pointer;
    }
    .gallery-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 32px rgba(30,26,22,.12);
    }
    .gallery-thumb {
        width: 100%;
        aspect-ratio: 4/3;
        background: #e8e0d4;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: .5rem;
        color: var(--gold);
        position: relative;
    }
    .gallery-thumb i { font-size: 2.5rem; opacity: .6; }
    .gallery-thumb .thumb-label {
        font-family: 'Jost', sans-serif;
        font-size: .65rem;
        letter-spacing: .18em;
        text-transform: uppercase;
        color: var(--muted);
    }
    .gallery-thumb .thumb-type-badge {
        position: absolute; top: .6rem; left: .6rem;
        padding: 2px 8px;
        border-radius: 2px;
        font-size: .62rem;
        font-family: 'Jost', sans-serif;
        font-weight: 500;
        letter-spacing: .07em;
        text-transform: uppercase;
    }
    .badge-img  { background: var(--gold); color: #fff; }
    .badge-pdf  { background: #c0392b; color: #fff; }

    .gallery-info {
        padding: .75rem 1rem;
        border-top: 1px solid var(--border);
    }
    .gallery-info .gi-name {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1rem;
        font-weight: 600;
        margin: 0 0 .15rem;
    }
    .gallery-info .gi-meta {
        font-size: .75rem;
        color: var(--muted);
        margin: 0;
    }

    /* ── Footer divider ── */
    .gold-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--gold), transparent);
        border: none;
        margin: 0;
    }
    .footer-text {
        font-size: .75rem;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: var(--muted);
    }

    /* ── Responsive tweaks ── */
    @media (max-width: 576px) {
        .menu-item { padding: .9rem 1rem; }
        .category-header { padding: 1.2rem 1rem; }
        .item-img-wrap { width: 56px; height: 56px; }
    }
</style>
