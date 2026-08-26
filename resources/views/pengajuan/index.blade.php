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
            <div class="text-muted">Surat sudah selesai</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-panel p-4 h-100">
            <div class="text-success mb-2">Ditolak</div>
            <div class="fs-2 fw-bold">{{ $counts['rejected'] }}</div>
            <div class="text-muted">Pengajuan yang gagal</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-panel p-4 h-100">
            <div class="text-success mb-2">Total Pengajuan</div>
            <div class="fs-2 fw-bold">{{ $counts['total'] }}</div>
            <div class="text-muted">Seluruh pengajuan surat</div>
        </div>
    </div>
</div>

@if(auth()->user()->role === 'warga')
<div class="text-center py-5">
    <i class="bi bi-person-check display-1 text-success opacity-50 mb-4 d-block"></i>
    <h2>Selamat datang, {{ auth()->user()->name }}</h2>
    <p class="lead text-muted mb-4">Status pengajuan Anda:</p>
    <div class="row g-4 justify-content-center">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-4">
                <div class="text-primary mb-2 fs-1"><i class="bi bi-clock"></i></div>
                <div class="fs-4 fw-bold">{{ $counts['pending'] }}</div>
                <small class="text-muted">Menunggu</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-4">
                <div class="text-success mb-2 fs-1"><i class="bi bi-check-circle"></i></div>
                <div class="fs-4 fw-bold text-success">{{ $counts['approved'] }}</div>
                <small class="text-muted">Disetujui</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-4">
                <div class="text-danger mb-2 fs-1"><i class="bi bi-x-circle"></i></div>
                <div class="fs-4 fw-bold text-danger">{{ $counts['rejected'] }}</div>
                <small class="text-muted">Ditolak</small>
            </div>
        </div>
    </div>
    <div class="mt-5">
        <a href="{{ route('ajukan') }}" class="btn btn-success btn-lg px-5 me-3">
            <i class="bi bi-plus-circle"></i> Ajukan Surat Baru
        </a>
        <a href="/riwayat" class="btn btn-outline-primary btn-lg px-5">
            <i class="bi bi-clock-history"></i> Riwayat Pengajuan
        </a>
    </div>
</div>
@elseif(in_array(auth()->user()->role, ['rt', 'rw']))
@elseif(in_array(auth()->user()->role, ['rt', 'rw']))
{{-- RT/RW Admin Panel --}}
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card card-panel p-4">
            <h6 class="text-success mb-2">Rekap Jenis Surat</h6>
            @if(isset($jenisRekap) && $jenisRekap->count())
            <ul class="list-unstyled mb-0">
                @foreach($jenisRekap as $item)
                <li class="d-flex justify-content-between">
                    <span>{{ $item->jenis_surat }}</span>
                    <span class="fw-bold">{{ $item->total }}</span>
                </li>
                @endforeach
            </ul>
            @else
            <p class="text-muted">Tidak ada data.</p>
            @endif
        </div>
    </div>
    @if(auth()->user()->role === 'rw' && isset($rtPending))
    <div class="col-md-6">
        <div class="card card-panel p-4">
            <h6 class="text-warning mb-2">Pending dari RT ({{ $counts['rt_pending'] ?? 0 }})</h6>
            @if($rtPending->count())
            <ul class="list-unstyled mb-0">
                @foreach($rtPending as $p)
                <li class="small">
                    <a href="{{ route('status.show', $p->id) }}" class="text-decoration-none">{{ $p->nama }} - {{ $p->jenis_surat }}</a>
                </li>
                @endforeach
            </ul>
            @else
            <p class="text-muted small">Semua selesai.</p>
            @endif
        </div>
    </div>
    @endif
</div>

<div class="d-flex gap-2 mb-4 flex-wrap">
    <select id="statusFilter" class="form-select" style="max-width: 200px;">
        <option value="">Semua Status</option>
        <option value="baru">Baru</option>
        <option value="disetujui_rt">Disetujui RT</option>
        <option value="diterima">Diterima</option>
        <option value="ditolak">Ditolak</option>
    </select>
    <div class="btn-group" role="group">
        <a href="/riwayat" class="btn btn-outline-secondary">Riwayat</a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Manajemen User</a>
    </div>
</div>
@endif

<div class="card card-panel shadow-sm">
    <div class="card-body p-0">
        <div class="card-header pb-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-1">Status Pengajuan Terbaru</h5>
                    <p class="text-muted mb-0 small">Periksa dan kelola pengajuan langsung di sini.</p>
                </div>
                <span class="badge bg-success">{{ isset($pengajuan) && method_exists($pengajuan, 'total') ? $pengajuan->total() : ($counts['total'] ?? 0) }} baris</span>
            </div>
        </div>

        @if($pengajuan->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width: 12%">Jenis</th>
                        <th style="width: 12%">Warga</th>
                        <th style="width: 10%">NIK</th>
                        <th style="width: 13%">Status</th>
                        <th style="width: 15%">Alasan</th>
                        <th style="width: 12%">Dibuat</th>
                        <th style="width: 12%">RT</th>
                        <th style="width: 14%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengajuan as $p)
                    <tr>
                        <td><span class="badge bg-light text-dark small">{{ Str::limit($p->jenis_surat, 15) }}</span></td>
                        <td>{{ $p->nama }}</td>
                        <td>{{ Str::limit($p->nik, 16) }}</td>
                        <td>
                            @php $statusColor = $p->status == 'diterima' ? 'success' : ($p->status == 'ditolak' ? 'danger' : ($p->status == 'disetujui_rt' ? 'warning' : 'secondary')); @endphp
                            <span class="badge bg-{{ $statusColor }}">
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                        <td>{{ \Illuminate\Support\Str::limit($p->alasan, 30) }}</td>
                        <td>{{ $p->created_at->format('d/m') }}</td>
                        <td>
                            @if($p->statusHistories->where('changed_by.role', 'rt')->first())
                            {{ optional($p->statusHistories->where('changed_by.role', 'rt')->first()->changedBy)->name ?? '-' }}
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-gear"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('status.show', $p->id) }}"><i class="bi bi-eye"></i> Detail</a></li>
                                    @if(in_array(auth()->user()->role, ['rt', 'rw']))
                                    <li><a class="dropdown-item" href="{{ route('status.edit', $p->id) }}"><i class="bi bi-pencil"></i> Edit</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    @if(auth()->user()->role === 'rt' && $p->status === 'baru')
                                    <li><form method="POST" action="{{ route('status.approve', $p->id) }}" class="dropdown-item p-0">
                                        @csrf <button class="dropdown-item text-success"><i class="bi bi-check-lg"></i> Setujui RT</button></form>
                                    </li>
                                    @endif
                                    @if(auth()->user()->role === 'rw' && $p->status === 'disetujui_rt')
                                    <li><form method="POST" action="{{ route('status.approve', $p->id) }}" class="dropdown-item p-0">
                                        @csrf <button class="dropdown-item text-success"><i class="bi bi-check-lg-circle"></i> Setujui RW (Final)</button></form>
                                    </li>
                                    @endif
                                    <li><form method="POST" action="{{ route('status.reject', $p->id) }}" class="dropdown-item p-0">
                                        @csrf <button class="dropdown-item text-danger"><i class="bi bi-x-lg"></i> Tolak</button></form>
                                    </li>
                                    @endif
                                </ul>
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
            <h5>Belum ada pengajuan</h5>
            <p class="mb-0">Mulai ajukan surat untuk melihat daftar di sini.</p>
        </div>
        @endif

        <div class="card-footer bg-transparent pt-3 pb-3 border-top">
@if(isset($pengajuan) && method_exists($pengajuan, 'hasPages') && $pengajuan->hasPages())
            <div class="d-flex justify-content-center">
{{ $pengajuan->links('pagination::simple-bootstrap-5') ?? '' }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
