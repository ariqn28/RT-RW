@extends('layouts.app')

@section('content')
<div class="card card-panel shadow-sm p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-1">Status Pengajuan</h2>
            <p class="text-muted mb-0">Lihat perkembangan pengajuan surat secara detail.</p>
        </div>
        <div>
            @if(!$isAdmin && auth()->check() && in_array(auth()->user()->role, ['rt', 'rw']))
                @php
                    $showApprove = (auth()->user()->role === 'rt' && $pengajuan->status === 'baru') ||
                                   (auth()->user()->role === 'rw' && $pengajuan->status === 'disetujui_rt');
                @endphp

                @if($showApprove)
                    <form action="{{ route('status.approve', $pengajuan->id) }}" method="POST" class="d-inline-block me-2">
                        @csrf
                        <button type="submit" class="btn btn-success">Setujui ({{ strtoupper(auth()->user()->role) }})</button>
                    </form>
                    <form action="{{ route('status.reject', $pengajuan->id) }}" method="POST" class="d-inline-block me-2">
                        @csrf
                        <button type="submit" class="btn btn-danger">Tolak</button>
                    </form>
                @endif
                <a href="{{ route('status.edit', $pengajuan->id) }}" class="btn btn-primary me-2">Ubah Status</a>
            @endif
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Informasi Pengajuan</h5>
                    <p class="mb-2"><strong>Jenis Surat:</strong> {{ $pengajuan->jenis_surat }}</p>
                    <p class="mb-2"><strong>Nama:</strong> {{ $pengajuan->nama }}</p>
                    <p class="mb-2"><strong>NIK:</strong> {{ $pengajuan->nik }}</p>
                    <p class="mb-2"><strong>Alamat:</strong> {{ $pengajuan->alamat }}</p>
                    <p class="mb-2"><strong>Alasan:</strong> {{ $pengajuan->alasan }}</p>
                    @if($pengajuan->file_path)
                        <p class="mb-2"><strong>Berkas:</strong> <a href="{{ Storage::url($pengajuan->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">Download</a></p>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm p-4 mb-4">
                <h5 class="mb-3">Rangkaian Status</h5>
                <div class="progress-step">
                    <div class="step {{ in_array($pengajuan->status, ['baru','disetujui_rt','diterima','ditolak']) ? 'complete' : '' }}">
                        Diajukan
                    </div>
                    <div class="step {{ in_array($pengajuan->status, ['disetujui_rt','diterima']) ? 'complete' : '' }}">
                        Disetujui RT
                    </div>
                    <div class="step {{ $pengajuan->status == 'diterima' ? 'complete' : ($pengajuan->status == 'ditolak' ? 'rejected' : '') }}">
                        {{ $pengajuan->status == 'ditolak' ? 'Ditolak' : 'Selesai' }}
                    </div>
                </div>
                <div class="timeline-line"></div>
                <div class="mt-3">
                    <strong>Status saat ini:</strong>
                    <span class="badge bg-{{ $pengajuan->status == 'diterima' ? 'success' : ($pengajuan->status == 'ditolak' ? 'danger' : ($pengajuan->status == 'disetujui_rt' ? 'info' : 'secondary')) }} ms-2">
                        {{ str_replace('_', ' ', ucfirst($pengajuan->status)) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h5 class="card-title">Catatan Pengajuan</h5>
                <p class="text-muted mb-3">Informasi tambahan tentang status saat ini.</p>
                @if($pengajuan->status == 'baru')
                    <div class="alert alert-secondary">Pengajuan Anda telah dikirim dan sedang menunggu verifikasi.</div>
                @elseif($pengajuan->status == 'disetujui_rt')
                    <div class="alert alert-info">Sudah disetujui RT, menunggu verifikasi akhir dari RW.</div>
                @elseif($pengajuan->status == 'diterima')
                    <div class="alert alert-success">Surat sudah disetujui. Silakan ambil ke kantor RT/RW atau cek kembali notifikasi.</div>
                @else
                    <div class="alert alert-danger">Pengajuan ditolak. Mohon periksa data dan ajukan ulang jika diperlukan.</div>
                @endif
                <p><strong>Terakhir diperbarui:</strong> {{ $pengajuan->updated_at->format('d M Y H:i') }}</p>
            </div>

            <div class="card border-0 shadow-sm p-4">
                <h5 class="card-title">Riwayat Status</h5>
                @if($pengajuan->statusHistories->count())
                    <div class="list-group list-group-flush">
                        @foreach($pengajuan->statusHistories as $history)
                            <div class="list-group-item px-0 py-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <strong>{{ ucfirst($history->status) }}</strong>
                                        oleh {{ $history->changedBy->name ?? 'Sistem' }}
                                    </div>
                                    <small class="text-muted">{{ $history->created_at->format('d M Y H:i') }}</small>
                                </div>
                                @if($history->note)
                                    <div class="text-muted">{{ $history->note }}</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info mb-0">Riwayat status belum tersedia.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

