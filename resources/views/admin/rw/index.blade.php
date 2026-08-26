@extends('layouts.app')

@section('title', 'Admin RW - Validasi Final')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Admin RW</h1>
        <p class="text-muted mb-0">Validasi final pengajuan RT & issue surat</p>
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
                <i class="bi bi-arrow-right-circle text-warning fs-1 mb-3"></i>
                <h3 class="mb-1">{{ $counts['rt_pending'] ?? 0 }}</h3>
                <p class="text-muted mb-0">Pending RT</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card h-100 shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-check-circle-fill text-success fs-1 mb-3"></i>
                <h3 class="mb-1">{{ $counts['approved'] ?? 0 }}</h3>
                <p class="text-muted mb-0">Final Approved</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card h-100 shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-x-circle text-danger fs-1 mb-3"></i>
                <h3 class="mb-1">{{ $counts['rejected'] ?? 0 }}</h3>
                <p class="text-muted mb-0">Final Rejected</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card h-100 shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-file-earmark-text text-primary fs-1 mb-3"></i>
<h3 class="mb-1">{{ number_format(App\Models\Pengajuan::where('status', 'diterima')->count()) }}</h3>
                <p class="text-muted mb-0">Surat Terbit</p>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-lg border-0 mb-4">
    <div class="card-header bg-white border-0 pb-0">
        <h5 class="mb-3"><i class="bi bi-arrow-right-circle text-warning me-2"></i>Pending dari RT</h5>
        <div class="row g-2 mb-3">
            <div class="col-auto">
                <select class="form-select form-select-sm" id="statusFilter">
                    <option value="">Semua RT Pending</option>
                    <option value="disetujui_rt">RT Approved</option>
                </select>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-warning">
                    <tr>
                        <th>#</th>
                        <th>Warga</th>
                        <th>RT</th>
                        <th>Jenis Surat</th>
                        <th>Status RT</th>
                        <th>Tanggal RT</th>
                        <th>Aksi RW</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rtPending as $index => $p)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="fw-bold">{{ $p->nama }}</div>
                            <small class="text-muted">{{ $p->nik }}</small>
                        </td>
                        <td>
                            <span class="badge bg-success">{{ $p->statusHistories->where('status', 'disetujui_rt')->first()->changedBy->name ?? 'RT' }}</span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark px-2 py-1">{{ Str::limit($p->jenis_surat, 18) }}</span>
                            @if($p->file_path)
                            <small class="d-block text-success">
                                <i class="bi bi-paperclip"></i> Lampiran OK
                            </small>
                            @endif
                        </td>
                        <td><span class="badge bg-warning text-dark">RT OK</span></td>
                        <td><small class="text-muted">{{ $p->statusHistories->where('status', 'disetujui_rt')->first()->created_at->format('d M Y H:i') }}</small></td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('status.show', $p) }}" class="btn btn-outline-info">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                                <form method="POST" action="{{ route('status.approve', $p) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success" title="Final RW">
                                        <i class="bi bi-check2-all"></i> RW OK
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('status.reject', $p) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Final tolak?')" title="RW Reject">
                                        <i class="bi bi-x-circle"></i> Tolak
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @if($rtPending->isEmpty())
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-check-circle-fill fs-1 mb-4 text-success opacity-75"></i>
                            <h5>Semua pengajuan RT sudah divalidasi</h5>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-bar-chart-line text-primary me-2"></i>Statistik Bulan Ini</h6>
            </div>
            <div class="card-body">
                <canvas id="statsChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-people text-info me-2"></i>Warga Aktif</h6>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span>Total Warga</span>
<span class="badge bg-primary">{{ number_format(App\Models\User::where('role', 'warga')->count()) }}</span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span>Ajukan bulan ini</span>
<span class="badge bg-success">{{ number_format(App\Models\Pengajuan::whereMonth('created_at', now()->month)->count()) }}</span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span>Approval rate RW</span>
                        <span class="badge bg-info">{{ round(($counts['approved'] ?? 0) / max(1, $counts['total'] ?? 1) * 100, 0) }}%</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('statsChart');
new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Pending RT', 'Approved RW', 'Rejected'],
        datasets: [{
            data: [{{ $counts['rt_pending'] ?? 0 }}, {{ $counts['approved'] ?? 0 }}, {{ $counts['rejected'] ?? 0 }}],
            backgroundColor: ['#ffc107', '#28a745', '#dc3545']
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' }}
    }
});
</script>
@endsection
