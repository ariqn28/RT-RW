@extends('layouts.app')

@section('content')
<div class="card card-panel shadow-sm p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1">Ajukan Surat</h2>
            <p class="text-muted mb-0">Isi data pengajuan dan lampirkan keterangan yang diperlukan.</p>
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

    <form action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Jenis Surat</label>
                <select name="jenis_surat" class="form-select @error('jenis_surat') is-invalid @enderror" required>
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
                @error('jenis_surat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">NIK</label>
                <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik') }}" required>
                @error('nik')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Alamat</label>
                <input type="text" name="alamat" class="form-control" value="{{ old('alamat') }}" placeholder="Contoh: Jl. Merpati No. 12" required>
                @error('alamat')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12">
                <label class="form-label">Upload Berkas (Opsional)</label>
                <input type="file" name="file" id="fileUpload" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                <small class="text-muted">Format: PDF, DOC, DOCX, JPG, PNG. Maksimal 1MB. Jika tidak ada file, biarkan kosong.</small>
                <div id="fileError" class="text-danger mt-1" style="display:none;"></div>
                @error('file')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12">
                <label class="form-label">Alasan</label>
                <textarea name="alasan" id="alasanTextarea" class="form-control @error('alasan') is-invalid @enderror" rows="4" placeholder="Contoh: Saya mengajukan surat keterangan domisili untuk keperluan pembuatan KTP..." required>{{ old('alasan') }}</textarea>
                @error('alasan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-success px-4">Kirim</button>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary px-4">Batal</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const jenisSuratSelect = document.querySelector('select[name="jenis_surat"]');
    const alasanTextarea = document.getElementById('alasanTextarea');

    const contohAlasan = {
        'Surat Keterangan Domisili': 'Saya mengajukan surat keterangan domisili untuk keperluan pembuatan KTP karena alamat saya belum tercatat dengan benar di sistem kependudukan.',
        'Surat Pengantar': 'Saya membutuhkan surat pengantar dari RT/RW untuk mengurus administrasi di kelurahan terkait pembuatan akta kelahiran anak saya.',
        'Surat Keterangan Tidak Mampu': 'Saya mengajukan surat keterangan tidak mampu untuk mendapatkan bantuan sosial dari pemerintah karena kondisi ekonomi keluarga yang kurang mampu.',
        'Surat Izin Keramaian': 'Saya mengajukan izin keramaian untuk mengadakan acara pernikahan di rumah pada tanggal 15 Mei 2026 pukul 19.00 WIB.',
        'Surat Keterangan Usaha': 'Saya mengajukan surat keterangan usaha untuk keperluan perpanjangan izin usaha mikro warung makan yang saya kelola.',
        'Surat Keterangan Pernikahan': 'Saya mengajukan surat keterangan pernikahan untuk melengkapi persyaratan administrasi pernikahan yang diminta oleh kantor kelurahan.',
        'Surat Pengantar KTP/KK': 'Saya mengajukan surat pengantar KTP/KK untuk pembuatan/pendaftaran KTP dan Kartu Keluarga di kantor kelurahan.',
        'Surat Keterangan Kelahiran': 'Saya mengajukan surat keterangan kelahiran untuk anak saya yang lahir pada tanggal 10 April 2026 di rumah sakit setempat.',
        'Surat Keterangan Kematian': 'Saya mengajukan surat keterangan kematian untuk ayah saya yang meninggal dunia pada tanggal 20 April 2026.',
        'Lainnya': 'Jelaskan alasan pengajuan surat Anda secara detail.'
    };

    jenisSuratSelect.addEventListener('change', function() {
        const selectedValue = this.value;
        if (contohAlasan[selectedValue]) {
            alasanTextarea.placeholder = 'Contoh: ' + contohAlasan[selectedValue];
        } else {
            alasanTextarea.placeholder = 'Jelaskan alasan pengajuan surat Anda secara detail.';
        }
    });

    if (jenisSuratSelect.value && contohAlasan[jenisSuratSelect.value]) {
        alasanTextarea.placeholder = 'Contoh: ' + contohAlasan[jenisSuratSelect.value];
    }

    // Validasi ukuran file upload (maksimal 1MB)
    const fileInput = document.getElementById('fileUpload');
    const fileError = document.getElementById('fileError');

    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const maxSize = 1 * 1024 * 1024; // 1MB
            if (file.size > maxSize) {
                fileError.textContent = 'Ukuran file melebihi 1MB. File Anda: ' + (file.size / 1024 / 1024).toFixed(2) + 'MB. Silakan kompres atau pilih file yang lebih kecil.';
                fileError.style.display = 'block';
                this.value = '';
            } else {
                fileError.style.display = 'none';
            }
        }
    });
});
</script>
@endsection
