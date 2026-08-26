@extends('layouts.warga_app')

@section('content')
<div class="container pb-20 px-4 mt-4">
    <div class="mb-6">
        <h2 class="h3 font-bold text-gray-800">Pengaturan Profil</h2>
        <p class="text-gray-500 text-sm">Update informasi data diri Anda.</p>
    </div>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded-xl mb-4 text-sm font-medium border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
        @csrf
        @method('PUT')

        <!-- Data Diri -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 space-y-4">
            <h3 class="text-sm font-bold text-gray-800 border-b pb-2">Data Diri</h3>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Nama Lengkap</label>
                <input type="text" name="name" class="w-full p-3 border border-gray-300 rounded-xl bg-gray-50" value="{{ old('name', auth()->user()->name) }}" required>
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Email</label>
                <input type="email" name="email" class="w-full p-3 border border-gray-300 rounded-xl bg-gray-50" value="{{ old('email', auth()->user()->email) }}" required>
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">NIK</label>
                <input type="text" name="nik" class="w-full p-3 border border-gray-300 rounded-xl bg-gray-50" value="{{ old('nik', auth()->user()->nik ?? '') }}">
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Alamat</label>
                <textarea name="alamat" class="w-full p-3 border border-gray-300 rounded-xl bg-gray-50" rows="2">{{ old('alamat', auth()->user()->alamat ?? '') }}</textarea>
            </div>
        </div>

        <!-- Aksi -->
        <div class="flex gap-3 pt-2">
            <button type="submit" class="flex-1 bg-blue-600 text-white font-bold py-3 rounded-xl shadow-lg">Simpan Perubahan</button>
            <a href="{{ route('dashboard') }}" class="flex-1 bg-gray-200 text-gray-700 font-bold py-3 rounded-xl text-center">Batal</a>
        </div>
    </form>
</div>
@endsection
