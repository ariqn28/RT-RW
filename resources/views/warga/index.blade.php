<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#2563eb">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="RT/RW Warga">
    <meta name="description" content="Layanan administrasi warga RT/RW berbasis PWA.">
    <title>Portal Warga RT/RW</title>
    <link rel="manifest" href="/pwa/manifest.json">
    <link rel="apple-touch-icon" href="/images/rt-rw-logo.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #0d6efd, #20c997); min-height: 100vh; }
    </style>
</head>
<body>
<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <section class="card border-0 shadow-lg">
                <div class="card-body p-4 p-md-5">
                    <h1 class="h3 fw-bold mb-3">Portal Warga RT/RW</h1>
                    <p class="text-muted mb-4">Kelola layanan administrasi warga dengan mudah dan aman.</p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('login') }}" class="btn btn-primary">Masuk ke website</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-secondary">Daftar akun</a>
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>
<script>
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/pwa/service-worker.js').catch(() => {});
    });
}
</script>
</body>
</html>