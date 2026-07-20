<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#2563eb">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="RT/RW Warga">
    <meta name="description" content="Aplikasi pengajuan surat warga berbasis PWA RT/RW.">
    <title>Dashboard RT/RW</title>
    <link rel="manifest" href="/pwa/manifest.json">
    <link rel="apple-touch-icon" href="/images/rt-rw-logo.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #edf7ed;
        }
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: #1db954;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,.95);
            font-weight: 600;
            padding: 0.95rem 1rem;
            border-radius: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.96rem;
        }
        .sidebar .nav-link .bi {
            font-size: 1.25rem;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(0,0,0,.12);
            border-radius: 0.75rem;
        }
        .sidebar .nav-link.disabled {
            opacity: 0.75;
            cursor: not-allowed;
        }
        .sidebar-logo {
            width: 72px;
            height: auto;
            display: block;
        }
        .app-header {
            background: #ffffff;
            border-bottom: 1px solid #e8ece7;
            min-height: 80px;
        }
        .card-panel {
            border-radius: 1.2rem;
            box-shadow: 0 20px 50px rgba(15, 90, 36, 0.08);
        }
        .button-panel .btn {
            min-width: 180px;
        }
        .progress-step {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
        }
        .progress-step .step {
            flex: 1;
            padding: 1rem;
            border-radius: 1rem;
            background: #f2f7f1;
            text-align: center;
            color: #5c6b5b;
            font-weight: 600;
        }
        .progress-step .step.active {
            background: #1f8b3a;
            color: #fff;
        }
        .progress-step .step.complete {
            background: #2fa74d;
            color: #fff;
        }
        .progress-step .step.rejected {
            background: #dc3545;
            color: #fff;
        }
        .timeline-line {
            height: 6px;
            background: #cfe8cc;
            border-radius: 999px;
            margin: 1rem 0;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <aside class="sidebar p-4 d-flex flex-column justify-content-between">
        <div>
            <div class="mb-4 d-flex align-items-center gap-3">
                <img src="{{ asset('images/rt-rw-logo.svg') }}" alt="RT/RW Logo" class="sidebar-logo">
                <div>
                    <div class="text-white fs-4 fw-bold">RT/RW</div>
                    <div class="text-white-50">Panel pengelolaan</div>
                </div>
            </div>
            <ul class="nav flex-column gap-2">
                <li class="nav-item"><a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="bi bi-house"></i>Dashboard</a></li>
                @if(auth()->user()->role === 'warga')
                <li class="nav-item"><a href="{{ route('ajukan') }}" class="nav-link {{ request()->routeIs('ajukan') ? 'active' : '' }}"><i class="bi bi-pencil"></i>Ajukan Surat</a></li>
                @endif
                <li class="nav-item"><a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="bi bi-bar-chart-line"></i>Status</a></li>
                <li class="nav-item"><a href="{{ route('riwayat') }}" class="nav-link {{ request()->routeIs('riwayat') ? 'active' : '' }}"><i class="bi bi-clock-history"></i>Riwayat</a></li>
                @if(auth()->user()->role === 'admin')
                <li class="nav-item"><a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><i class="bi bi-person"></i>User</a></li>
                @endif
                <li class="nav-item"><a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}"><i class="bi bi-gear"></i>Pengaturan</a></li>
            </ul>
        </div>
        @auth
        <div class="mt-4">
            <div class="text-white mb-3">{{ auth()->user()->name }}</div>
            <div class="text-white-50 mb-3">Akun {{ strtoupper(auth()->user()->role ?? 'RW') }}</div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light w-100">Keluar</button>
            </form>
        </div>
        @endauth
    </aside>

    <div class="flex-grow-1">
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h1">RT/RW System</span>

                <div class="d-flex">
                    @auth
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-2"></i>{{ auth()->user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><span class="dropdown-item-text small text-muted">{{ auth()->user()->email }}</span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="bi bi-house-door me-2"></i>Dashboard</a></li>
                            @if(auth()->user()->role === 'warga')
                            <li><a class="dropdown-item" href="{{ route('ajukan') }}"><i class="bi bi-file-earmark-plus me-2"></i>Ajukan Surat</a></li>
                            @endif
                            @if(in_array(auth()->user()->role, ['rt', 'rw']))
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="bi bi-list-check me-2"></i>Daftar Pengajuan</a></li>
                            @endif
                            @if(auth()->user()->role === 'admin')
                            <li><a class="dropdown-item" href="{{ route('admin.users.index') }}"><i class="bi bi-people me-2"></i>Manajemen User</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>

                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @endauth
                </div>
            </div>
        </nav>

        <header class="app-header d-flex align-items-center justify-content-between px-4">
            <div>
                <h1 class="h4 mb-1">Dashboard {{ strtoupper(auth()->user()->role) }}</h1>
                <p class="text-muted mb-0">
                    @if(auth()->user()->role === 'warga')
                        Ajukan surat tanpa melihat data pengajuan seluruh pengguna.
                    @elseif(auth()->user()->role === 'admin')
                        Lihat statistik keseluruhan, sementara RT/RW hanya menyetujui pengajuan.
                    @else
                        Kelola dan verifikasi pengajuan surat dari warga.
                    @endif
                </p>
            </div>
        </header>

        <main class="p-4">
            @if(session('success'))
                <div class="alert alert-success rounded-4 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
<script>
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/pwa/service-worker.js').catch(() => {});
    });
}
</script>
</body>
</html>
