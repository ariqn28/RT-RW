<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RTRW Terpadu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif !important;
            background-color: #f3f4f6;
            background-image: linear-gradient(180deg, #e9f5ee 0%, #f3f4f6 420px);
        }
        /* Memaksa elemen agar mengikuti desain */
        * { box-sizing: border-box; }
        [x-cloak] { display: none !important; }
        a, button, [role="button"] { -webkit-tap-highlight-color: transparent; }

        /* Animasi masuk ringan untuk konten dashboard */
        .fade-up {
            opacity: 0;
            transform: translateY(10px);
            animation: fade-up .5s cubic-bezier(.22, .61, .36, 1) forwards;
        }
        @keyframes fade-up { to { opacity: 1; transform: translateY(0); } }

        /* --- Latar interaktif: blob kabur yang hidup + glow mengikuti kursor --- */
        .app-bg { position: fixed; inset: 0; z-index: -1; overflow: hidden; pointer-events: none; }
        .app-bg-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: .55;
            will-change: transform, opacity;
        }
        .app-bg-blob--1 {
            width: 560px; height: 560px; top: -160px; right: -80px;
            background: radial-gradient(circle at 35% 35%, rgba(16,185,129,.35), rgba(5,150,105,.12) 60%, transparent 75%);
            animation: bg-drift-1 22s ease-in-out infinite alternate;
        }
        .app-bg-blob--2 {
            width: 480px; height: 480px; top: 60px; left: -160px;
            background: radial-gradient(circle at 50% 40%, rgba(14,165,233,.28), rgba(56,189,248,.10) 60%, transparent 75%);
            animation: bg-drift-2 26s ease-in-out infinite alternate;
        }
        .app-bg-blob--3 {
            width: 440px; height: 440px; bottom: -140px; left: 30%;
            background: radial-gradient(circle at 50% 50%, rgba(139,92,246,.16), rgba(167,139,250,.06) 60%, transparent 75%);
            animation: bg-drift-3 32s ease-in-out infinite alternate;
        }
        .app-bg-glow {
            position: absolute; inset: 0;
            background: radial-gradient(640px 440px at var(--gx, 70%) var(--gy, 12%), rgba(16,185,129,.10), transparent 70%);
            mix-blend-mode: screen;
        }
        @keyframes bg-drift-1 { from { transform: translate3d(0,0,0) scale(1);   } to { transform: translate3d(-70px,50px,0) scale(1.10); } }
        @keyframes bg-drift-2 { from { transform: translate3d(0,0,0) scale(1);   } to { transform: translate3d(60px,-40px,0) scale(1.14); } }
        @keyframes bg-drift-3 { from { transform: translate3d(0,0,0) scale(1);   } to { transform: translate3d(-50px,-60px,0) scale(1.06); } }
        @media (prefers-reduced-motion: reduce) {
            .app-bg-blob { animation: none; opacity: .35; }
            .fade-up { animation: none; opacity: 1; transform: none; }
        }
    </style>
</head>
<body class="min-h-screen">

    <div class="app-bg" aria-hidden="true">
        <div class="app-bg-blob app-bg-blob--1"></div>
        <div class="app-bg-blob app-bg-blob--2"></div>
        <div class="app-bg-blob app-bg-blob--3"></div>
        <div class="app-bg-glow" id="appBgGlow"></div>
    </div>

    @php
        $navs = [
            ['route' => 'dashboard', 'label' => 'Beranda', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            ['route' => 'ajukan', 'label' => 'Ajukan', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
            ['route' => 'riwayat', 'label' => 'Riwayat', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['route' => 'profile.edit', 'label' => 'Profil', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z']
        ];
    @endphp

    <div class="mx-auto w-full max-w-[448px] min-h-screen bg-white shadow-2xl relative overflow-hidden md:my-8 md:min-h-0 md:rounded-[2.5rem] md:ring-1 md:ring-black/5">

        <main class="pb-24">
            @yield('content')
        </main>

        <nav class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-[448px] bg-white border-t flex justify-around p-4 shadow-2xl z-50 md:bottom-8 md:rounded-t-3xl">
            @foreach($navs as $nav)
                <a href="{{ route($nav['route']) }}" class="flex flex-col items-center {{ request()->routeIs($nav['route']) || ($nav['route'] === 'dashboard' && request()->routeIs('warga.dashboard')) ? 'text-emerald-600' : 'text-gray-400' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $nav['icon'] }}"></path></svg>
                    <span class="text-[10px] font-bold mt-1">{{ $nav['label'] }}</span>
                </a>
            @endforeach
        </nav>
    </div>

    <script>
        // Glow lembut mengikuti kursor (hanya perangkat dengan pointer halus)
        (function () {
            var glow = document.getElementById('appBgGlow');
            if (!glow || !window.matchMedia || !window.matchMedia('(pointer: fine)').matches) return;
            var cx = 0.7, cy = 0.12, tx = cx, ty = cy, running = false;
            function tick() {
                cx += (tx - cx) * 0.06;
                cy += (ty - cy) * 0.06;
                glow.style.setProperty('--gx', (cx * 100).toFixed(2) + '%');
                glow.style.setProperty('--gy', (cy * 100).toFixed(2) + '%');
                if (Math.abs(tx - cx) > 0.001 || Math.abs(ty - cy) > 0.001) {
                    running = requestAnimationFrame(tick);
                } else {
                    running = null;
                }
            }
            window.addEventListener('pointermove', function (e) {
                tx = e.clientX / window.innerWidth;
                ty = e.clientY / window.innerHeight;
                if (!running) running = requestAnimationFrame(tick);
            }, { passive: true });
        })();
    </script>
</body>
</html>