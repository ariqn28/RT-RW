@extends('layouts.app')

@section('content')
<div class="card card-panel shadow-sm p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1">Ubah Status Pengajuan</h2>
            <p class="text-muted mb-0">Perbarui status pengajuan surat RT/RW.</p>
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

    @php
        $role = auth()->user()->role;
        $options = $role === 'rt' && $pengajuan->status === 'baru'
            ? ['disetujui_rt' => 'Setujui sebagai RT', 'ditolak' => 'Tolak pengajuan']
            : ($role === 'rw' && $pengajuan->status === 'disetujui_rt'
                ? ['diterima' => 'Setujui sebagai RW', 'ditolak' => 'Tolak pengajuan']
                : []);
    @endphp

    @if(empty($options))
        <div class="alert alert-warning mb-0">Status ini tidak dapat diubah pada tahap verifikasi Anda.</div>
    @else
    <form action="{{ route('status.update', $pengajuan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                @foreach($options as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success px-4">Simpan</button>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary px-4">Batal</a>
    </form>
    @endif
</div>
@endsection
