@extends('layouts.app')

@section('content')
<div class="card card-panel shadow-sm p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1">Ajukan Surat (Mode Sederhana)</h2>
            <p class="text-muted mb-0">Gunakan mode ini jika form biasa error. Tidak ada upload file.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <form action="{{ route('pengajuan.store') }}" method="POST">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Jenis Surat *</label>
                <select name="jenis_surat" class="form-select" required>
                    <option value="">Pilih Jenis Surat</option>
                    <option value="Surat Keterangan Domisili" {{ old('jenis_surat') == 'Surat Keterangan Domisili' ? 'selected' : '' }}>Surat Keterangan Domisili</option>
                    <option value="Surat Pengantar" {{ old('jenis_surat') == 'Surat Pengantar' ? 'selected' : '' }}>Surat Pengantar</option>
                    <option value="Surat Keterangan Tidak Mampu" {{ old('jenis_surat') == 'Surat Keterangan Tidak Mampu' ? 'selected' : '' }}>Surat Keterangan Tidak Mampu</option>
                    <option value="Surat Izin Keramaian" {{ old('jenis_surat') == 'Surat Izin Keramaian' ? 'selected' : '' }}>Surat Izin Keramaian</option>
                    <option value="Surat Keterangan Usaha" {{ old('jenis_surat') == 'Surat Keterangan Usaha' ? 'selected' : '' }}>Surat Keterangan Usaha</option>
                    <option value="Surat Keterangan Pernikahan" {{ old('jenis_surat') == 'Surat Keterangan Pernikahan' ? 'selected' : '' }}>Surat Keterangan Pernikahan</option>
                    <option value="Surat Pengantar KTP/KK" {{ old('jenis_surat') == 'Surat Pengantar KTP/KK' ? 'selected' : '' }}>Surat Pengantar KTP/KK</option>
                    <option value="Surat Keterangan Kelahiran" {{ old('jenis_surat') == 'Surat Keterangan Kelahiran' ? 'selected' : '' }}>Surat Keterangan Kelahiran</option>
                    <option value="Surat Keterangan Kematian" {{ old('jenis_surat') == 'Surat Keterangan Kematian' ? 'selected' : '' }}>Surat Keterangan Kematian</option>
                    <option value="Lainnya" {{ old('jenis_surat') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nama *</label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">NIK *</label>
                <input type="text" name="nik" class="form-control" value="{{ old('nik') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Alamat *</label>
                <input type="text" name="alamat" class="form-control" value="{{ old('alamat') }}" placeholder="Contoh: Jl. Merpati No. 12" required>
            </div>
            <div class="col-12">
                <label class="form-label">Alasan *</label>
                <textarea name="alasan" class="form-control" rows="4" placeholder="Jelaskan alasan pengajuan surat Anda..." required>{{ old('alasan') }}</textarea>
            </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-success px-4">Kirim</button>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary px-4">Batal</a>
        </div>
    </form>

    <hr class="my-4">
    <div class="alert alert-info">
        <strong>Mode sederhana ini:</strong>
        <ul class="mb-0">
            <li>Tidak menggunakan JavaScript/AJAX</li>
            <li>Tidak bisa upload file</li>
            <li>Form submit langsung ke server (refresh halaman)</li>
            <li>Cocok untuk isolasi masalah "Gagal terhubung ke server"</li>
        </ul>
    </div>
@endsection
