<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login RT/RW</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0f9d58 0%, #1fc16f 100%);
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            border-radius: 1.5rem;
            box-shadow: 0 24px 60px rgba(15, 95, 39, 0.32);
        }
        .login-card .card-body {
            padding: 2.5rem;
        }
        .login-title {
            color: #ffffff;
            font-weight: 700;
        }
        .form-control {
            border-radius: 0.85rem;
        }
        .btn-submit {
            border-radius: 0.85rem;
            padding: 0.85rem 1.3rem;
        }
    </style>
</head>
<body>
    <div class="card login-card overflow-hidden">
        <div class="row g-0">
            <div class="col-12">
                <div class="card-body bg-success text-white">
                    <h2 class="login-title mb-3">Masuk ke RT/RW</h2>
                    <p class="mb-0 text-white-75">Masukkan email dan kata sandi Anda untuk mengakses dashboard.</p>
                </div>
            </div>
            <div class="col-12 p-4 bg-white">
                @if($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
                @endif

                <form action="{{ url('/login') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-control" required>
                            <option value="warga" {{ old('role') == 'warga' ? 'selected' : '' }}>Warga</option>
                            <option value="rt" {{ old('role') == 'rt' ? 'selected' : '' }}>RT</option>
                            <option value="rw" {{ old('role') == 'rw' ? 'selected' : '' }}>RW</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success btn-submit w-100">Submit</button>
                </form>

                <div class="mt-4 text-center">
                    <span class="text-muted">Belum punya akun?</span>
                    <a href="{{ route('register') }}" class="fw-semibold text-success">Daftar sekarang</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

