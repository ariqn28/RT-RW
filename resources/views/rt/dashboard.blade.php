@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Dashboard RT</h1>
        <p class="text-muted">Kelola pengajuan warga - approve RT</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="card border-start border-primary border-3 h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-primary text-white rounded-circle p-3 me-3">
                        <i class="bi bi-clock-history fs-4"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">{{ $counts['pending'] ?? 0 }}</h5>
                        <small class="text-muted">Baru (approve)</small>
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
                        <i class="bi bi-check-circle fs-4"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">{{ $counts['approved'] ?? 0 }}</h5>
                        <small class="text-muted">Disetujui RT</small>
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
                        <small class="text-muted">Ditolak RT</small>
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
                        <i class="bi bi-people fs-4"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">{{ $counts['total'] ?? 0 }}</h5>
                        <small class="text-muted">Total warga</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-transparent border-0 pb-3">
                <h5 class="mb-0"><i class="bi bi-list-check text-primary me-2"></i>Pengajuan Baru</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Warga</th>
                                <th>Jenis Surat</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pending ?: [] as $p)
                            <tr>
                                <td>
                                    <div>
                                        <div class="fw-bold">{{ $p->nama }}</div>
                                        <small class="text-muted">{{ $p->nik }}</small>
                                    </div>
                                </td>
                                <td>{{ Str::limit($p->jenis_surat, 20) }}</td>
                                <td>{{ $p->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{ route('status.show', $p) }}"><i class="bi bi-eye"></i> Detail</a></li>
                                            <li><form method="POST" action="{{ route('status.approve', $p) }}" class="dropdown-item p-0">
                                                @csrf
                                                <button class="dropdown-item text-success"><i class="bi bi-check-lg me-2"></i>Setujui RT</button>
                                            </form></li>
                                            <li><form method="POST" action="{{ route('status.reject', $p) }}" class="dropdown-item p-0">
                                                @csrf
                                                <button class="dropdown-item text-danger"><i class="bi bi-x-lg me-2"></i>Tolak</button>
                                            </form></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @if(!($pending ?? [])->count())
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="bi bi-check-circle fs-1 mb-3"></i>
                                    <div>Tidak ada pengajuan baru</div>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-transparent border-0 pb-3">
                <h5 class="mb-0"><i class="bi bi-bar-chart text-success me-2"></i>Rekap Jenis Surat</h5>
            </div>
            <div class="card-body">
                @if(isset($jenisRekap) && $jenisRekap->count())
                <ul class="list-group list-group-flush">
                    @foreach($jenisRekap as $item)
                    <li class="list-group-item px-0 border-0 d-flex justify-content-between">
                        <span>{{ Str::limit($item->jenis_surat, 25) }}</span>
                        <span class="badge bg-primary rounded-pill">{{ $item->total }}</span>
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="text-center text-muted py-4">
                    <i class="bi bi-graph-up fs-1 mb-3 opacity-50"></i>
                    <div>Tidak ada data</div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <a href="{{ route('pengajuan.index') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-arrow-left me-2"></i>Lihat Semua Pengajuan
        </a>
    </div>
</div>
@endsection
