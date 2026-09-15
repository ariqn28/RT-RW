@extends('layouts.warga_app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#059669', // Emerald 600 senada dengan dashboard

                // --- Konfigurasi agar cantik di HP ---
                width: '300px',         // Lebar popup tetap di HP/Desktop
                padding: '1.25rem',
                buttonsStyling: true,
                customClass: {
                    title: 'text-lg font-bold',
                    confirmButton: 'rounded-full px-8 py-2'
                },
                // Efek muncul halus
                showClass: {
                    popup: 'swal2-show'
                }
            });
        });
    </script>
@endif

<div class="pb-10">

    {{-- HEADER BERWARNA --}}
    <header class="relative overflow-hidden rounded-b-[2rem] bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-800 px-5 pt-6 pb-20 text-white">
        <div aria-hidden="true" class="pointer-events-none absolute -top-24 -right-20 h-64 w-64 rounded-full bg-white/10 blur-2xl"></div>
        <div aria-hidden="true" class="pointer-events-none absolute -bottom-28 -left-16 h-56 w-56 rounded-full bg-emerald-400/20 blur-2xl"></div>

        <div class="relative flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <span class="flex h-10 w-10 items-center justify-center rounded-2xl border border-white/20 bg-white/15">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </span>
                <span class="text-sm font-bold tracking-wide">RTRW Terpadu</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" aria-label="Keluar" class="flex h-10 w-10 items-center justify-center rounded-2xl border border-white/20 bg-white/15 text-white transition-colors hover:bg-white/25 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/70">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M16.5 3.75a1.5 1.5 0 0 1 1.5 1.5v13.5a1.5 1.5 0 0 1-1.5 1.5h-6a1.5 1.5 0 0 1-1.5-1.5V15a.75.75 0 0 0-1.5 0v3.75a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V5.25a3 3 0 0 0-3-3h-6a3 3 0 0 0-3 3V9A.75.75 0 1 0 9 9V5.25a1.5 1.5 0 0 1 1.5-1.5h6Zm-5.03 4.72a.75.75 0 0 0 0 1.06l1.72 1.72H2.25a.75.75 0 0 0 0 1.5h10.94l-1.72 1.72a.75.75 0 1 0 1.06 1.06l3-3a.75.75 0 0 0 0-1.06l-3-3a.75.75 0 0 0-1.06 0Z"></path>
                    </svg>
                </button>
            </form>
        </div>

        <div class="relative mt-7">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-emerald-200">{{ now()->format('d M Y') }}</p>
                <h1 class="mt-2 text-2xl font-bold leading-snug">Halo, {{ \Illuminate\Support\Str::before(auth()->user()->name, ' ') }}!</h1>
                <p class="mt-1.5 text-sm text-emerald-100/90">Selamat datang di layanan pengajuan surat, iuran, dan informasi warga.</p>
            </div>
        </div>
    </header>

    {{-- KONTEN --}}
    <div class="-mt-14 space-y-5 px-5">

        @php
            $statuses = [
                'TUNGGU' => ['color' => 'amber', 'icon' => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z'],
                'SETUJU' => ['color' => 'emerald', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                'TOLAK'  => ['color' => 'red', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ];
        @endphp

        {{-- STATISTIK PENGAJUAN --}}
        <section class="fade-up">
            <div class="grid grid-cols-3 gap-3">
                @foreach($statuses as $label => $data)
                    <a href="{{ route('riwayat') }}" aria-label="Lihat riwayat pengajuan {{ \Illuminate\Support\Str::lower($label) }}"
                       class="group flex flex-col items-center gap-1.5 rounded-3xl border border-gray-100 bg-white p-4 text-center shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-md active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500/70 focus-visible:ring-offset-2">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-{{ $data['color'] }}-50 text-{{ $data['color'] }}-600 transition-transform duration-200 group-hover:scale-105">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $data['icon'] }}" />
                            </svg>
                        </span>
                        <span class="min-w-0">
                            <span id="count-{{ $label }}" class="block text-2xl font-extrabold leading-none text-gray-900 transition-colors group-hover:text-{{ $data['color'] }}-600">{{ $counts[$label] ?? 0 }}</span>
                            <span class="mt-1 block text-[9px] font-bold uppercase tracking-wider text-{{ $data['color'] }}-600">{{ $label }}</span>
                        </span>
                    </a>
                @endforeach
            </div>
        </section>

        {{-- KOLOM KIRI --}}
        <div class="space-y-5">

            {{-- KARTU PROFIL --}}
            <a href="{{ route('profile.edit') }}" class="fade-up group flex items-center justify-between gap-4 rounded-3xl border border-gray-100 bg-white p-5 shadow-sm transition-all duration-200 hover:border-emerald-200 hover:shadow-md active:scale-[0.99] focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500/70 focus-visible:ring-offset-2" style="animation-delay:60ms">
                <div class="flex min-w-0 items-center gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 text-xl font-extrabold text-white shadow-md shadow-emerald-600/20">{{ substr(auth()->user()->name, 0, 1) }}</div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <h2 class="truncate text-base font-bold text-gray-900">{{ auth()->user()->name }}</h2>
                            <span class="shrink-0 rounded-full bg-emerald-50 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-emerald-700">{{ auth()->user()->role }}</span>
                        </div>
                        @if(auth()->user()->alamat)
                            <p class="mt-0.5 truncate text-xs text-gray-500">{{ auth()->user()->alamat }}</p>
                        @endif
                        @if(auth()->user()->nik)
                            <p class="mt-0.5 text-xs text-gray-400">NIK ••••{{ substr(auth()->user()->nik, -4) }}</p>
                        @endif
                    </div>
                </div>
                <span aria-hidden="true" class="shrink-0 text-emerald-600 transition-transform duration-200 group-hover:translate-x-1">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            </a>

            {{-- MENU LAYANAN --}}
            <section class="fade-up" style="animation-delay:120ms">
                <div class="mb-2.5">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Layanan Warga</h3>
                </div>
                @php
                    $layanan = [
                        ['route' => 'ajukan', 'label' => 'Ajukan Surat', 'desc' => 'Buat surat online', 'bg' => 'bg-emerald-100', 'text' => 'text-emerald-600', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                        ['route' => 'riwayat', 'label' => 'Riwayat', 'desc' => 'Pantau pengajuan', 'bg' => 'bg-sky-100', 'text' => 'text-sky-600', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['route' => 'iuran.index', 'label' => 'Iuran Warga', 'desc' => 'Bayar iuran bulanan', 'bg' => 'bg-amber-100', 'text' => 'text-amber-600', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                        ['route' => 'informasi.index', 'label' => 'Informasi', 'desc' => 'Pengumuman pengurus', 'bg' => 'bg-violet-100', 'text' => 'text-violet-600', 'icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z'],
                    ];
                @endphp
                <div class="grid grid-cols-2 gap-3">
                    @foreach($layanan as $item)
                        <a href="{{ route($item['route']) }}" class="group flex h-full flex-col rounded-3xl border border-gray-100 bg-white p-5 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-emerald-200 hover:shadow-lg active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500/70 focus-visible:ring-offset-2">
                            <span class="flex h-11 w-11 items-center justify-center rounded-2xl {{ $item['bg'] }} {{ $item['text'] }} transition-transform duration-200 group-hover:scale-110">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                                </svg>
                            </span>
                            <h4 class="mt-3 text-sm font-bold text-gray-900">{{ $item['label'] }}</h4>
                            <p class="mt-0.5 text-[11px] leading-snug text-gray-500">{{ $item['desc'] }}</p>
                            <span aria-hidden="true" class="mt-auto inline-flex items-center pt-2 text-emerald-600 opacity-0 -translate-x-1.5 transition-all duration-200 group-hover:translate-x-0 group-hover:opacity-100 group-focus-visible:translate-x-0 group-focus-visible:opacity-100">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        </a>
                    @endforeach
                </div>
            </section>
        </div>

        {{-- KOLOM KANAN --}}
        <div class="space-y-5">

            {{-- BANTUAN & AKUN / KONTAK PENGURUS --}}
            <div x-data="{ open: false, chatOpen: false }" class="fade-up space-y-3" style="animation-delay:160ms">
                <div class="mb-[5px]">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Bantuan & Akun</h3>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" @click="open = !open" class="rounded-3xl border border-amber-100 bg-amber-50 p-4 text-left transition-all duration-200 hover:-translate-y-0.5 hover:border-amber-200 hover:shadow-md active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-500/60">
                        <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-100 text-amber-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                            </svg>
                        </span>
                        <h4 class="mt-3 text-sm font-bold text-amber-900">Kontak Pengurus</h4>
                        <p class="mt-0.5 text-[11px] leading-snug text-amber-700">WhatsApp & live chat</p>
                    </button>
                    <a href="{{ route('profile.edit') }}" class="group rounded-3xl border border-gray-100 bg-white p-4 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-md active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500/60">
                        <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-100 text-slate-600 transition-transform duration-200 group-hover:scale-110">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </span>
                        <h4 class="mt-3 text-sm font-bold text-gray-900">Profil</h4>
                        <p class="mt-0.5 text-[11px] leading-snug text-gray-500">Akun & data diri</p>
                    </a>
                </div>

                <div x-show="open" @click.away="open = false" x-cloak class="rounded-3xl border border-amber-200 bg-white p-4 shadow-sm">
                    @if($contact->whatsapp)
                    @php($wa = preg_replace('/[^0-9]/', '', $contact->whatsapp))
                    <div class="grid grid-cols-1 gap-2">
                        <a href="https://wa.me/{{ $wa }}?text={{ urlencode($contact->chat_greeting) }}" target="_blank" rel="noopener" class="rounded-xl border border-amber-200 bg-amber-50 p-3 text-center text-xs font-bold text-amber-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-500/60">
                            WhatsApp
                        </a>
                        <button type="button" @click="chatOpen = true; open = false" class="rounded-xl border border-amber-200 bg-amber-50 p-3 text-center text-xs font-bold text-amber-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-500/60">
                            Live Chat
                        </button>
                    </div>
                    @else
                    <p class="text-xs text-amber-800">Kontak pengurus belum diatur.</p>
                    @endif
                </div>

                <div x-show="chatOpen" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
                    <div @click="chatOpen = false" class="absolute inset-0 bg-gray-900/40"></div>
                    <div class="bg-white w-full max-w-sm rounded-3xl shadow-2xl relative h-[400px] flex flex-col">
                        <div class="p-4 bg-emerald-600 text-white rounded-t-3xl flex justify-between">
                            <span>Chat Admin</span>
                            <button @click="chatOpen = false">X</button>
                        </div>
                        <div class="flex-1 p-4 bg-gray-50 overflow-y-auto">
                            {{ $contact->chat_greeting }}
                        </div>
                        @if($contact->whatsapp)
                        <a href="https://wa.me/{{ $wa }}?text={{ urlencode($contact->chat_greeting) }}" target="_blank" rel="noopener" class="m-4 mt-0 bg-emerald-600 text-white text-center rounded-xl py-3 font-bold">Lanjutkan ke WhatsApp</a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- BERITA TERBARU --}}
            <section class="fade-up" style="animation-delay:200ms">
                <div class="mb-2.5 flex items-center justify-between">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Berita Terbaru</h3>
                    <a href="{{ route('informasi.index') }}" class="text-[11px] font-bold text-emerald-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 rounded">Lihat semua</a>
                </div>
                @forelse($latestAnnouncements as $announcement)
                    <a href="{{ route('informasi.index') }}" class="group flex items-start gap-3 rounded-3xl border border-gray-100 bg-white p-4 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-sky-200 hover:shadow-md active:scale-[0.99] focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500/60 @if(!$loop->last) mb-2 @endif">
                        <span class="flex h-12 w-12 shrink-0 flex-col items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                            <span class="text-sm font-extrabold leading-none">{{ $announcement->created_at->format('d') }}</span>
                            <span class="mt-0.5 text-[9px] font-bold uppercase leading-none">{{ $announcement->created_at->format('M') }}</span>
                        </span>
                        <span class="min-w-0">
                            <h4 class="line-clamp-2 text-sm font-bold text-gray-800">{{ $announcement->title }}</h4>
                            <p class="mt-0.5 line-clamp-2 text-xs text-gray-500">{{ $announcement->body }}</p>
                        </span>
                    </a>
                @empty
                    <p class="rounded-3xl bg-blue-50 p-4 text-sm text-blue-800">Belum ada berita terbaru.</p>
                @endforelse
            </section>
        </div>

        {{-- IURAN AKTIF --}}
        <section class="fade-up" style="animation-delay:240ms">
            <div class="mb-2.5 flex items-center justify-between">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Iuran Aktif</h3>
                <a href="{{ route('iuran.index') }}" class="text-[11px] font-bold text-emerald-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 rounded">Lihat semua</a>
            </div>
            <div class="grid items-stretch gap-4">
                @forelse($activeDues as $due)
                    <a href="{{ route('iuran.index') }}#iuran-{{ $due->id }}" class="group flex flex-col gap-2.5 rounded-3xl border border-gray-100 bg-white p-5 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-emerald-200 hover:shadow-lg active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500/70 focus-visible:ring-offset-2">
                        <div class="flex items-start justify-between gap-3">
                            <h4 class="min-w-0 font-bold text-gray-800 text-sm">{{ $due->title }}</h4>
                            <span class="shrink-0 rounded-xl bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700 whitespace-nowrap">Rp {{ number_format($due->amountFor(auth()->id()), 0, ',', '.') }}</span>
                        </div>
                        <p class="text-xs text-gray-500">{{ $due->due_date ? 'Batas ' . $due->due_date->format('d M Y') : 'Informasi pembayaran tersedia' }}</p>
                        @if($due->payment_methods)
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($due->payment_methods as $method)
                                    <span class="rounded-lg bg-gray-100 px-2 py-0.5 text-[10px] font-bold text-gray-600">{{ ['qris' => 'QRIS', 'cash' => 'Cash', 'transfer' => 'Transfer'][$method] ?? $method }}</span>
                                @endforeach
                            </div>
                        @endif
                        <span class="mt-auto inline-block rounded-xl bg-emerald-600 py-2.5 text-center text-xs font-bold text-white transition-colors duration-200 group-hover:bg-emerald-700">Bayar Sekarang</span>
                    </a>
                @empty
                    <p class="rounded-3xl bg-emerald-50 p-4 text-sm text-emerald-800">Belum ada iuran aktif.</p>
                @endforelse
            </div>
        </section>
    </div>
</div>

<script>
    setInterval(function() {
        fetch("{{ route('warga.stats') }}")
            .then(response => response.json())
            .then(data => {
                // Update hanya jika angkanya berubah
                if(document.getElementById('count-TUNGGU')) document.getElementById('count-TUNGGU').innerText = data.TUNGGU;
                if(document.getElementById('count-SETUJU')) document.getElementById('count-SETUJU').innerText = data.SETUJU;
                if(document.getElementById('count-TOLAK')) document.getElementById('count-TOLAK').innerText = data.TOLAK;
            })
            .catch(err => console.error('Error:', err));
    }, 3000); // Cek data baru setiap 3 detik secara otomatis
</script>
@endsection