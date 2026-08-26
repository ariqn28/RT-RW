@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Dashboard RT/RW</h1>
        <p class="text-muted mb-0">{{ auth()->user()->role === 'rt' ? 'RT' : 'RW' }} Admin Panel - Kelola Pengajuan Surat</p>
    </div>
    <span class="badge fs-6 bg-primary">{{ $counts['total'] }} Total Pengajuan</span>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body p-4">
                <div class="text-warning mb-2"><i class="bi bi-clock"></i> Baru</div>
                <div class="fs-2 fw-bold">{{ $counts['pending'] }}</div>
                <div class="text-muted small">Menunggu RT</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body p-4">
                <div class="text-info mb-2"><i class="bi bi-arrow-right-circle"></i> RT Approved</div>
                <div class="fs-2 fw-bold">{{ $counts['rt_pending'] ?? 0 }}</div>
                <div class="text-muted small">Menunggu RW {{ auth()->user()->role === 'rw' ? '(Anda)' : '' }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body p-4">
                <div class="text-success mb-2"><i class="bi bi-check-circle"></i> Final Approved</div>
                <div class="fs-2 fw-bold">{{ $counts['approved'] }}</div>
                <div class="text-muted small">Selesai</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body p-4">
                <div class="text-danger mb-2"><i class="bi bi-x-circle"></i> Ditolak</div>
                <div class="fs-2 fw-bold">{{ $counts['rejected'] }}</div>
                <div class="text-muted small">Rejected</div>
            </div>
        </div>
    </div>
</div>

{{-- Rekap Jenis Surat --}}
<div class="row g-3 mb-4">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-transparent">
                <h6 class="mb-0"><i class="bi bi-bar-chart-line"></i> Rekap Jenis Surat (Top 5)</h6>
            </div>
            <div class="card-body">
                @if(isset($jenisRekap) && $jenisRekap->count())
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Jenis Surat</th>
                                <th>Jumlah</th>
                                <th>Persen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jenisRekap as $item)
                            <tr>
                                <td>{{ Str::limit($item->jenis_surat, 25) }}</td>
                                <td><strong>{{ $item->total }}</strong></td>
                                <td>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar" style="width: {{ ($item->total / $counts['total'] * 100) }}%"></div>
                                    </div>
                                    <small class="text-muted">{{ number_format($item->total / $counts['total'] * 100, 1) }}%</small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="bi bi-graph-up opacity-50" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2">Belum ada data rekap</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    @if(auth()->user()->role === 'rw')
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-transparent">
                <h6 class="mb-0"><i class="bi bi-clock-history"></i> Pending RT ({{ $counts['rt_pending'] ?? 0 }})</h6>
            </div>
            <div class="card-body p-3">
                @if(isset($rtPending) && $rtPending->count())
                <div class="list-group list-group-flush">
                    @foreach($rtPending->take(8) as $p)
                    <a href="{{ route('status.show', $p->id) }}" class="list-group-item list-group-item-action border-0 px-0 py-2">
                        <div class="d-flex w-100 justify-content-between">
                            <small class="text-truncate">{{ $p->nama }}</small>
                            <span class="badge bg-info">{{ Str::limit($p->jenis_surat, 12) }}</span>
                        </div>
                    </a>
                    @endforeach
                </div>
                @if($rtPending->count() > 8)
                <small class="text-end d-block mt-2 text-muted">+{{ $rtPending->count() - 8 }} lagi</small>
                @endif
                @else
                <div class="text-center py-4 text-success">
                    <i class="bi bi-check-circle-fill fs-1 opacity-75 mb-2 d-block"></i>
                    <small>Semua pengajuan RT sudah diproses</small>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Main Table --}}
<div class="card shadow-lg border-0">
    <div class="card-header bg-primary text-white px-4 py-3">
        <div class="row g-3 align-items-center">
            <div class="col-md">
                <h5 class="mb-0"><i class="bi bi-list-ul"></i> Daftar Pengajuan (Semua Status)</h5>
            </div>
            <div class="col-md-auto">
                <div class="input-group input-group-sm" style="max-width: 220px;">
                    <select id="statusFilter" class="form-select form-select-sm border-0 shadow-sm">
                        <option value="">Filter Status</option>
                        <option value="baru">Baru (RT)</option>
                        <option value="disetujui_rt">Disetujui RT (RW)</option>
                        <option value="diterima">Diterima</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>
            </div>
            <div class="col-md-auto">
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('riwayat') }}" class="btn btn-outline-light"><i class="bi bi-clock-history"></i> Riwayat</a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-light"><i class="bi bi-people"></i> Users</a>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-dark">
                <tr>
                    <th style="width: 12%">Jenis</th>
                    <th style="width: 12%">Warga</th>
                    <th style="width: 10%">NIK</th>
                    <th style="width: 12%">Status</th>
                    <th style="width: 18%">Alasan</th>
                    <th style="width: 10%">Dibuat</th>
                    <th style="width: 12%">Oleh RT</th>
                    <th style="width: 14%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="pengajuanTableBody">
                @forelse($pengajuan as $p)
                <tr>
                    <td>
                        <span class="badge bg-light text-dark fs-6 px-2 py-1 rounded-pill">{{ Str::limit($p->jenis_surat, 12) }}</span>
                    </td>
                    <td class="fw-medium">{{ $p->nama }}</td>
                    <td><small class="text-muted">{{ Str::limit($p->nik, 16) }}</small></td>
                    <td>
                        @php
                            $statusCfg = [
                                'baru' => ['secondary', 'Menunggu RT'],
                                'disetujui_rt' => ['warning', 'Menunggu RW'],
                                'diterima' => ['success', 'Selesai'],
                                'ditolak' => ['danger', 'Ditolak']
                            ];
                            [$color, $label] = $statusCfg[$p->status] ?? ['secondary', ucfirst($p->status)];
                        @endphp
                        <span class="badge bg-{{ $color }} px-2 py-1">{{ $label }}</span>
                    </td>
                    <td><small class="text-muted">{{ Str::limit($p->alasan, 40) }}</small></td>
                    <td><small>{{ $p->created_at->format('d/m H:i') }}</small></td>
                    <td>
                        @if($p->statusHistories->where('changed_by.role', 'rt')->first())
                        <small class="text-primary">{{ $p->statusHistories->where('changed_by.role', 'rt')->first()->changedBy->name }}</small>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('status.show', $p->id) }}" class="btn btn-outline-success btn-sm" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('status.edit', $p->id) }}" class="btn btn-outline-primary btn-sm" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <div class="btn-group" role="group">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle px-2" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    @if(auth()->user()->role === 'rt' && $p->status === 'baru')
                                    <li>
                                        <form method="POST" action="{{ route('status.approve', $p->id) }}" class="px-2">
                                            @csrf
                                            <button type="submit" class="dropdown-item rounded">
                                                <i class="bi bi-check-circle text-success me-1"></i>Setujui RT
                                            </button>
                                        </form>
                                    </li>
                                    @endif
                                    @if(auth()->user()->role === 'rw' && $p->status === 'disetujui_rt')
                                    <li>
                                        <form method="POST" action="{{ route('status.approve', $p->id) }}" class="px-2">
                                            @csrf
                                            <button type="submit" class="dropdown-item rounded bg-success bg-opacity-10 text-success">
                                                <i class="bi bi-check-circle-fill me-1"></i>Final Approve RW
                                            </button>
                                        </form>
                                    </li>
                                    @endif
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('status.reject', $p->id) }}" class="px-2">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Tolak pengajuan ini?')">
                                                <i class="bi bi-x-circle me-1"></i>Tolak
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox display-4 opacity-25 mb-3 d-block"></i>
                        <h5>Belum ada pengajuan</h5>
                        <p class="mb-0">Semua pengajuan sudah diproses atau belum ada data.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-transparent py-3">
        @if($pengajuan->hasPages())
        <div class="d-flex justify-content-center">
            {{ $pengajuan->appends(request()->query())->links('pagination::simple-bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.getElementById('statusFilter')?.addEventListener('change', function() {
    const url = new URL(window.location);
    url.searchParams.set('status', this.value);
    window.location = url;
});
</script>
@endpush
@endsection
