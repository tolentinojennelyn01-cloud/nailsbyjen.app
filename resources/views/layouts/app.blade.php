<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Nails by Jen')</title>

    {{-- Google Fonts: Playfair Display for glam serif headings, Jost for clean body text, Alex Brush for the signature script mark --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,600&family=Jost:wght@300;400;500;600&family=Alex+Brush&display=swap" rel="stylesheet">

    {{-- Tailwind via CDN keeps this simple — swap for your own Vite build if you already have one --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        rose: {
                            50: '#fdf2f4', 100: '#fce7ea', 200: '#f9c9d1',
                            300: '#f4a3b1', 400: '#ec7690', 500: '#df4d6e',
                            600: '#c22e51', 700: '#a12142', 800: '#861d3a',
                        },
                        gold: {
                            50: '#fbf7ec', 100: '#f5ebce', 200: '#ecd8a2',
                            300: '#e0bf70', 400: '#d4ab4d', 500: '#c2953a',
                            600: '#a3792d', 700: '#835f26', 800: '#5c4119',
                        },
                        plum: {
                            50: '#f7f0f3', 400: '#8a4d68', 600: '#5c2740', 700: '#481e32', 800: '#391826',
                        },
                    },
                    fontFamily: {
                        serif: ['"Playfair Display"', 'Georgia', 'serif'],
                        sans: ['Jost', 'system-ui', 'sans-serif'],
                        script: ['"Alex Brush"', 'cursive'],
                    },
                    boxShadow: {
                        glam: '0 10px 30px -10px rgba(194, 46, 81, 0.25)',
                    },
                },
            },
        };
    </script>

    {{-- Alpine.js for the reactive price calculator, no build step needed --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Jost', system-ui, sans-serif;
            background-color: #fdf2f4;
            background-image:
                radial-gradient(ellipse 700px 420px at 8% -8%, rgba(212, 171, 77, 0.16), transparent 60%),
                radial-gradient(ellipse 700px 420px at 100% 0%, rgba(223, 77, 110, 0.14), transparent 60%);
            background-attachment: fixed;
        }
        .glam-divider {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }
        .glam-divider::before,
        .glam-divider::after {
            content: '';
            height: 1px;
            flex: 1;
            background: linear-gradient(90deg, transparent, #d4ab4d, transparent);
        }
    </style>
</head>
<body class="min-h-screen text-plum-800">

    <header class="bg-white/70 backdrop-blur border-b border-gold-200/60">
        <div class="max-w-4xl mx-auto px-4 py-5 flex items-center justify-between">
            <a href="{{ route('booking.create') }}" class="flex items-baseline gap-2">
                <span class="font-script text-4xl leading-none bg-gradient-to-r from-rose-600 via-rose-500 to-gold-500 bg-clip-text text-transparent">Jen Nail Artistry</span>
                <span class="text-[11px] uppercase tracking-[0.2em] text-gold-700/80 font-medium">Nailtech Beginner</span>
            </a>
            <a href="https://www.instagram.com/nailsbyjen.ph" target="_blank" class="text-sm text-rose-500 hover:text-rose-600 transition-colors">@nailsbyjen.ph</a>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-10">
        @if (session('success'))
            <div class="mb-6 rounded-xl bg-gradient-to-r from-emerald-50 to-white border border-emerald-100 text-emerald-700 px-4 py-3 text-sm shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="text-center py-10">
        <div class="max-w-xs mx-auto glam-divider text-gold-500 text-xs mb-3">✦</div>
        <p class="text-xs tracking-wide text-plum-400">
            Paliparan 2, Dasmariñas, Cavite &middot; nailsbyjen.ph
        </p>
    </footer>
</body>
</html>
