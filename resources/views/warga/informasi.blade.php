@extends('layouts.warga_app')

@section('content')
<div class="px-4 py-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi & Pengumuman</h2>

    <div class="bg-blue-50 border border-blue-100 p-4 rounded-3xl">
        <p class="text-sm text-blue-800">sedang dalam pengembangan .</p>
    </div>

    <a href="{{ route('dashboard') }}" class="mt-6 inline-block text-sm font-semibold text-blue-600">
        ← Kembali ke Dashboard
    </a>
</div>
@endsection
