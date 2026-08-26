@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Dashboard RW</h1>
        <p class="text-muted">Validasi final pengajuan - approve RW</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="card border-start border-warning border-3 h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-warning text-dark rounded-circle p-3 me-3">
                        <i class="bi bi-arrow-right-circle fs-4"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">{{ $counts['rt_pending'] ?? 0 }}</h5>
                        <small class="text-muted">Disetujui RT (validasi RW)</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card border-start border-success border-3 h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-success text-white rounded-circle p-3 me-3">
                        <i class="bi bi-check-circle-fill fs-4"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">{{ $counts['approved'] ?? 0 }}</h5>
                        <small class="text-muted">Selesai RW</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card border-start border-danger border-3 h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-danger text-white rounded-circle p-3 me-3">
                        <i class="bi bi-x-circle fs-4"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">{{ $counts['rejected'] ?? 0 }}</h5>
                        <small class="text-muted">Ditolak RW</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card border-start border-info border-3 h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-info text-white rounded-circle p-3 me-3">
                        <i class="bi bi-list-nested fs-4"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">{{ $counts['total'] ?? 0 }}</h5>
                        <small class="text-muted">Total bulan ini</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Pending RT Quick Actions --}}
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-transparent border-0 pb-3">
                <h5 class="mb-0"><i class="bi bi-arrow-right-circle text-warning me-2"></i>Pending RT ({{ $rtPending->count() ?? 0 }})</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Warga</th>
                                <th>Jenis Surat</th>
                                <th>RT Approve</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rtPending ?? [] as $p)
                            <tr>
                                <td>
                                    <div>
                                        <div class="fw-bold">{{ $p->nama }}</div>
                                        <small class="text-muted">{{ $p->nik }}</small>
                                    </div>
                                </td>
                                <td>{{ Str::limit($p->jenis_surat, 25) }}</td>
                                <td>{{ $p->statusHistories->where('status', 'disetujui_rt')->first()?->created_at->format('d M Y') ?? '-' }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-warning dropdown-toggle" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{ route('status.show', $p) }}"><i class="bi bi-eye"></i> Detail</a></li>
                                            <li><form method="POST" action="{{ route('status.approve', $p) }}" class="dropdown-item p-0">
                                                @csrf
                                                <button class="dropdown-item text-success"><i class="bi bi-check2-circle me-2"></i>Final Approve RW</button>
                                            </form></li>
                                            <li><form method="POST" action="{{ route('status.reject', $p) }}" class="dropdown-item p-0">
                                                @csrf
                                                <button class="dropdown-item text-danger"><i class="bi bi-x-circle me-2"></i>Tolak RW</button>
                                            </form></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="bi bi-check-circle-fill fs-1 mb-3 text-success opacity-50"></i>
                                    <div>Semua pengajuan selesai</div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-transparent border-0 pb-3">
                <h5 class="mb-0"><i class="bi bi-people-fill text-info me-2"></i>Statistik Warga</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0 border-0 d-flex justify-content-between">
                        <span>Total Warga</span>
                        <span class="badge bg-info">{{ User::where('role', 'warga')->count() }}</span>
                    </li>
                    <li class="list-group-item px-0 border-0 d-flex justify-content-between">
                        <span>RT Active</span>
                        <span class="badge bg-primary">{{ User::where('role', 'rt')->count() }}</span>
                    </li>
                    <li class="list-group-item px-0 border-0 d-flex justify-content-between">
                        <span>RT Last 7 days</span>
                        <span class="badge bg-success">{{ Pengajuan::where('created_at', '>=', now()->subDays(7))->count() }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<a href="{{ route('pengajuan.index') }}" class="btn btn-outline-primary btn-lg">
    <i class="bi bi-arrow-left me-2"></i>Lihat Semua Pengajuan
</a>

@endsection
