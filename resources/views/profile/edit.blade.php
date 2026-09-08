@extends(in_array(auth()->user()->role, ['admin', 'rt', 'rw']) ? 'layouts.app' : 'layouts.warga_app')

@section('content')
<div class="container pb-20 px-4 mt-4">
    <div class="mb-6">
        <h2 class="h3 {{ in_array(auth()->user()->role, ['admin', 'rt', 'rw']) ? 'text-dark' : 'font-bold text-gray-800' }}">Pengaturan Profil</h2>
        <p class="{{ in_array(auth()->user()->role, ['admin', 'rt', 'rw']) ? 'text-muted' : 'text-gray-500' }} text-sm">Perbarui informasi akun {{ strtoupper(auth()->user()->role) }} Anda.</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" class="{{ in_array(auth()->user()->role, ['admin', 'rt', 'rw']) ? '' : 'space-y-4' }}">
        @csrf
        @method('PUT')

        <div class="{{ in_array(auth()->user()->role, ['admin', 'rt', 'rw']) ? 'card shadow-sm mb-4' : 'bg-white p-5 rounded-2xl shadow-sm border border-gray-100 space-y-4' }}">
            <div class="{{ in_array(auth()->user()->role, ['admin', 'rt', 'rw']) ? 'card-body' : '' }}">
            <h3 class="{{ in_array(auth()->user()->role, ['admin', 'rt', 'rw']) ? 'h5 mb-4' : 'text-sm font-bold text-gray-800 border-b pb-2' }}">Data Diri</h3>

            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">NIK</label>
                <input type="text" name="nik" class="form-control" value="{{ old('nik', auth()->user()->nik ?? '') }}">
            </div>

            <div>
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', auth()->user()->alamat ?? '') }}</textarea>
            </div>
            </div>
        </div>

        <div class="{{ in_array(auth()->user()->role, ['admin', 'rt', 'rw']) ? 'card shadow-sm mb-4' : 'bg-white p-5 rounded-2xl shadow-sm border border-gray-100 space-y-4' }}">
            <div class="{{ in_array(auth()->user()->role, ['admin', 'rt', 'rw']) ? 'card-body' : '' }}">
                <h3 class="{{ in_array(auth()->user()->role, ['admin', 'rt', 'rw']) ? 'h5 mb-4' : 'text-sm font-bold text-gray-800 border-b pb-2' }}">Keamanan Akun</h3>
                <div class="mb-3">
                    <label class="form-label">Password Saat Ini</label>
                    <input type="password" name="current_password" class="form-control" autocomplete="current-password">
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password" class="form-control" autocomplete="new-password">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 pt-2">
            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
            <a href="{{ route(auth()->user()->role === 'rt' ? 'dashboard.rt' : (auth()->user()->role === 'rw' ? 'dashboard.rw' : 'dashboard')) }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
