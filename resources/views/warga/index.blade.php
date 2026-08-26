<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#2563eb">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="RT/RW Warga">
    <meta name="description" content="Aplikasi pengajuan surat warga berbasis PWA RT/RW.">
    <title>Warga RT/RW</title>
    <link rel="manifest" href="/pwa/manifest.json">
    <link rel="apple-touch-icon" href="/images/rt-rw-logo.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #0d6efd, #20c997); color: #fff; min-height: 100vh; }
        .card { color: #212529; }
        .code { background: #f8f9fa; padding: .75rem; border-radius: .5rem; font-size: .95rem; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4 p-md-5">
                    <h1 class="h3 fw-bold mb-3">Portal Warga RT/RW</h1>
                    
                    <p class="text-muted mb-4">Akses login web dan integrasi mobile untuk akun warga.</p>

                    <div class="mb-4">
                        <h2 class="h5">Akun demo</h2>
                        <div class="code">
                            Email: warga@gmail.com<br>
                            Password: 12345678
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex">
                        <a href="/login" class="btn btn-primary">Masuk ke web</a>
                        <a href="/api/mobile/login" class="btn btn-outline-secondary" onclick="event.preventDefault(); alert('Gunakan method POST dengan body JSON. Contoh: {\"email\":\"warga@gmail.com\",\"password\":\"12345678\",\"device_name\":\"Android App\"}');">Coba endpoint mobile</a>
                    </div>

                    <hr class="my-4">

                    <h2 class="h5">Endpoint mobile</h2>
                    <p class="small text-muted mb-2">Gunakan request berikut dari aplikasi mobile:</p>
                    <pre class="code">POST /api/mobile/login
{
  "email": "warga@gmail.com",
  "password": "12345678",
  "device_name": "Android App"
}</pre>
                </div>
            </div>
        </div>
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
