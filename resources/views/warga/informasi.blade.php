@extends('layouts.warga_app')

@section('content')
<div class="px-4 py-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi & Pengumuman</h2>

    @forelse($announcements as $announcement)
        <article class="bg-white border border-gray-100 rounded-2xl overflow-hidden mb-4 shadow-sm">
            @if($announcement->image_path)<img src="{{ asset($announcement->image_path) }}" alt="{{ $announcement->title }}" class="w-full h-44 object-cover">@endif
            <div class="p-4">
                <p class="text-xs text-blue-600 font-bold">{{ $announcement->created_at->format('d M Y') }}</p>
                <h3 class="font-bold text-gray-800 mt-1">{{ $announcement->title }}</h3>
                <p class="text-sm text-gray-600 mt-2 whitespace-pre-line">{{ $announcement->body }}</p>
            </div>
        </article>
    @empty
        <div class="bg-blue-50 border border-blue-100 p-4 rounded-2xl text-sm text-blue-800">Belum ada pengumuman terbaru.</div>
    @endforelse

    <div class="mt-6 bg-amber-50 border border-amber-100 rounded-2xl p-4">
        <h3 class="font-bold text-amber-900">Kontak {{ $contact->office_name }}</h3>
        @if($contact->address)<p class="text-sm text-amber-800 mt-2">{{ $contact->address }}</p>@endif
        <div class="grid grid-cols-2 gap-2 mt-4">
            @if($contact->whatsapp)
                @php($wa = preg_replace('/[^0-9]/', '', $contact->whatsapp))
                <a href="https://wa.me/{{ $wa }}?text={{ urlencode($contact->chat_greeting) }}" target="_blank" rel="noopener" class="bg-white border border-amber-200 rounded-xl px-3 py-2 text-center text-sm font-bold text-amber-800">WhatsApp</a>
                <a href="https://wa.me/{{ $wa }}?text={{ urlencode($contact->chat_greeting) }}" target="_blank" rel="noopener" class="bg-emerald-600 rounded-xl px-3 py-2 text-center text-sm font-bold text-white">Live Chat</a>
            @endif
        </div>
        @if($contact->phone || $contact->email)
            <p class="text-xs text-gray-600 mt-3">{{ $contact->phone }}{{ $contact->phone && $contact->email ? ' · ' : '' }}{{ $contact->email }}</p>
        @endif
    </div>

    <a href="{{ route('dashboard') }}" class="mt-6 inline-block text-sm font-semibold text-blue-600">
        ← Kembali ke Dashboard
    </a>
</div>
@endsection
