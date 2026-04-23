<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Access Forbidden</title>
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
            background-color: #1a0a0a;
        }

        .bg-glow {
            position: fixed;
            inset: 0;
            pointer-events: none;
            background:
                radial-gradient(ellipse 65% 55% at 50% 60%, rgba(198, 40, 40, 0.13) 0%, transparent 70%),
                radial-gradient(ellipse 40% 30% at 50% 40%, rgba(183, 28, 28, 0.08) 0%, transparent 65%);
        }

        .ghost-number {
            font-family: 'Playfair Display', serif;
            font-size: clamp(180px, 32vw, 400px);
            font-weight: 900;
            color: transparent;
            -webkit-text-stroke: 2px rgba(198, 40, 40, 0.11);
            line-height: 0.85;
            user-select: none;
            letter-spacing: -0.05em;
            animation: ghostGlow 4s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes ghostGlow {

            0%,
            100% {
                -webkit-text-stroke-color: rgba(198, 40, 40, 0.11);
            }

            50% {
                -webkit-text-stroke-color: rgba(198, 40, 40, 0.22);
            }
        }

        .lock-wrap {
            animation: lockShake 6s ease-in-out infinite;
        }

        @keyframes lockShake {

            0%,
            82%,
            100% {
                transform: rotate(0deg);
            }

            85% {
                transform: rotate(-4deg) translateY(-3px);
            }

            88% {
                transform: rotate(4deg) translateY(-3px);
            }

            91% {
                transform: rotate(-3deg) translateY(-2px);
            }

            94% {
                transform: rotate(2deg) translateY(-1px);
            }

            97% {
                transform: rotate(0deg);
            }
        }

        .pulse-ring {
            position: absolute;
            inset: -10px;
            border-radius: 50%;
            border: 2px solid rgba(198, 40, 40, 0.4);
            animation: ringPulse 2.5s ease-out infinite;
        }

        @keyframes ringPulse {
            0% {
                transform: scale(0.88);
                opacity: 0.7;
            }

            70% {
                transform: scale(1.35);
                opacity: 0;
            }

            100% {
                transform: scale(1.35);
                opacity: 0;
            }
        }

        .error-card {
            animation: cardIn 0.9s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        @keyframes cardIn {
            from {
                opacity: 0;
                transform: translateY(44px) scale(0.97);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Scan line */
        .scan-line {
            position: absolute;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(198, 40, 40, 0.35), transparent);
            animation: scan 5s linear infinite;
            pointer-events: none;
        }

        @keyframes scan {
            from {
                top: 0%;
                opacity: 0.8;
            }

            to {
                top: 100%;
                opacity: 0.1;
            }
        }

        .warning-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #C62828;
            animation: dotBlink 1.5s ease-in-out infinite;
            flex-shrink: 0;
        }

        @keyframes dotBlink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.25;
            }
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: linear-gradient(135deg, #C62828, #B71C1C);
            color: white;
            padding: 10px 22px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 4px 20px rgba(198, 40, 40, 0.25);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(198, 40, 40, 0.4);
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: rgba(198, 40, 40, 0.08);
            color: rgba(239, 154, 154, 0.9);
            padding: 10px 22px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid rgba(198, 40, 40, 0.25);
            transition: background 0.2s ease, border-color 0.2s ease;
        }

        .btn-secondary:hover {
            background: rgba(198, 40, 40, 0.14);
            border-color: rgba(198, 40, 40, 0.5);
        }

        .upgrade-btn-primary {
            flex: 1;
            text-align: center;
            background: #C62828;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
            transition: background 0.2s ease;
        }

        .upgrade-btn-primary:hover {
            background: #B71C1C;
        }

        .upgrade-btn-secondary {
            flex: 1;
            text-align: center;
            background: rgba(198, 40, 40, 0.1);
            color: rgba(239, 154, 154, 0.85);
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
            border: 1px solid rgba(198, 40, 40, 0.2);
            transition: background 0.2s ease;
        }

        .upgrade-btn-secondary:hover {
            background: rgba(198, 40, 40, 0.18);
        }

        .hr-deco {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(198, 40, 40, 0.18), transparent);
        }

        .corner {
            position: fixed;
            width: 28px;
            height: 28px;
        }

        .corner-tl {
            top: 24px;
            left: 24px;
            border-top: 1px solid rgba(198, 40, 40, 0.3);
            border-left: 1px solid rgba(198, 40, 40, 0.3);
        }

        .corner-tr {
            top: 24px;
            right: 24px;
            border-top: 1px solid rgba(198, 40, 40, 0.3);
            border-right: 1px solid rgba(198, 40, 40, 0.3);
        }

        .corner-bl {
            bottom: 24px;
            left: 24px;
            border-bottom: 1px solid rgba(198, 40, 40, 0.3);
            border-left: 1px solid rgba(198, 40, 40, 0.3);
        }

        .corner-br {
            bottom: 24px;
            right: 24px;
            border-bottom: 1px solid rgba(198, 40, 40, 0.3);
            border-right: 1px solid rgba(198, 40, 40, 0.3);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-6">

    <div class="bg-glow"></div>
    <div class="corner corner-tl"></div>
    <div class="corner corner-tr"></div>
    <div class="corner corner-bl"></div>
    <div class="corner corner-br"></div>

    <div class="relative z-10 text-center w-full" style="max-width:480px;">

        <!-- Ghost 403 -->
        <div class="absolute inset-0 flex items-center justify-center select-none" style="top:-60px;"
            aria-hidden="true">
            <span class="ghost-number">403</span>
        </div>

        <!-- Card -->
        <div class="error-card relative rounded-2xl px-8 pt-10 pb-8 overflow-hidden"
            style="margin-top:60px; background:rgba(28,10,10,0.92); backdrop-filter:blur(12px); border:1px solid rgba(198,40,40,0.15); box-shadow:0 24px 80px rgba(0,0,0,0.5);">

            <div class="scan-line"></div>

            <!-- Lock icon -->
            <div class="flex justify-center mb-6">
                <div class="relative">
                    <div class="pulse-ring"></div>
                    <div class="lock-wrap relative z-10 flex items-center justify-center rounded-2xl"
                        style="width:64px; height:64px; background:radial-gradient(circle at 40% 35%, rgba(198,40,40,0.22), rgba(183,28,28,0.08)); border:1px solid rgba(198,40,40,0.25);">
                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none">
                            <rect x="4" y="14" width="22" height="14" rx="3" fill="rgba(198,40,40,0.18)"
                                stroke="#C62828" stroke-width="1.5" />
                            <path d="M9 14V10a6 6 0 0 1 12 0v4" stroke="#C62828" stroke-width="1.5"
                                stroke-linecap="round" fill="none" />
                            <circle cx="15" cy="21" r="2.5" fill="#C62828" />
                            <rect x="14" y="21" width="2" height="3.5" rx="1" fill="#C62828" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Warning badge -->
            <div class="inline-flex items-center gap-2 mb-4 px-3 py-1.5 rounded-full"
                style="background:rgba(198,40,40,0.14); border:1px solid rgba(198,40,40,0.25);">
                <span class="warning-dot"></span>
                <span
                    style="color:#ef9a9a; font-size:11px; font-weight:500; letter-spacing:0.12em; text-transform:uppercase;">
                    Access Restricted · Error 403
                </span>
            </div>

            <!-- Headline -->
            <h1 class="mb-3"
                style="font-family:'Playfair Display',serif; font-size:1.9rem; font-weight:700; color:white; line-height:1.2;">
                You are<br>
                <em style="color:#ef9a9a; font-style:italic;">not authorized</em>
            </h1>

            <p class="mb-6" style="color:#6b6060; font-size:14px; line-height:1.75;">
                You don't have permission to access this area. Your current plan may not include this feature, or your
                session may have expired.
            </p>

            <div class="hr-deco mb-6"></div>

            <!-- Upgrade nudge -->
            <div class="mb-6 text-left rounded-xl p-4"
                style="background:rgba(198,40,40,0.07); border:1px solid rgba(198,40,40,0.18);">
                <div class="flex items-start gap-3 mb-3">
                    <div class="flex-shrink-0 flex items-center justify-center rounded-lg"
                        style="width:34px; height:34px; background:rgba(198,40,40,0.18); margin-top:2px;">
                        <svg width="16" height="16" fill="none" stroke="#C62828" stroke-width="1.5"
                            viewBox="0 0 24 24">
                            <path
                                d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                        </svg>
                    </div>
                    <div>
                        <p style="color:white; font-size:14px; font-weight:500; margin-bottom:4px;">Unlock with Pro Plan
                        </p>
                        <p style="color:#6b6060; font-size:12px; line-height:1.6;">
                            Digital menus, table QR codes, and order management are available on the
                            <span style="color:#ef9a9a;">Pro Plan</span>.
                        </p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ url('/#pricing') }}" class="upgrade-btn-primary">View Plans</a>
                    <a href="{{ route('master.login') }}" class="upgrade-btn-secondary">Login</a>
                </div>
            </div>

            <!-- Main actions -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ url('/') }}" class="btn-primary">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7" rx="1" />
                        <rect x="14" y="3" width="7" height="7" rx="1" />
                        <rect x="3" y="14" width="7" height="7" rx="1" />
                        <rect x="14" y="14" width="7" height="7" rx="1" />
                    </svg>
                    Go to Home
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
        </div>

        <p class="mt-5" style="font-size:12px; color:#4a3535;">
            © {{ date('Y') }} · MenuApp &nbsp;·&nbsp;
            <a href="{{ url('/support') }}" style="color:#4a3535; text-decoration:none;"
                onmouseover="this.style.color='#C62828'" onmouseout="this.style.color='#4a3535'">Contact Support</a>
            &nbsp;·&nbsp;
            <a href="{{ url('/login') }}" style="color:#4a3535; text-decoration:none;"
                onmouseover="this.style.color='#C62828'" onmouseout="this.style.color='#4a3535'">Sign In</a>
        </p>
    </div>
</body>

</html>
