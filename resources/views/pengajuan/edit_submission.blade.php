@extends('layouts.warga_app')

@section('content')
<div class="px-4 py-6 pb-24">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Ubah Pengajuan</h1>
        <p class="mt-1 text-sm text-gray-500">Periksa kembali data sebelum pengajuan diproses oleh RT.</p>
    </div>

    @if($errors->any())
        <div class="mb-4 rounded-2xl bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('pengajuan.update', $pengajuan) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf @method('PUT')
        <div class="space-y-4 rounded-3xl border border-gray-100 bg-white p-5 shadow-sm">
            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-600">Jenis Surat</label>
                <select name="jenis_surat" class="w-full rounded-2xl border border-gray-200 bg-gray-50 p-4" required>
                    @foreach(['Surat Keterangan Domisili', 'Surat Pengantar', 'Surat Keterangan Tidak Mampu', 'Surat Izin Keramaian', 'Surat Keterangan Usaha', 'Lainnya'] as $jenis)
                        <option value="{{ $jenis }}" {{ old('jenis_surat', $pengajuan->jenis_surat) === $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                    @endforeach
                </select>
            </div>
            <div><label class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-600">Nama Lengkap</label><input name="nama" value="{{ old('nama', $pengajuan->nama) }}" class="w-full rounded-2xl border border-gray-200 bg-gray-50 p-4" required></div>
            <div><label class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-600">NIK</label><input name="nik" value="{{ old('nik', $pengajuan->nik) }}" inputmode="numeric" maxlength="20" class="w-full rounded-2xl border border-gray-200 bg-gray-50 p-4" required></div>
            <div><label class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-600">Alamat</label><input name="alamat" value="{{ old('alamat', $pengajuan->alamat) }}" class="w-full rounded-2xl border border-gray-200 bg-gray-50 p-4" required></div>
            <div><label class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-600">Alasan Pengajuan</label><textarea name="alasan" rows="4" class="w-full rounded-2xl border border-gray-200 bg-gray-50 p-4" required>{{ old('alasan', $pengajuan->alasan) }}</textarea></div>
            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-600">Ganti Lampiran <span class="normal-case text-gray-400">(opsional)</span></label>
                <input type="file" name="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="w-full rounded-2xl border border-gray-200 bg-gray-50 p-3">
                <p class="mt-2 text-xs text-gray-500">{{ $pengajuan->file_path ? 'Lampiran saat ini akan diganti jika Anda memilih file baru.' : 'PDF, DOC, DOCX, JPG, atau PNG. Maksimal 2 MB.' }}</p>
            </div>
        </div>
        <div class="flex gap-3">
            <button class="flex-1 rounded-2xl bg-emerald-600 py-4 font-bold text-white">Simpan Perubahan</button>
            <a href="{{ route('status.show', $pengajuan) }}" class="rounded-2xl bg-gray-100 px-5 py-4 font-bold text-gray-600">Batal</a>
        </div>
    </form>
</div>
@endsection
