<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register RT/RW</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0f9d58 0%, #1fc16f 100%);
        }
        .auth-card {
            width: 100%;
            max-width: 500px;
            border-radius: 1.5rem;
            box-shadow: 0 24px 60px rgba(15, 95, 39, 0.32);
        }
        .auth-card .card-body {
            padding: 2.5rem;
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
    <div class="card auth-card overflow-hidden">
        <div class="row g-0">
            <div class="col-12">
                <div class="card-body bg-success text-white">
                    <h2 class="mb-3">Daftar Akun</h2>
                    <p class="mb-0 text-white-75">Buat akun untuk mengakses sistem pengajuan surat.</p>
                </div>
            </div>
            <div class="col-12 p-4 bg-white">
                @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('register.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-control" required>
                            <option value="warga" {{ old('role') == 'warga' ? 'selected' : '' }}>Warga</option>
                            <option value="rt" {{ old('role') == 'rt' ? 'selected' : '' }}>RT</option>
                            <option value="rw" {{ old('role') == 'rw' ? 'selected' : '' }}>RW</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-submit w-100">Daftar</button>
                </form>

                <div class="mt-4 text-center">
                    <span class="text-muted">Sudah punya akun?</span>
                    <a href="{{ route('login') }}" class="fw-semibold text-success">Masuk</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
