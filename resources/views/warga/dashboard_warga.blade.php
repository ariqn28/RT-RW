@extends('layouts.warga_app')

@section('content')
<div class="px-4 py-6 pb-24 space-y-8">

    <div class="flex justify-between items-center">
        <h1 class="text-xl font-bold text-gray-800">Welcome</h1>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-gray-600 hover:text-red-600 transition-colors p-2 bg-gray-100 rounded-full">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M16.5 3.75a1.5 1.5 0 0 1 1.5 1.5v13.5a1.5 1.5 0 0 1-1.5 1.5h-6a1.5 1.5 0 0 1-1.5-1.5V15a.75.75 0 0 0-1.5 0v3.75a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V5.25a3 3 0 0 0-3-3h-6a3 3 0 0 0-3 3V9A.75.75 0 1 0 9 9V5.25a1.5 1.5 0 0 1 1.5-1.5h6Zm-5.03 4.72a.75.75 0 0 0 0 1.06l1.72 1.72H2.25a.75.75 0 0 0 0 1.5h10.94l-1.72 1.72a.75.75 0 1 0 1.06 1.06l3-3a.75.75 0 0 0 0-1.06l-3-3a.75.75 0 0 0-1.06 0Z"></path>
                </svg>
            </button>
        </form>
    </div>

    <div class="bg-emerald-600 p-6 rounded-[2rem] text-white shadow-xl shadow-emerald-200/50">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center border border-white/20 backdrop-blur-sm">
                <span class="font-bold text-2xl">{{ substr(auth()->user()->name, 0, 1) }}</span>
            </div>
            <div>
                <h2 class="font-bold text-xl tracking-wide">{{ auth()->user()->name }}</h2>
                <p class="text-emerald-100/90 text-sm font-medium mt-0.5">Warga RT 001 / RW 005</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-white/10 p-3 rounded-2xl border border-white/10">
                <p class="text-[10px] uppercase tracking-wider font-semibold opacity-70 mb-1">Status</p>
                <p class="font-bold text-sm">Aktif • Permanen</p>
            </div>
            <div class="bg-white/10 p-3 rounded-2xl border border-white/10">
                <p class="text-[10px] uppercase tracking-wider font-semibold opacity-70 mb-1">Terdaftar</p>
                <p class="font-bold text-sm">Januari 2024</p>
            </div>
        </div>
    </div>

    <div>
        <h3 class="font-bold text-gray-800 mb-3">Status Pengajuan</h3>
        <div class="grid grid-cols-4 gap-2">
            @php
                $statuses = [
                    'TUNGGU' => ['color' => 'amber', 'icon' => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z'],
                    'SETUJU' => ['color' => 'emerald', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    'TOLAK' => ['color' => 'red', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    'SELESAI' => ['color' => 'blue', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z']
                ];
            @endphp
            @foreach($statuses as $label => $data)
            <div class="bg-white p-3 rounded-2xl border border-gray-100 text-center shadow-sm">
                <svg class="w-6 h-6 mx-auto mb-1 text-{{ $data['color'] }}-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $data['icon'] }}" />
                </svg>
                <div class="text-lg font-bold text-gray-800">0</div>
                <div class="text-[9px] font-bold text-{{ $data['color'] }}-600">{{ $label }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <div>
    <h3 class="font-bold text-gray-800 mb-3">LAYANAN UTAMA RT/RW</h3>
    <div class="grid grid-cols-2 gap-3">
        <a href="{{ route('iuran.index') }}" class="bg-emerald-50 p-4 rounded-3xl border border-emerald-100 hover:shadow-md transition-all">
            <svg class="w-8 h-8 text-emerald-600 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h4 class="font-bold text-emerald-900 text-sm">Iuran Warga</h4>
            <p class="text-[10px] text-emerald-700">Kelola pembayaran bulanan</p>
        </a>

        <a href="{{ route('informasi.index') }}" class="bg-blue-50 p-4 rounded-3xl border border-blue-100 hover:shadow-md transition-all">
            <svg class="w-8 h-8 text-blue-600 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
            </svg>
            <h4 class="font-bold text-blue-900 text-sm">Informasi</h4>
            <p class="text-[10px] text-blue-700">Pengumuman dari pengurus</p>
        </a>
    </div>
</div>
<div x-data="{ open: false, chatOpen: false }" class="relative">
    <button @click="open = !open" type="button" class="w-full bg-amber-50 p-4 rounded-2xl flex justify-between items-center border border-amber-100 transition-all">
        <div class="flex items-center gap-3">
            <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <div class="text-left">
                <h4 class="font-bold text-amber-900 text-sm">Kontak Pengurus</h4>
            </div>
        </div>
    </button>

    <div x-show="open" @click.away="open = false" class="mt-2 grid grid-cols-2 gap-2">
        <a href="https://wa.me/628123456789" target="_blank" class="bg-white p-3 rounded-xl border border-amber-200 text-center text-xs font-bold text-amber-800">
            WhatsApp
        </a>
        <button type="button" @click="chatOpen = true; open = false" class="bg-white p-3 rounded-xl border border-amber-200 text-center text-xs font-bold text-amber-800">
            Live Chat
        </button>
    </div>

    <div x-show="chatOpen" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
        <div @click="chatOpen = false" class="absolute inset-0 bg-gray-900/40"></div>
        <div class="bg-white w-full max-w-sm rounded-3xl shadow-2xl relative h-[400px] flex flex-col">
            <div class="p-4 bg-emerald-600 text-white rounded-t-3xl flex justify-between">
                <span>Chat Admin</span>
                <button @click="chatOpen = false">X</button>
            </div>
            <div class="flex-1 p-4 bg-gray-50 overflow-y-auto">
                Halo, ada yang bisa dibantu?
            </div>
        </div>
    </div>
</div>
@endsection
