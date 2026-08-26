@extends('layouts.app')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card card-panel p-4 h-100">
            <div class="text-success mb-2">Pengajuan Baru</div>
            <div class="fs-2 fw-bold">{{ $counts['pending'] }}</div>
            <div class="text-muted">Menunggu persetujuan RT/RW</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-panel p-4 h-100">
            <div class="text-success mb-2">Disetujui</div>
            <div class="fs-2 fw-bold">{{ $counts['approved'] }}</div>
            <div class="text-muted">Surat yang telah diterima</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-panel p-4 h-100">
            <div class="text-success mb-2">Ditolak</div>
            <div class="fs-2 fw-bold">{{ $counts['rejected'] }}</div>
            <div class="text-muted">Pengajuan yang ditolak</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-panel p-4 h-100">
            <div class="text-success mb-2">Total Pengajuan</div>
            <div class="fs-2 fw-bold">{{ $counts['total'] }}</div>
            <div class="text-muted">Semua pengajuan surat</div>
        </div>
    </div>
</div>

<div class="mb-4">
    <a href="{{ route('admin.users.index') }}" class="btn btn-success btn-lg">Buka Manajemen User</a>
</div>

<div class="card card-panel shadow-sm p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="card-title mb-1">Statistik Pengguna</h5>
            <p class="text-muted mb-0">Admin hanya melihat statistik. Persetujuan diajukan oleh RT/RW.</p>
        </div>
        <span class="badge bg-success">Admin Mode</span>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card card-panel p-4 h-100">
                <div class="text-success mb-2">Jumlah Warga</div>
                <div class="fs-2 fw-bold">{{ $roleCounts['warga'] ?? 0 }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-panel p-4 h-100">
                <div class="text-success mb-2">Jumlah RT</div>
                <div class="fs-2 fw-bold">{{ $roleCounts['rt'] ?? 0 }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-panel p-4 h-100">
                <div class="text-success mb-2">Jumlah RW</div>
                <div class="fs-2 fw-bold">{{ $roleCounts['rw'] ?? 0 }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card card-panel shadow-sm p-4">
    <h5 class="card-title mb-3">Catatan Admin</h5>
    <p class="text-muted mb-0">Sistem ini mendukung 3 peran utama:</p>
    <ul class="mt-3">
        <li><strong>Warga</strong>: hanya dapat mengajukan surat dan melihat status pengajuannya sendiri.</li>
        <li><strong>RT/RW</strong>: dapat melihat semua pengajuan dan memproses status.</li>
        <li><strong>Admin</strong>: melihat statistik umum dan tidak memproses pengajuan langsung.</li>
    </ul>
</div>
@endsection