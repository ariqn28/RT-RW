{{-- @extends('layouts.app')

@section('content') --}}
@extends('layouts.warga_app')

@section('content')
<div class="px-4 py-6 pb-24">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Ajukan Surat</h2>
        <p class="text-gray-500 text-sm">Lengkapi data di bawah ini untuk permohonan Anda.</p>
    </div>

    @if ($errors->has('file'))
    <div class="alert alert-danger">
        {{ $errors->first('file') }}
    </div>
@endif

    <form action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div class="bg-white p-5 rounded-3xl shadow-sm border border-gray-100 space-y-4">

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Jenis Surat</label>
                <select name="jenis_surat" class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-all" required>
                    <option value="">-- Pilih Jenis Surat --</option>
                    <option value="Surat Keterangan Domisili">Surat Keterangan Domisili</option>
                    <option value="Surat Pengantar">Surat Pengantar</option>
                    <option value="Surat Keterangan Tidak Mampu">Surat Keterangan Tidak Mampu</option>
                    <option value="Surat Izin Keramaian">Surat Izin Keramaian</option>
                    <option value="Surat Keterangan Usaha">Surat Keterangan Usaha</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Nama Lengkap</label>
                <input type="text" name="nama" class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:outline-none" value="{{ old('nama', auth()->user()->name) }}" required>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">NIK</label>
                <input type="number" name="nik" class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:outline-none" required>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Alamat</label>
                <input type="text" name="alamat" class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:outline-none" required>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Alasan Pengajuan</label>
                <textarea name="alasan" class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-emerald-500 focus:outline-none" rows="3" required>{{ old('alasan') }}</textarea>
            </div>

            <div>
    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Upload Berkas</label>

    <div class="relative border-2 border-dashed border-gray-200 rounded-2xl p-6 text-center hover:bg-gray-50 transition-colors">
        <input type="file" id="fileUpload" name="file" class="hidden">

        <label for="fileUpload" class="cursor-pointer">
            <div class="text-emerald-600 text-3xl mb-2">📁</div>
            <p class="text-xs text-gray-500">Klik untuk upload (PDF, JPG, PNG)</p>
        </label>

        <div id="fileNameDisplay" class="mt-3 text-xs font-semibold text-emerald-600 break-all"></div>
        <div id="fileError" class="mt-2 text-xs text-red-500" style="display:none;"></div>
    </div>

    <small class="text-gray-400 text-[10px] mt-1 block">Maksimal 2MB.</small>
</div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-emerald-200 transition-all">Kirim Pengajuan</button>
            <a href="{{ route('dashboard') }}" class="w-1/3 bg-gray-100 text-gray-600 font-bold py-4 rounded-2xl text-center hover:bg-gray-200 transition-all">Batal</a>
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
// Tambahkan elemen untuk menampilkan nama file (buat di HTML Anda <span id="fileNameDisplay"></span>)
const fileNameDisplay = document.getElementById('fileNameDisplay');

fileInput.addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        // Ubah maxSize menjadi 2MB (2 * 1024 * 1024)
        const maxSize = 2 * 1024 * 1024;

        if (file.size > maxSize) {
            fileError.textContent = 'Ukuran file terlalu besar (Max 2MB). File Anda: ' + (file.size / 1024 / 1024).toFixed(2) + 'MB.';
            fileError.style.display = 'block';
            fileError.style.color = 'red';
            fileNameDisplay.textContent = ''; // Hapus nama file jika error
            this.value = ''; // Reset input
        } else {
            fileError.style.display = 'none';
            // Berikan tanda berhasil pilih file
            fileNameDisplay.textContent = 'File terpilih: ' + file.name;
            fileNameDisplay.style.color = 'green';
            fileNameDisplay.style.fontWeight = 'bold';
        }
    }
});
});
</script>
@endsection
