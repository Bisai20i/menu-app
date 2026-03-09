<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Saveur – Restaurant Menu</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700;900&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,400&display=swap"
        rel="stylesheet" />

    <style>
        :root {
            --brand: #C0392B;
            --brand-dark: #96281B;
            --brand-light: #FDECEA;
            --gold: #E2A84B;
            --surface: #F7F6F3;
            --card-bg: #FFFFFF;
            --text-primary: #1A1A1A;
            --text-muted: #7A7775;
            --border: #EBEBEA;
            --nav-h: 68px;
            --sidebar-w: 260px;
            --radius-card: 16px;
            --radius-pill: 50px;
            --shadow-card: 0 2px 16px rgba(0, 0, 0, .07);
            --shadow-hero: 0 14px 40px rgba(192, 57, 43, .22);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--surface);
            color: var(--text-primary);
            min-height: 100vh;
        }

        /* ═══════════════════════ NAVBAR ═══════════════════════ */
        .site-nav {
            height: var(--nav-h);
            background: #fff;
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 16px rgba(0, 0, 0, .05);
        }

        .site-nav .inner {
            max-width: 1400px;
            margin: 0 auto;
            height: 100%;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            flex-shrink: 0;
        }

        .nav-logo {
            width: 44px;
            height: 44px;
            background: var(--brand);
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.3rem;
            box-shadow: 0 4px 12px rgba(192, 57, 43, .35);
        }

        .nav-brand-text h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--brand);
            line-height: 1.15;
        }

        .nav-brand-text small {
            font-size: .7rem;
            color: var(--text-muted);
            font-weight: 400;
            display: block;
        }

        .nav-search {
            flex: 1;
            max-width: 420px;
            position: relative;
        }

        .nav-search input {
            width: 100%;
            padding: 10px 16px 10px 40px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-pill);
            font-family: 'DM Sans', sans-serif;
            font-size: .85rem;
            background: var(--surface);
            color: var(--text-primary);
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }

        .nav-search input:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(192, 57, 43, .1);
        }

        .nav-search .search-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: .95rem;
            pointer-events: none;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-btn {
            width: 40px;
            height: 40px;
            border: none;
            border-radius: 12px;
            background: var(--surface);
            color: var(--text-muted);
            font-size: 1.05rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            position: relative;
            transition: background .2s, color .2s, transform .15s;
        }

        .nav-btn:hover {
            background: var(--brand-light);
            color: var(--brand);
            transform: scale(1.05);
        }

        .nav-btn .badge-dot {
            position: absolute;
            top: 7px;
            right: 7px;
            width: 8px;
            height: 8px;
            background: var(--brand);
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .table-badge {
            display: flex;
            align-items: center;
            gap: 6px;
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-pill);
            padding: 6px 14px;
            font-size: .78rem;
            font-weight: 600;
            color: var(--text-muted);
            white-space: nowrap;
        }

        .table-badge i {
            color: var(--brand);
        }

        .nav-cart-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            background: var(--brand);
            color: #fff;
            border: none;
            border-radius: var(--radius-pill);
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
            font-size: .83rem;
            cursor: pointer;
            white-space: nowrap;
            box-shadow: 0 4px 14px rgba(192, 57, 43, .3);
            transition: background .2s, transform .15s, box-shadow .2s;
        }

        .nav-cart-btn:hover {
            background: var(--brand-dark);
            transform: translateY(-1px);
        }

        .nav-cart-btn .count-bubble {
            background: rgba(255, 255, 255, .25);
            border-radius: 20px;
            padding: 1px 7px;
            font-size: .75rem;
            font-weight: 700;
        }

        .nav-toggler {
            display: none;
            background: none;
            border: none;
            font-size: 1.4rem;
            color: var(--text-primary);
            cursor: pointer;
            padding: 4px;
        }

        /* ═══════════════════════ LAYOUT ═══════════════════════ */
        .page-layout {
            max-width: 1400px;
            margin: 0 auto;
            padding: 28px 24px 100px;
            display: grid;
            grid-template-columns: var(--sidebar-w) 1fr;
            gap: 28px;
            align-items: start;
        }

        /* ═══════════════════════ SIDEBAR ═══════════════════════ */
        .sidebar {
            position: sticky;
            top: calc(var(--nav-h) + 20px);
        }

        .sidebar-card {
            background: #fff;
            border-radius: var(--radius-card);
            border: 1px solid var(--border);
            overflow: hidden;
            box-shadow: var(--shadow-card);
            margin-bottom: 16px;
        }

        .sidebar-card-head {
            padding: 16px 18px 12px;
            border-bottom: 1px solid var(--border);
        }

        .sidebar-card-head h6 {
            font-family: 'Playfair Display', serif;
            font-size: .92rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .cat-list {
            padding: 8px 0;
        }

        .cat-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 18px;
            cursor: pointer;
            font-size: .85rem;
            font-weight: 500;
            color: var(--text-muted);
            border-left: 3px solid transparent;
            transition: background .15s, color .15s;
        }

        .cat-item:hover {
            background: var(--surface);
            color: var(--text-primary);
        }

        .cat-item.active {
            background: var(--brand-light);
            color: var(--brand);
            border-left-color: var(--brand);
            font-weight: 600;
        }

        .cat-item .cat-emoji {
            font-size: 1rem;
            width: 22px;
            text-align: center;
        }

        .cat-item .cat-count {
            margin-left: auto;
            font-size: .7rem;
            background: var(--border);
            border-radius: 10px;
            padding: 1px 7px;
            color: var(--text-muted);
        }

        .cat-item.active .cat-count {
            background: rgba(192, 57, 43, .15);
            color: var(--brand);
        }

        .info-rows {
            padding: 6px 0 4px;
        }

        .info-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 18px;
            font-size: .8rem;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-row i {
            color: var(--brand);
            font-size: .95rem;
            width: 16px;
            text-align: center;
        }

        .info-row strong {
            color: var(--text-primary);
            margin-left: auto;
            font-weight: 600;
        }

        .btn-waiter-side {
            width: 100%;
            padding: 11px;
            border: 1.5px solid var(--brand);
            background: transparent;
            color: var(--brand);
            border-radius: 12px;
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
            font-size: .85rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            transition: background .2s, color .2s;
        }

        .btn-waiter-side:hover {
            background: var(--brand-light);
        }

        /* ═══════════════════════ MAIN ═══════════════════════ */
        .main-content {
            min-width: 0;
        }

        .promo-strip {
            background: linear-gradient(135deg, #C0392B 0%, #8B1E15 100%);
            border-radius: var(--radius-card);
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            color: #fff;
            margin-bottom: 24px;
        }

        .promo-icon {
            width: 42px;
            height: 42px;
            background: rgba(255, 255, 255, .15);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .promo-strip h6 {
            font-weight: 700;
            margin: 0 0 2px;
            font-size: .92rem;
        }

        .promo-strip p {
            margin: 0;
            font-size: .75rem;
            opacity: .85;
        }

        .promo-cta {
            margin-left: auto;
            flex-shrink: 0;
            background: rgba(255, 255, 255, .2);
            border: 1px solid rgba(255, 255, 255, .35);
            color: #fff;
            border-radius: var(--radius-pill);
            padding: 6px 16px;
            font-size: .78rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s;
            white-space: nowrap;
        }

        .promo-cta:hover {
            background: rgba(255, 255, 255, .3);
        }

        .section-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -.01em;
        }

        .see-all {
            font-size: .8rem;
            font-weight: 600;
            color: var(--brand);
            text-decoration: none;
        }

        .see-all:hover {
            text-decoration: underline;
        }

        /* Hero */
        .hero-card {
            border-radius: var(--radius-card);
            overflow: hidden;
            position: relative;
            cursor: pointer;
            box-shadow: var(--shadow-hero);
            margin-bottom: 32px;
            transition: transform .3s;
        }

        .hero-card:hover {
            transform: translateY(-3px);
        }

        .hero-card img {
            width: 100%;
            height: 260px;
            object-fit: cover;
            display: block;
            transition: transform .5s;
        }

        .hero-card:hover img {
            transform: scale(1.03);
        }

        .hero-gradient {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(8, 3, 0, .85) 0%, rgba(0, 0, 0, 0) 55%);
        }

        .hero-body {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px 22px 18px;
        }

        .hero-body h4 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.45rem;
            color: #fff;
            margin: 0 0 4px;
        }

        .hero-body p {
            font-size: .8rem;
            color: rgba(255, 255, 255, .75);
            margin: 0;
            max-width: 400px;
        }

        .hero-badge {
            position: absolute;
            top: 14px;
            left: 14px;
            background: var(--gold);
            color: #fff;
            font-size: .7rem;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 20px;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .hero-price {
            position: absolute;
            bottom: 22px;
            right: 66px;
            font-size: 1.1rem;
            font-weight: 700;
            color: #fff;
        }

        .hero-cart-btn {
            position: absolute;
            bottom: 16px;
            right: 16px;
            width: 44px;
            height: 44px;
            background: var(--gold);
            border: none;
            border-radius: 50%;
            color: #fff;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(226, 168, 75, .5);
            transition: transform .2s, background .2s;
        }

        .hero-cart-btn:hover {
            transform: scale(1.1);
            background: #c8922e;
        }

        /* Mobile category pills (hidden on desktop) */
        .mobile-cats {
            display: none;
            overflow-x: auto;
            white-space: nowrap;
            padding-bottom: 12px;
            margin-bottom: 20px;
            scrollbar-width: none;
        }

        .mobile-cats::-webkit-scrollbar {
            display: none;
        }

        .cat-pill {
            display: inline-block;
            padding: 7px 18px;
            border-radius: var(--radius-pill);
            font-size: .8rem;
            font-weight: 600;
            cursor: pointer;
            margin-right: 8px;
            border: 1.5px solid var(--border);
            background: #fff;
            color: var(--text-muted);
            transition: all .2s;
            user-select: none;
        }

        .cat-pill.active,
        .cat-pill:hover {
            background: var(--brand);
            border-color: var(--brand);
            color: #fff;
            box-shadow: 0 4px 12px rgba(192, 57, 43, .3);
        }

        /* Menu grid */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 18px;
        }

        /* Menu card */
        .menu-card {
            background: var(--card-bg);
            border-radius: var(--radius-card);
            overflow: hidden;
            box-shadow: var(--shadow-card);
            border: 1px solid var(--border);
            cursor: pointer;
            transition: transform .25s, box-shadow .25s;
            animation: fadeUp .4s ease both;
        }

        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 36px rgba(0, 0, 0, .11);
        }

        .menu-card:nth-child(1) {
            animation-delay: .04s
        }

        .menu-card:nth-child(2) {
            animation-delay: .09s
        }

        .menu-card:nth-child(3) {
            animation-delay: .14s
        }

        .menu-card:nth-child(4) {
            animation-delay: .19s
        }

        .menu-card:nth-child(5) {
            animation-delay: .24s
        }

        .menu-card:nth-child(6) {
            animation-delay: .29s
        }

        .menu-card .img-wrap {
            position: relative;
            overflow: hidden;
        }

        .menu-card img {
            width: 100%;
            height: 130px;
            object-fit: cover;
            display: block;
            transition: transform .4s;
        }

        .menu-card:hover img {
            transform: scale(1.06);
        }

        .veg-dot {
            position: absolute;
            top: 8px;
            left: 8px;
            width: 16px;
            height: 16px;
            border-radius: 3px;
            border: 1.5px solid #22a55b;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .veg-dot::after {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #22a55b;
        }

        .card-body-inner {
            padding: 12px 12px 14px;
        }

        .item-name {
            font-weight: 600;
            font-size: .88rem;
            color: var(--text-primary);
            margin: 0 0 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .item-desc {
            font-size: .72rem;
            color: var(--text-muted);
            margin: 0 0 7px;
        }

        .rating-chip {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            background: #FFF8EE;
            border: 1px solid #F5D78A;
            border-radius: 6px;
            padding: 2px 7px;
            font-size: .68rem;
            font-weight: 600;
            color: #B07C1A;
        }

        .rating-chip i {
            font-size: .62rem;
            color: var(--gold);
        }

        .price-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 10px;
        }

        .price {
            font-weight: 700;
            font-size: .92rem;
            color: var(--text-primary);
        }

        .price span {
            font-size: .72rem;
            font-weight: 500;
            color: var(--text-muted);
            margin-right: 1px;
        }

        .add-btn {
            width: 32px;
            height: 32px;
            background: var(--brand);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: .9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background .2s, transform .15s;
        }

        .add-btn:hover {
            background: var(--brand-dark);
            transform: scale(1.08);
        }

        .add-btn.added {
            background: #22a55b;
        }

        .qty-stepper {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .qty-btn {
            width: 26px;
            height: 26px;
            border: 1.5px solid var(--brand);
            background: transparent;
            color: var(--brand);
            border-radius: 8px;
            font-size: .9rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background .15s, color .15s;
        }

        .qty-btn:hover {
            background: var(--brand);
            color: #fff;
        }

        .qty-count {
            font-size: .85rem;
            font-weight: 700;
            color: var(--text-primary);
            min-width: 16px;
            text-align: center;
        }

        /* ═══════════════════════ MOBILE BOTTOM BAR ═══════════════════════ */
        .bottom-bar {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #fff;
            border-top: 1px solid var(--border);
            padding: 12px 16px 20px;
            z-index: 500;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, .07);
        }

        .btn-orders {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: var(--brand);
            color: #fff;
            border: none;
            border-radius: 14px;
            padding: 14px;
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
            font-size: .92rem;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(192, 57, 43, .35);
            transition: background .2s;
            position: relative;
        }

        .btn-orders:hover {
            background: var(--brand-dark);
        }

        .btn-orders .cart-count {
            position: absolute;
            right: 16px;
            background: #fff;
            color: var(--brand);
            border-radius: 50%;
            width: 26px;
            height: 26px;
            font-size: .76rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-waiter-mob {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: transparent;
            color: var(--brand);
            border: 1.5px solid var(--brand);
            border-radius: 14px;
            padding: 12px;
            margin-top: 10px;
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
            font-size: .88rem;
            cursor: pointer;
            transition: background .2s;
        }

        .btn-waiter-mob:hover {
            background: var(--brand-light);
        }

        /* ═══════════════════════ ANIMATIONS ═══════════════════════ */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.03);
            }

            100% {
                transform: scale(1);
            }
        }

        /* ═══════════════════════ RESPONSIVE ═══════════════════════ */
        @media (max-width: 992px) {
            .page-layout {
                grid-template-columns: 1fr;
                padding: 20px 16px 100px;
            }

            .sidebar {
                display: none;
            }

            .mobile-cats {
                display: block;
            }

            .bottom-bar {
                display: block;
            }

            .nav-search {
                max-width: 260px;
            }

            .table-badge {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .site-nav .inner {
                padding: 0 14px;
            }

            .nav-search {
                display: none;
            }

            .nav-toggler {
                display: block;
            }

            .nav-cart-btn .btn-label {
                display: none;
            }

            .menu-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .hero-card img {
                height: 200px;
            }

            .hero-body h4 {
                font-size: 1.1rem;
            }
        }

        @media (min-width: 1200px) {
            .hero-card img {
                height: 300px;
            }

            .menu-grid {
                grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="site-nav">
        <div class="inner">
            <a class="nav-brand" href="#">
                <div class="nav-logo"><i class="bi bi-fire"></i></div>
                <div class="nav-brand-text">
                    <h1>Saveur Kitchen</h1>
                    <small>Your go-to place</small>
                </div>
            </a>

            <div class="nav-search">
                <i class="bi bi-search search-icon"></i>
                <input type="text" placeholder="Search dishes, cuisines…" />
            </div>

            <div class="nav-actions">
                <div class="table-badge"><i class="bi bi-geo-alt-fill"></i> Table 4</div>

                <button class="nav-btn" title="Notifications">
                    <i class="bi bi-bell"></i>
                    <span class="badge-dot"></span>
                </button>

                <button class="nav-btn" title="Account">
                    <i class="bi bi-person"></i>
                </button>

                <button class="nav-cart-btn" id="navCartBtn" onclick="openCart()">
                    <i class="bi bi-bag-check-fill"></i>
                    <span class="btn-label">Your Orders</span>
                    <span class="count-bubble" id="navCartCount">0</span>
                </button>

                <button class="nav-toggler" title="Menu"><i class="bi bi-list"></i></button>
            </div>
        </div>
    </nav>

    <!-- PAGE LAYOUT -->
    <div class="page-layout">

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="sidebar-card">
                <div class="sidebar-card-head">
                    <h6>Browse Menu</h6>
                </div>
                <div class="cat-list">
                    <div class="cat-item active" onclick="filterCat(this,'all')">
                        <span class="cat-emoji">🍽</span> All Items <span class="cat-count">18</span>
                    </div>
                    <div class="cat-item" onclick="filterCat(this,'appetizer')">
                        <span class="cat-emoji">🥗</span> Appetizer <span class="cat-count">5</span>
                    </div>
                    <div class="cat-item" onclick="filterCat(this,'main')">
                        <span class="cat-emoji">🍛</span> Main Course <span class="cat-count">7</span>
                    </div>
                    <div class="cat-item" onclick="filterCat(this,'beverage')">
                        <span class="cat-emoji">🥤</span> Beverages <span class="cat-count">4</span>
                    </div>
                    <div class="cat-item" onclick="filterCat(this,'dessert')">
                        <span class="cat-emoji">🍰</span> Dessert <span class="cat-count">2</span>
                    </div>
                </div>
            </div>

            <div class="sidebar-card">
                <div class="sidebar-card-head">
                    <h6>Restaurant Info</h6>
                </div>
                <div class="info-rows">
                    <div class="info-row"><i class="bi bi-clock"></i> Open Now <strong>11AM – 11PM</strong></div>
                    <div class="info-row"><i class="bi bi-geo-alt"></i> Location <strong>Hall B, Floor 2</strong></div>
                    <div class="info-row"><i class="bi bi-star-fill" style="color:var(--gold)"></i> Rating <strong>4.7 /
                            5.0</strong></div>
                    <div class="info-row"><i class="bi bi-lightning-charge"></i> Avg. Wait <strong>~12 min</strong>
                    </div>
                </div>
            </div>

            <button class="btn-waiter-side">
                <i class="bi bi-person-raised-hand"></i> Call a Waiter
            </button>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content">

            <div class="promo-strip">
                <div class="promo-icon">🎉</div>
                <div>
                    <h6>Happy Hours – 20% Off!</h6>
                    <p>On all starters till 8 PM tonight</p>
                </div>
                <button class="promo-cta">Claim Now</button>
            </div>

            <!-- Mobile category pills -->
            <div class="mobile-cats">
                <span class="cat-pill active" onclick="filterCatMob(this,'all')">🍽 All</span>
                <span class="cat-pill" onclick="filterCatMob(this,'appetizer')">🥗 Appetizer</span>
                <span class="cat-pill" onclick="filterCatMob(this,'main')">🍛 Main Course</span>
                <span class="cat-pill" onclick="filterCatMob(this,'beverage')">🥤 Beverages</span>
                <span class="cat-pill" onclick="filterCatMob(this,'dessert')">🍰 Dessert</span>
            </div>

            <!-- Today's Special -->
            <div class="section-row">
                <span class="section-title">Today's Special</span>
                <a href="#" class="see-all">See all specials →</a>
            </div>

            <div class="hero-card" onclick="addHero(this)">
                <img src="https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=1200&q=80"
                    alt="Signature Chicken Biryani" />
                <div class="hero-gradient"></div>
                <span class="hero-badge">⭐ Chef's Pick</span>
                <div class="hero-body">
                    <h4>Signature Chicken Biryani</h4>
                    <p>Slow-cooked dum biryani with saffron, crispy onions &amp; house raita</p>
                </div>
                <span class="hero-price"><small style="font-size:.75rem;opacity:.8">Rs </small>2,200</span>
                <button class="hero-cart-btn" title="Add to cart"><i class="bi bi-bag-plus-fill"></i></button>
            </div>

            <!-- Our Menu -->
            <div class="section-row">
                <span class="section-title">Our Menu</span>
                <a href="#" class="see-all">See all →</a>
            </div>

            <div class="menu-grid" id="menuGrid">

                <div class="menu-card" data-cat="appetizer">
                    <div class="img-wrap">
                        <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=500&q=75"
                            alt="Stuffed Mushroom" />
                        <span class="veg-dot"></span>
                    </div>
                    <div class="card-body-inner">
                        <p class="item-name">Stuffed Mushroom</p>
                        <p class="item-desc">Served with Ketchup</p>
                        <div class="rating-chip"><i class="bi bi-star-fill"></i> 4.5 (128)</div>
                        <div class="price-row">
                            <p class="price mb-0"><span>Rs </span>1,400</p>
                            <button class="add-btn" onclick="toggleAdd(event,this)"><i
                                    class="bi bi-plus-lg"></i></button>
                        </div>
                    </div>
                </div>

                <div class="menu-card" data-cat="appetizer">
                    <div class="img-wrap">
                        <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=500&q=75"
                            alt="Crispy Calamari" />
                    </div>
                    <div class="card-body-inner">
                        <p class="item-name">Crispy Calamari</p>
                        <p class="item-desc">With garlic aioli dip</p>
                        <div class="rating-chip"><i class="bi bi-star-fill"></i> 4.3 (84)</div>
                        <div class="price-row">
                            <p class="price mb-0"><span>Rs </span>1,200</p>
                            <button class="add-btn" onclick="toggleAdd(event,this)"><i
                                    class="bi bi-plus-lg"></i></button>
                        </div>
                    </div>
                </div>

                <div class="menu-card" data-cat="main">
                    <div class="img-wrap">
                        <img src="https://images.unsplash.com/photo-1467003909585-2f8a72700288?w=500&q=75"
                            alt="Grilled Salmon" />
                    </div>
                    <div class="card-body-inner">
                        <p class="item-name">Grilled Salmon</p>
                        <p class="item-desc">Herb butter &amp; roasted veggies</p>
                        <div class="rating-chip"><i class="bi bi-star-fill"></i> 4.7 (210)</div>
                        <div class="price-row">
                            <p class="price mb-0"><span>Rs </span>2,800</p>
                            <button class="add-btn" onclick="toggleAdd(event,this)"><i
                                    class="bi bi-plus-lg"></i></button>
                        </div>
                    </div>
                </div>

                <div class="menu-card" data-cat="main">
                    <div class="img-wrap">
                        <img src="https://images.unsplash.com/photo-1512058564366-18510be2db19?w=500&q=75"
                            alt="Lamb Chops" />
                    </div>
                    <div class="card-body-inner">
                        <p class="item-name">Lamb Chops</p>
                        <p class="item-desc">Rosemary &amp; mint sauce</p>
                        <div class="rating-chip"><i class="bi bi-star-fill"></i> 4.6 (95)</div>
                        <div class="price-row">
                            <p class="price mb-0"><span>Rs </span>3,200</p>
                            <button class="add-btn" onclick="toggleAdd(event,this)"><i
                                    class="bi bi-plus-lg"></i></button>
                        </div>
                    </div>
                </div>

                <div class="menu-card" data-cat="beverage">
                    <div class="img-wrap">
                        <img src="https://images.unsplash.com/photo-1544145945-f90425340c7e?w=500&q=75"
                            alt="Fresh Lemonade" />
                        <span class="veg-dot"></span>
                    </div>
                    <div class="card-body-inner">
                        <p class="item-name">Fresh Lemonade</p>
                        <p class="item-desc">Mint &amp; ginger twist</p>
                        <div class="rating-chip"><i class="bi bi-star-fill"></i> 4.8 (312)</div>
                        <div class="price-row">
                            <p class="price mb-0"><span>Rs </span>480</p>
                            <button class="add-btn" onclick="toggleAdd(event,this)"><i
                                    class="bi bi-plus-lg"></i></button>
                        </div>
                    </div>
                </div>

                <div class="menu-card" data-cat="dessert">
                    <div class="img-wrap">
                        <img src="https://images.unsplash.com/photo-1551024601-bec78aea704b?w=500&q=75"
                            alt="Choco Lava Cake" />
                        <span class="veg-dot"></span>
                    </div>
                    <div class="card-body-inner">
                        <p class="item-name">Choco Lava Cake</p>
                        <p class="item-desc">Warm, served with ice cream</p>
                        <div class="rating-chip"><i class="bi bi-star-fill"></i> 4.9 (445)</div>
                        <div class="price-row">
                            <p class="price mb-0"><span>Rs </span>650</p>
                            <button class="add-btn" onclick="toggleAdd(event,this)"><i
                                    class="bi bi-plus-lg"></i></button>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- MOBILE BOTTOM BAR -->
    <div class="bottom-bar">
        <button class="btn-orders" id="ordersBtn" onclick="openCart()">
            <i class="bi bi-bag-check-fill"></i>
            Your Orders
            <span class="cart-count" id="cartCount">0</span>
        </button>
        <button class="btn-waiter-mob">
            <i class="bi bi-person-raised-hand"></i> Call a Waiter
        </button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let cartTotal = 0;

        function filterCat(el, cat) {
            document.querySelectorAll('.cat-item').forEach(i => i.classList.remove('active'));
            el.classList.add('active');
            applyFilter(cat);
        }

        function filterCatMob(el, cat) {
            document.querySelectorAll('.cat-pill').forEach(p => p.classList.remove('active'));
            el.classList.add('active');
            applyFilter(cat);
        }

        function applyFilter(cat) {
            document.querySelectorAll('.menu-card').forEach(card => {
                const show = cat === 'all' || card.dataset.cat === cat;
                card.style.display = show ? '' : 'none';
                if (show) {
                    card.style.animation = 'none';
                    void card.offsetHeight;
                    card.style.animation = '';
                }
            });
        }

        function toggleAdd(e, btn) {
            e.stopPropagation();
            if (btn.classList.contains('added')) return;
            btn.classList.add('added');
            btn.innerHTML = '<i class="bi bi-check-lg"></i>';
            cartTotal++;
            updateCart();
            setTimeout(() => {
                const stepper = document.createElement('div');
                stepper.className = 'qty-stepper';
                stepper.innerHTML =
                    `<button class="qty-btn" onclick="changeQty(event,this,-1)">−</button><span class="qty-count">1</span><button class="qty-btn" onclick="changeQty(event,this,1)">+</button>`;
                btn.replaceWith(stepper);
            }, 480);
        }

        function changeQty(e, btn, delta) {
            e.stopPropagation();
            const stepper = btn.parentElement;
            const countEl = stepper.querySelector('.qty-count');
            let qty = parseInt(countEl.textContent) + delta;
            if (qty <= 0) {
                const addBtn = document.createElement('button');
                addBtn.className = 'add-btn';
                addBtn.innerHTML = '<i class="bi bi-plus-lg"></i>';
                addBtn.setAttribute('onclick', 'toggleAdd(event,this)');
                stepper.replaceWith(addBtn);
                cartTotal = Math.max(0, cartTotal - 1);
                updateCart();
                return;
            }
            cartTotal += delta;
            countEl.textContent = qty;
            updateCart();
        }

        function updateCart() {
            document.getElementById('cartCount').textContent = cartTotal;
            document.getElementById('navCartCount').textContent = cartTotal;
            [document.getElementById('ordersBtn'), document.getElementById('navCartBtn')].forEach(b => {
                if (!b) return;
                b.style.animation = 'none';
                void b.offsetHeight;
                b.style.animation = 'pulse .3s ease';
            });
        }

        function addHero(card) {
            cartTotal++;
            updateCart();
            const heroBtn = card.querySelector('.hero-cart-btn');
            heroBtn.style.background = '#22a55b';
            heroBtn.innerHTML = '<i class="bi bi-check-lg"></i>';
            setTimeout(() => {
                heroBtn.style.background = '';
                heroBtn.innerHTML = '<i class="bi bi-bag-plus-fill"></i>';
            }, 1500);
        }

        function openCart() {
            alert(`You have ${cartTotal} item(s) in your order.\n\n(Connect to your cart/checkout page here.)`);
        }
    </script>
</body>

</html>
