@extends('layouts.app')

@section('content')
<div class="card card-panel shadow-sm p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1">Riwayat Status</h2>
            <p class="text-muted mb-0">Lihat semua perubahan status pengajuan surat.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>

    @if($histories->count())
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Pengaju</th>
                    <th>Jenis Surat</th>
                    <th>Status</th>
                    <th>Diubah Oleh</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($histories as $h)
                <tr>
                    <td>{{ $h->created_at->format('d M Y H:i') }}</td>
                    <td>{{ $h->pengajuan->nama ?? '-' }}</td>
                    <td>{{ $h->pengajuan->jenis_surat ?? '-' }}</td>
                    <td>
                        <span class="badge bg-{{ $h->status == 'diterima' ? 'success' : ($h->status == 'ditolak' ? 'danger' : ($h->status == 'disetujui_rt' ? 'info' : 'secondary')) }}">
                            {{ ucfirst(str_replace('_', ' ', $h->status)) }}
                        </span>
                    </td>
                    <td>{{ $h->changedBy->name ?? 'Sistem' }}</td>
                    <td>{{ $h->note ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @else
    <div class="alert alert-info">Belum ada riwayat perubahan status.</div>
    @endif
    <div class="card-footer bg-transparent pt-3 pb-2 border-top mt-3">
        @if($histories->hasPages())
        <div class="d-flex justify-content-center">
            {{ $histories->onEachSide(1)->links('pagination::simple-bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection

