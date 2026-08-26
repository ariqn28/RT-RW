@extends('layouts.app')

@section('title', 'Riwayat Status')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Riwayat Status</h1>
        <p class="text-muted mb-0">Log seluruh perubahan status pengajuan surat.</p>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-success">
        <i class="bi bi-house me-1"></i>Dashboard
    </a>
</div>

<div class="card shadow-lg border-0">
    <div class="card-header bg-white border-0 pb-0">
        <h5 class="mb-3"><i class="bi bi-clock-history text-primary me-2"></i>Daftar Riwayat</h5>
    </div>
    <div class="card-body p-0">
        @if($histories->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 5%">#</th>
                        <th style="width: 15%">Jenis Surat</th>
                        <th style="width: 12%">Pemohon</th>
                        <th style="width: 12%">Status</th>
                        <th style="width: 28%">Catatan</th>
                        <th style="width: 13%">Diproses Oleh</th>
                        <th style="width: 15%">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($histories as $index => $h)
                    @php
                        $statusColors = [
                            'diterima' => 'success',
                            'ditolak' => 'danger',
                            'disetujui_rt' => 'warning',
                        ];
                        $badge = $statusColors[$h->status] ?? 'secondary';
                    @endphp
                    <tr>
                        <td>{{ ($histories->currentPage()-1) * $histories->perPage() + $index + 1 }}</td>
                        <td><span class="fw-bold">{{ Str::limit($h->pengajuan->jenis_surat ?? '-', 25) }}</span></td>
                        <td><small class="text-muted">{{ $h->pengajuan->user->name ?? '-' }}</small></td>
                        <td>
                            <span class="badge bg-{{ $badge }} px-2 py-1">
                                {{ ucfirst(str_replace('_', ' ', $h->status)) }}
                            </span>
                        </td>
                        <td><small>{{ Str::limit($h->note ?? '-', 60) }}</small></td>
                        <td><small class="text-muted">{{ $h->changedBy->name ?? 'Sistem' }}</small></td>
                        <td><small class="text-muted">{{ $h->created_at->format('d M Y H:i') }}</small></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-transparent border-top d-flex justify-content-center py-2">
            {{ $histories->onEachSide(1)->links('pagination::simple-bootstrap-5') }}
        </div>
        @else
        <div class="p-5 text-center text-muted">
            <i class="bi bi-inbox display-1 mb-3 opacity-25"></i>
            <h5>Belum ada riwayat perubahan status</h5>
            <p class="mb-0">Riwayat akan muncul setelah ada pengajuan diproses.</p>
        </div>
        @endif
    </div>
</div>
@endsection
