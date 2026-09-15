<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register RT/RW</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --green: #1da85c;
            --green-dark: #0f5c32;
            --heading: #152b20;
            --muted: #6b7a72;
            --border: #e4ece8;
        }
        * {
            box-sizing: border-box;
        }
        html,
        body {
            overflow-x: hidden;
        }
        body {
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 16px;
            position: relative;
            background:
                radial-gradient(600px 500px at 18% 18%, rgba(29,168,92,.18), transparent 60%),
                radial-gradient(500px 400px at 85% 78%, rgba(29,185,84,.14), transparent 55%),
                radial-gradient(800px 600px at 50% 45%, rgba(15,92,50,.10), transparent 50%),
                linear-gradient(170deg, #142a1e 0%, #1a3828 40%, #1c4030 65%, #152e22 100%);
        }
        body::before,
        body::after {
            content: '';
            position: fixed;
            z-index: 0;
            border-radius: 50%;
            filter: blur(80px);
            animation: drift 18s ease-in-out infinite alternate;
            pointer-events: none;
        }
        body::before {
            width: 420px;
            height: 420px;
            background: rgba(29,168,92,.18);
            top: -120px;
            left: -90px;
        }
        body::after {
            width: 360px;
            height: 360px;
            background: rgba(29,185,84,.14);
            bottom: -100px;
            right: -70px;
            animation-delay: -8s;
            animation-duration: 22s;
        }
        .auth-card {
            width: 100%;
            max-width: 1000px;
            background: #fff;
            border-radius: 22px;
            box-shadow: 0 32px 80px rgba(0,0,0,.28), 0 8px 24px rgba(0,0,0,.16);
            overflow: hidden;
            display: grid;
            grid-template-columns: 40% 1fr;
            position: relative;
            z-index: 1;
        }
        .auth-panel {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 18px;
            padding: clamp(40px, 6vw, 64px) 32px;
            text-align: center;
            color: #fff;
            background: linear-gradient(155deg, #0d5a30 0%, #128a4a 45%, #1cb964 100%);
            overflow: hidden;
        }
        .auth-panel::before {
            content: '';
            position: absolute;
            width: 340px;
            height: 340px;
            border-radius: 50%;
            background: rgba(255,255,255,.06);
            top: -120px;
            right: -110px;
        }
        .auth-panel::after {
            content: '';
            position: absolute;
            width: 320px;
            height: 320px;
            border-radius: 50%;
            background: rgba(255,255,255,.045);
            bottom: -140px;
            left: -110px;
        }
        .auth-panel .ring {
            position: absolute;
            width: 190px;
            height: 190px;
            border-radius: 50%;
            border: 1.5px dashed rgba(255,255,255,.22);
            bottom: -70px;
            right: -50px;
        }
        .auth-panel .panel-logo {
            width: 104px;
            height: auto;
            filter: brightness(0) invert(1);
            z-index: 1;
        }
        .auth-panel h1 {
            font-size: 1.65rem;
            font-weight: 800;
            letter-spacing: .5px;
            margin: 0;
            z-index: 1;
        }
        .auth-form {
            padding: 46px 48px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .auth-form h2 {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--heading);
            margin: 0 0 6px;
        }
        .label-auth {
            font-size: .88rem;
            font-weight: 600;
            color: #33503f;
            margin-bottom: 7px;
        }
        .input-group-auth {
            border: 1.5px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
            transition: border-color .2s ease, box-shadow .2s ease;
        }
        .input-group-auth .input-group-text {
            background: #f4f8f6;
            border: 0;
            color: var(--green);
            padding-inline: 14px;
            font-size: 1.05rem;
        }
        .input-group-auth .form-control,
        .input-group-auth .form-select {
            border: 0;
            background: #fff;
            padding: 13px 16px;
            font-size: .95rem;
            color: var(--heading);
            box-shadow: none;
        }
        .input-group-auth .form-control::placeholder {
            color: #9fb0a8;
        }
        .input-group-auth:focus-within {
            border-color: var(--green);
            box-shadow: 0 0 0 4px rgba(29,168,92,.13);
        }
        .btn-submit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 14px 18px;
            border: 0;
            border-radius: 12px;
            background: linear-gradient(135deg, #128a4a, #1cb964);
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: transform .15s ease, box-shadow .2s ease, filter .2s ease;
        }
        .btn-submit:hover {
            box-shadow: 0 12px 24px rgba(20,138,74,.32);
            filter: brightness(1.04);
            transform: translateY(-1px);
        }
        .btn-submit:active {
            transform: translateY(0);
            box-shadow: none;
        }
        .btn-submit:disabled {
            opacity: .65;
            cursor: not-allowed;
            filter: none;
            transform: none;
        }
        .auth-footer {
            margin-top: 26px;
            text-align: center;
            font-size: .92rem;
            color: var(--muted);
        }
        .auth-footer a {
            color: var(--green);
            font-weight: 700;
            text-decoration: none;
        }
        .auth-footer a:hover {
            text-decoration: underline;
        }
        @keyframes drift {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(-22px, 16px) scale(1.05); }
            100% { transform: translate(16px, -12px) scale(.95); }
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        @keyframes cardIn {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation: none !important;
                transition: none !important;
            }
        }
        @media (max-width: 991.98px) {
            .auth-form {
                padding: 40px 36px;
            }
        }
        @media (max-width: 767.98px) {
            body {
                padding: 18px 14px 30px;
                align-items: flex-start;
            }
            body::before {
                width: 340px;
                height: 340px;
                background: rgba(29,168,92,.22);
                top: -110px;
                right: -90px;
                left: auto;
                bottom: auto;
                animation: drift 14s ease-in-out infinite alternate;
            }
            body::after {
                width: 280px;
                height: 280px;
                background: rgba(29,185,84,.18);
                top: auto;
                right: auto;
                bottom: -90px;
                left: -80px;
                animation: drift 18s ease-in-out infinite alternate-reverse;
                animation-delay: -8s;
            }
            .auth-card {
                display: flex;
                flex-direction: column;
                border-radius: 26px;
                box-shadow: 0 18px 46px rgba(16,75,43,.18);
                animation: cardIn .5s ease-out;
            }
            .auth-panel {
                flex-direction: column;
                justify-content: center;
                gap: 14px;
                padding: 46px 24px 40px;
                text-align: center;
                border-bottom-left-radius: 30px;
                border-bottom-right-radius: 30px;
            }
            .auth-panel::before {
                display: block;
                width: 280px;
                height: 280px;
                top: -110px;
                right: -80px;
                animation: drift 14s ease-in-out infinite alternate;
            }
            .auth-panel::after {
                display: block;
                width: 260px;
                height: 260px;
                bottom: -120px;
                left: -90px;
                animation: drift 18s ease-in-out infinite alternate-reverse;
            }
            .auth-panel .ring {
                display: block;
                animation: spin 24s linear infinite;
            }
            .auth-panel .panel-logo {
                width: 66px;
            }
            .auth-panel h1 {
                font-size: 1.3rem;
                letter-spacing: 1px;
            }
            .auth-form {
                padding: 30px 24px 28px;
            }
            .auth-form h2 {
                font-size: 1.45rem;
            }
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-panel">
            <span class="ring"></span>
            <img src="{{ asset('images/rt-rw-logo.svg') }}" alt="RT/RW Logo" class="panel-logo">
            <h1>RT/RW System</h1>
        </div>
        <div class="auth-form">
            <h2>Daftar Akun</h2>

            @if($errors->any())
            <div class="alert alert-danger rounded-3 mb-3">
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
                    <label class="form-label label-auth" for="regName">Nama Lengkap</label>
                    <div class="input-group input-group-auth">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" id="regName" placeholder="Nama Lengkap" required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label label-auth" for="regEmail">Email</label>
                    <div class="input-group input-group-auth">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" id="regEmail" placeholder="name@example.com" required>
                    </div>
<<<<<<< HEAD
                    <div class="alert alert-light border small text-muted">
                        Pendaftaran ini membuat akun warga. Akun RT, RW, dan admin dibuat melalui Manajemen User.
=======
                </div>

                <div class="mb-3">
                    <label class="form-label label-auth" for="regRole">Role</label>
                    <div class="input-group input-group-auth">
                        <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                        <select name="role" class="form-select" id="regRole" required>
                            <option value="warga" {{ old('role') == 'warga' ? 'selected' : '' }}>Warga</option>
                            <option value="rt" {{ old('role') == 'rt' ? 'selected' : '' }}>RT</option>
                            <option value="rw" {{ old('role') == 'rw' ? 'selected' : '' }}>RW</option>
                        </select>
>>>>>>> origin/main
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label label-auth" for="regPassword">Password</label>
                    <div class="input-group input-group-auth">
                        <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                        <input type="password" name="password" class="form-control" id="regPassword" placeholder="Kata sandi" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label label-auth" for="regPasswordConfirm">Konfirmasi Password</label>
                    <div class="input-group input-group-auth">
                        <span class="input-group-text"><i class="bi bi-shield-check"></i></span>
                        <input type="password" name="password_confirmation" class="form-control" id="regPasswordConfirm" placeholder="Konfirmasi kata sandi" required>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Daftar</button>
            </form>

            <div class="auth-footer">
                Sudah punya akun?
                <a href="{{ route('login') }}">Masuk</a>
            </div>
        </div>
    </div>
</body>
</html>