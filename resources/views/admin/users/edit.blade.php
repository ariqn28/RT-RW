@extends('layouts.app')

@section('content')
<div class="card card-panel shadow-sm p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1">Edit Role Pengguna</h2>
            <p class="text-muted mb-0">Perbarui peran untuk pengguna yang dipilih.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Kembali</a>
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

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" class="form-control" value="{{ $user->name }}" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" class="form-select" required>
                <option value="warga" {{ $user->role === 'warga' ? 'selected' : '' }}>Warga</option>
                <option value="rt" {{ $user->role === 'rt' ? 'selected' : '' }}>RT</option>
                <option value="rw" {{ $user->role === 'rw' ? 'selected' : '' }}>RW</option>
                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success px-4">Simpan</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
    </form>
</div>
@endsection