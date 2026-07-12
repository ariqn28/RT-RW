@extends('layouts.app')

@section('title', 'Admin RT - Kelola Pengajuan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Admin RT</h1>
        <p class="text-muted mb-0">Kelola pengajuan warga & approve RT</p>
    </div>
    <div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary me-2">
            <i class="bi bi-people me-1"></i>Manajemen User
        </a>
        <a href="{{ route('dashboard') }}" class="btn btn-success">
            <i class="bi bi-house me-1"></i>Dashboard
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card h-100 shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-clock-history text-warning fs-1 mb-3"></i>
                <h3 class="mb-1">{{ $counts['pending'] ?? 0 }}</h3>
                <p class="text-muted mb-0">Pengajuan Baru</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card h-100 shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-check-circle text-success fs-1 mb-3"></i>
                <h3 class="mb-1">{{ $counts['approved'] ?? 0 }}</h3>
                <p class="text-muted mb-0">Disetujui RT</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card h-100 shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-x-circle text-danger fs-1 mb-3"></i>
                <h3 class="mb-1">{{ $counts['rejected'] ?? 0 }}</h3>
                <p class="text-muted mb-0">Ditolak</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card h-100 shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-people text-info fs-1 mb-3"></i>
                <h3 class="mb-1">{{ $counts['total'] ?? 0 }}</h3>
                <p class="text-muted mb-0">Total</p>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-lg border-0 mb-4">
    <div class="card-header bg-white border-0 pb-0">
        <h5 class="mb-3"><i class="bi bi-list-ul text-primary me-2"></i>Pengajuan Aktif</h5>
        <div class="row g-2 mb-3">
            <div class="col-auto">
                <select class="form-select form-select-sm" id="statusFilter">
                    <option value="">Semua Status</option>
                    <option value="baru">Baru</option>
                    <option value="disetujui_rt">RT Done</option>
                    <option value="diterima">RW Done</option>
                    <option value="ditolak">Ditolak</option>
                </select>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        @if($pengajuan->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 5%">#</th>
                        <th style="width: 20%">Warga</th>
                        <th style="width: 15%">Jenis</th>
                        <th style="width: 12%">Status</th>
                        <th style="width: 25%">Alasan</th>
                        <th style="width: 12%">Dibuat</th>
                        <th style="width: 11%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengajuan as $index => $p)
                    <tr>
                        <td>{{ ($pengajuan->currentPage()-1) * $pengajuan->perPage() + $index + 1 }}</td>
                        <td>
                            <div class="fw-bold">{{ $p->nama }}</div>
                            <small class="text-muted">{{ $p->nik }}</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark px-2 py-1">{{ Str::limit($p->jenis_surat, 15) }}</span>
                            @if($p->file_path)
                            <small class="d-block text-success">
                                <i class="bi bi-file-earmark-pdf"></i> Ada lampiran
                            </small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $p->status == 'diterima' ? 'success' : ($p->status == 'ditolak' ? 'danger' : ($p->status == 'disetujui_rt' ? 'warning' : 'secondary')) }} fs-6 px-2 py-1">
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                        <td>{{ Str::limit($p->alasan, 35) }}</td>
                        <td><small class="text-muted">{{ $p->created_at->format('d M Y H:i') }}</small></td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('status.show', $p) }}" class="btn btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($p->status === 'baru')
                                <form method="POST" action="{{ route('status.approve', $p) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success" title="Approve RT">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                                @endif
                                <form method="POST" action="{{ route('status.reject', $p) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Tolak pengajuan?')" title="Reject">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
            </table>
        </div>
        @else
        <div class="p-5 text-center text-muted">
            <i class="bi bi-inbox display-1 mb-3 opacity-25"></i>
            <h5>Belum ada pengajuan baru</h5>
            <p class="mb-0">Menunggu pengajuan dari warga...</p>
        </div>
        @endif
                <div class="card-footer bg-transparent p-2 border-top">
            @if($pengajuan->hasPages())
            <div class="d-flex justify-content-center">
                <nav aria-label="RT Pagination" class="mb-n2">
                    {{ $pengajuan->onEachSide(0)->links('pagination::simple-bootstrap-5') }}
                </nav>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
document.getElementById('statusFilter').addEventListener('change', function() {
    const url = new URL(window.location);
    url.searchParams.set('status', this.value);
    window.location = url;
});
</script>
@endsection
