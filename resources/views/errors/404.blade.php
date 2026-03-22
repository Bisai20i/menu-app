<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Page Not Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#C62828',
                        'primary-light': '#FFEBEE',
                        'primary-dark': '#B71C1C',
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'DM Sans', sans-serif;
        }

        .bg-grid {
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(198, 40, 40, 0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(198, 40, 40, 0.04) 1px, transparent 1px);
            background-size: 60px 60px;
            animation: gridDrift 20s linear infinite;
            pointer-events: none;
        }

        @keyframes gridDrift {
            0% {
                background-position: 0 0;
            }

            100% {
                background-position: 60px 60px;
            }
        }

        .ghost-number {
            font-family: 'Playfair Display', serif;
            font-size: clamp(180px, 32vw, 400px);
            font-weight: 900;
            color: transparent;
            -webkit-text-stroke: 2px rgba(198, 40, 40, 0.09);
            line-height: 0.85;
            user-select: none;
            letter-spacing: -0.05em;
            animation: ghostPulse 4s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes ghostPulse {

            0%,
            100% {
                -webkit-text-stroke-color: rgba(198, 40, 40, 0.09);
            }

            50% {
                -webkit-text-stroke-color: rgba(198, 40, 40, 0.17);
            }
        }

        .plate-wrap {
            animation: plateFloat 3s ease-in-out infinite;
        }

        @keyframes plateFloat {

            0%,
            100% {
                transform: translateY(0px) rotate(-1deg);
            }

            50% {
                transform: translateY(-12px) rotate(1deg);
            }
        }

        .steam-line {
            display: block;
            width: 3px;
            border-radius: 2px;
            background: linear-gradient(to top, rgba(198, 40, 40, 0.5), transparent);
            animation: steamRise 2s ease-in-out infinite;
        }

        .steam-line:nth-child(1) {
            height: 24px;
            animation-delay: 0s;
        }

        .steam-line:nth-child(2) {
            height: 36px;
            animation-delay: 0.3s;
        }

        .steam-line:nth-child(3) {
            height: 20px;
            animation-delay: 0.6s;
        }

        @keyframes steamRise {
            0% {
                transform: translateY(0) scaleX(1);
                opacity: 0.7;
            }

            50% {
                transform: translateY(-8px) scaleX(1.3);
                opacity: 0.4;
            }

            100% {
                transform: translateY(-16px) scaleX(0.8);
                opacity: 0;
            }
        }

        .error-card {
            animation: cardIn 0.8s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        @keyframes cardIn {
            from {
                opacity: 0;
                transform: translateY(36px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background-color: #C62828;
            color: white;
            padding: 10px 22px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #B71C1C;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(198, 40, 40, 0.3);
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: transparent;
            color: #C62828;
            padding: 10px 22px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            border: 1.5px solid #C62828;
            transition: background-color 0.2s ease;
        }

        .btn-secondary:hover {
            background-color: #FFEBEE;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 12px;
            color: rgba(198, 40, 40, 0.35);
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(198, 40, 40, 0.12);
        }

        .quick-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            padding: 12px 8px;
            border-radius: 12px;
            text-decoration: none;
            transition: background 0.2s ease;
        }

        .quick-link:hover {
            background: #FFEBEE;
        }

        .quick-link-label {
            font-size: 12px;
            color: #6b7280;
            transition: color 0.2s ease;
        }

        .quick-link:hover .quick-link-label {
            color: #C62828;
        }

        .corner {
            position: fixed;
            width: 28px;
            height: 28px;
        }

        .corner-tl {
            top: 24px;
            left: 24px;
            border-top: 2px solid rgba(198, 40, 40, 0.2);
            border-left: 2px solid rgba(198, 40, 40, 0.2);
        }

        .corner-tr {
            top: 24px;
            right: 24px;
            border-top: 2px solid rgba(198, 40, 40, 0.2);
            border-right: 2px solid rgba(198, 40, 40, 0.2);
        }

        .corner-bl {
            bottom: 24px;
            left: 24px;
            border-bottom: 2px solid rgba(198, 40, 40, 0.2);
            border-left: 2px solid rgba(198, 40, 40, 0.2);
        }

        .corner-br {
            bottom: 24px;
            right: 24px;
            border-bottom: 2px solid rgba(198, 40, 40, 0.2);
            border-right: 2px solid rgba(198, 40, 40, 0.2);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-6" style="background-color:#faf8f5;">

    <div class="bg-grid"></div>
    <div class="corner corner-tl"></div>
    <div class="corner corner-tr"></div>
    <div class="corner corner-bl"></div>
    <div class="corner corner-br"></div>

    <div class="relative z-10 text-center w-full" style="max-width:480px;">

        <!-- Ghost 404 -->
        <div class="absolute inset-0 flex items-center justify-center select-none" style="top:-60px;"
            aria-hidden="true">
            <span class="ghost-number">404</span>
        </div>

        <!-- Card -->
        <div class="error-card relative rounded-2xl px-8 pt-10 pb-8"
            style="margin-top:60px; background:rgba(255,255,255,0.88); backdrop-filter:blur(8px); border:1px solid rgba(198,40,40,0.1); box-shadow:0 20px 60px rgba(198,40,40,0.07);">

            <!-- Plate -->
            <div class="plate-wrap flex flex-col items-center mb-6">
                <div class="flex gap-2 justify-center mb-2">
                    <span class="steam-line"></span>
                    <span class="steam-line"></span>
                    <span class="steam-line"></span>
                </div>
                <svg width="88" height="88" viewBox="0 0 90 90" fill="none">
                    <ellipse cx="45" cy="82" rx="30" ry="5" fill="rgba(198,40,40,0.07)" />
                    <circle cx="45" cy="48" r="32" fill="white" stroke="#e5e7eb" stroke-width="2" />
                    <circle cx="45" cy="48" r="26" fill="#faf8f5" stroke="#f3f4f6" stroke-width="1" />
                    <text x="45" y="57" text-anchor="middle" font-family="Playfair Display, serif" font-size="28"
                        font-weight="700" fill="#C62828">?</text>
                    <path d="M13 34 Q45 2 77 34" stroke="#d1d5db" stroke-width="2" fill="none"
                        stroke-linecap="round" />
                    <line x1="13" y1="34" x2="77" y2="34" stroke="#d1d5db"
                        stroke-width="2" />
                    <circle cx="45" cy="8" r="4" fill="#C62828" />
                </svg>
            </div>

            <!-- Error badge -->
            <div class="inline-flex items-center gap-2 mb-4 px-3 py-1.5 rounded-full text-xs font-medium"
                style="background:#FFEBEE; color:#C62828; letter-spacing:0.08em; text-transform:uppercase;">
                <svg width="8" height="8" viewBox="0 0 8 8">
                    <circle cx="4" cy="4" r="4" fill="#C62828" />
                </svg>
                Error 404
            </div>

            <!-- Headline -->
            <h1 class="mb-3"
                style="font-family:'Playfair Display',serif; font-size:1.9rem; font-weight:700; color:#111827; line-height:1.2;">
                This dish isn't<br>
                <em style="color:#C62828; font-style:italic;">on the menu</em>
            </h1>

            <p class="mb-8" style="color:#6b7280; font-size:14px; line-height:1.75;">
                The page you're looking for seems to have walked out of the kitchen. It may have been moved, renamed, or
                perhaps it was a daily special that's no longer available.
            </p>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center mb-6">
                <a href="{{ url('/') }}" class="btn-primary">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M3 12l9-9 9 9" />
                        <path d="M9 21V9h6v12" />
                    </svg>
                    Back to Home
                </a>
                <button onclick="history.back()" class="btn-secondary">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M19 12H5" />
                        <path d="M12 19l-7-7 7-7" />
                    </svg>
                    Go Back
                </button>
            </div>

            {{-- <div class="divider mb-5">or try one of these</div>

            <!-- Quick links -->
            <div class="grid grid-cols-3 gap-1">
                <a href="{{ url('/menu') }}" class="quick-link">
                    <svg width="20" height="20" fill="none" stroke="#C62828" stroke-width="1.5"
                        viewBox="0 0 24 24">
                        <rect x="3" y="3" width="18" height="18" rx="2" />
                        <path d="M3 9h18M9 21V9" />
                    </svg>
                    <span class="quick-link-label">Menu</span>
                </a>
                <a href="{{ url('/dashboard') }}" class="quick-link">
                    <svg width="20" height="20" fill="none" stroke="#C62828" stroke-width="1.5"
                        viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7" rx="1" />
                        <rect x="14" y="3" width="7" height="7" rx="1" />
                        <rect x="3" y="14" width="7" height="7" rx="1" />
                        <rect x="14" y="14" width="7" height="7" rx="1" />
                    </svg>
                    <span class="quick-link-label">Dashboard</span>
                </a>
                <a href="{{ url('/support') }}" class="quick-link">
                    <svg width="20" height="20" fill="none" stroke="#C62828" stroke-width="1.5"
                        viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9" />
                        <path d="M12 8v4" />
                        <circle cx="12" cy="16" r="0.5" fill="#C62828" />
                    </svg>
                    <span class="quick-link-label">Support</span>
                </a>
            </div> --}}
        </div>

        <p class="mt-5" style="font-size:12px; color:#9ca3af;">
            © {{ date('Y') }} · MenuApp &nbsp;·&nbsp;
            <a href="{{ url('/') }}" style="color:#9ca3af; text-decoration:none; transition:color 0.2s;"
                onmouseover="this.style.color='#C62828'" onmouseout="this.style.color='#9ca3af'">Visit Homepage</a>
        </p>
    </div>
</body>

</html>