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
        body { font-family: 'Inter', sans-serif !important; background-color: #f3f4f6; }
        /* Memaksa elemen agar mengikuti desain */
        * { box-sizing: border-box; }
       [x-cloak] { display: none !important; }  
    </style>
</head>
<body class="flex justify-center min-h-screen">

    <div class="w-full max-w-[448px] bg-white min-h-screen shadow-2xl relative overflow-hidden">

        <main class="pb-24">
            @yield('content')
        </main>

        <nav class="fixed bottom-0 w-full max-w-[448px] bg-white border-t flex justify-around p-4 shadow-2xl z-50">
            @php
                $navs = [
                    ['route' => 'dashboard', 'label' => 'Beranda', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['route' => 'ajukan', 'label' => 'Ajukan', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                    ['route' => 'riwayat', 'label' => 'Riwayat', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['route' => 'profile.edit', 'label' => 'Profil', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z']
                ];
            @endphp
            @foreach($navs as $nav)
                <a href="{{ route($nav['route']) }}" class="flex flex-col items-center {{ request()->routeIs($nav['route']) ? 'text-[#0e8a5b]' : 'text-gray-400' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $nav['icon'] }}"></path></svg>
                    <span class="text-[10px] font-bold mt-1">{{ $nav['label'] }}</span>
                </a>
            @endforeach
        </nav>
    </div>
</body>
</html>
