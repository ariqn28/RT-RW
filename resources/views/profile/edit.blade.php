@extends('layouts.app')

@section('title', 'Pengaturan Akun')

@section('content')
<div class="card card-panel shadow-sm p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1">Pengaturan Akun</h2>
            <p class="text-muted mb-0">Kelola informasi profil dan keamanan akun.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success rounded-3 mb-4">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label fw-semibold">NIK</label>
                    <input type="text" class="form-control @error('nik') is-invalid @enderror" name="nik" value="{{ old('nik', auth()->user()->nik ?? '') }}" maxlength="16">
                    @error('nik')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label fw-semibold">Alamat</label>
                    <textarea class="form-control @error('alamat') is-invalid @enderror" name="alamat" rows="3">{{ old('alamat', auth()->user()->alamat ?? '') }}</textarea>
                    @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="mb-4">
            <h5 class="fw-semibold mb-3">Konfigurasi Wi‑Fi</h5>
            <p class="text-muted small mb-3">Ubah SSID dan password Wi‑Fi dari aplikasi ini tanpa harus membuka halaman router.</p>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">SSID Wi‑Fi</label>
                    <input type="text" class="form-control @error('wifi_ssid') is-invalid @enderror" name="wifi_ssid" value="{{ old('wifi_ssid', env('WIFI_SSID', '')) }}">
                    @error('wifi_ssid')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password Wi‑Fi</label>
                    <input type="password" class="form-control @error('wifi_password') is-invalid @enderror" name="wifi_password" value="{{ old('wifi_password') }}" autocomplete="new-password">
                    @error('wifi_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Kosongkan jika tidak ingin mengubah password saat ini.</div>
                </div>
            </div>
        </div>

        @if(auth()->user()->role === 'warga')
        <div class="mb-4">
            <h5 class="fw-semibold mb-3">Ganti Password</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Password Lama</label>
                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Password Baru</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation">
                </div>
            </div>
        </div>
        @endif

        <div class="d-flex gap-3">
            <button type="submit" class="btn btn-success px-4">
                <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
            </button>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary px-4">Batal</a>
        </div>
    </form>
</div>

<script>
    // Auto-hide success alert
    setTimeout(function() {
        document.querySelector('.alert')?.classList.add('fade', 'show');
        document.querySelector('.alert')?.addEventListener('transitionend', function() {
            this.remove();
        });
    }, 5000);
</script>
@endsection

