@extends(in_array(auth()->user()->role, ['admin', 'rt', 'rw']) ? 'layouts.app' : 'layouts.warga_app')

@section('content')

@if(in_array(auth()->user()->role, ['admin', 'rt', 'rw']))
    <div class="container pb-20 px-4 mt-4">
        <div class="mb-6">
            <h2 class="h3 text-dark">Pengaturan Profil</h2>
            <p class="text-muted text-sm">Perbarui informasi akun {{ strtoupper(auth()->user()->role) }} Anda.</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="h5 mb-4">Data Diri</h3>

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">NIK</label>
                        <input type="text" name="nik" class="form-control" value="{{ old('nik', auth()->user()->nik ?? '') }}">
                    </div>

                    <div>
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', auth()->user()->alamat ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="h5 mb-4">Keamanan Akun</h3>
                    <div class="mb-3">
                        <label class="form-label">Password Saat Ini</label>
                        <input type="password" name="current_password" class="form-control" autocomplete="current-password">
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password" class="form-control" autocomplete="new-password">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 pt-2">
                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                <a href="{{ route(auth()->user()->role === 'rt' ? 'dashboard.rt' : (auth()->user()->role === 'rw' ? 'dashboard.rw' : 'dashboard')) }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
@else
    @php
        $labelCls = 'block text-[11px] font-bold uppercase tracking-wider text-gray-500';
        $inputBase = 'w-full rounded-2xl border px-4 py-3 text-sm text-gray-900 placeholder-gray-400 transition duration-200 hover:border-gray-300 focus:bg-white focus:outline-none focus:ring-2';
        $inputOk = $inputBase . ' border-gray-200 bg-gray-50/70 focus:border-emerald-500 focus:ring-emerald-500/25';
        $inputErr = $inputBase . ' border-red-300 bg-red-50/50 focus:border-red-500 focus:ring-red-500/25';
        $errText = 'mt-1.5 text-xs font-medium text-red-600';
    @endphp

    <div class="pb-10">

        {{-- HEADER PROFIL --}}
        <header class="fade-up relative overflow-hidden rounded-b-[2rem] bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-800 px-5 pt-6 pb-16 text-white">
            <div aria-hidden="true" class="pointer-events-none absolute -top-24 -right-20 h-64 w-64 rounded-full bg-white/10 blur-2xl"></div>
            <div aria-hidden="true" class="pointer-events-none absolute -bottom-28 -left-16 h-56 w-56 rounded-full bg-emerald-400/20 blur-2xl"></div>

            <div class="relative">
                <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-emerald-200">Akun Anda</p>
                <h1 class="mt-2 text-2xl font-bold leading-snug text-white">Pengaturan Profil</h1>
                <p class="mt-1.5 text-sm text-emerald-100/90">Perbarui informasi akun {{ strtoupper(auth()->user()->role) }} Anda.</p>
            </div>
        </header>

        <div class="-mt-10 space-y-5 px-5">

            {{-- PESAN SUKSES --}}
            @if (session('success'))
                <div class="fade-up flex items-start gap-3 rounded-3xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-white">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                    <span class="pt-0.5">{{ session('success') }}</span>
                </div>
            @endif

            {{-- PESAN ERROR --}}
            @if ($errors->any())
                <div class="fade-up rounded-3xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <p class="flex items-center gap-2 font-bold">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                        </svg>
                        Periksa kembali isian Anda
                    </p>
                    <ul class="mt-2 list-disc space-y-1.5 pl-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
                @csrf
                @method('PUT')

                {{-- DATA DIRI --}}
                <section class="fade-up rounded-3xl border border-gray-100 bg-white p-5 shadow-sm">
                    <div class="mb-5 flex items-center gap-3 border-b border-gray-100 pb-4">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </span>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Data Diri</h3>
                            <p class="text-[11px] text-gray-500">Biodata yang dicantumkan pada surat pengajuan Anda.</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label for="name" class="{{ $labelCls }}">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" class="{{ $errors->has('name') ? $inputErr : $inputOk }}" value="{{ old('name', auth()->user()->name) }}" required autocomplete="name">
                            @error('name')<p class="{{ $errText }}">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="email" class="{{ $labelCls }}">Email <span class="text-red-500">*</span></label>
                            <input type="email" id="email" name="email" class="{{ $errors->has('email') ? $inputErr : $inputOk }}" value="{{ old('email', auth()->user()->email) }}" required autocomplete="email">
                            @error('email')<p class="{{ $errText }}">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="nik" class="{{ $labelCls }}">NIK</label>
                            <input type="text" id="nik" name="nik" class="{{ $errors->has('nik') ? $inputErr : $inputOk }}" value="{{ old('nik', auth()->user()->nik ?? '') }}">
                            @error('nik')<p class="{{ $errText }}">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="alamat" class="{{ $labelCls }}">Alamat</label>
                            <textarea id="alamat" name="alamat" rows="4" class="{{ $errors->has('alamat') ? $inputErr : $inputOk }}" autocomplete="street-address">{{ old('alamat', auth()->user()->alamat ?? '') }}</textarea>
                            @error('alamat')<p class="{{ $errText }}">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </section>

                {{-- KEAMANAN AKUN --}}
                <section class="fade-up rounded-3xl border border-gray-100 bg-white p-5 shadow-sm" style="animation-delay:80ms">
                    <div class="mb-5 flex items-center gap-3 border-b border-gray-100 pb-4">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-violet-50 text-violet-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </span>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Keamanan Akun</h3>
                            <p class="text-[11px] text-gray-500">Kosongkan bila tidak ingin mengganti kata sandi.</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label for="current_password" class="{{ $labelCls }}">Password Saat Ini</label>
                            <input type="password" id="current_password" name="current_password" class="{{ $errors->has('current_password') ? $inputErr : $inputOk }}" autocomplete="current-password">
                            @error('current_password')<p class="{{ $errText }}">{{ $message }}</p>@enderror
                        </div>

                        <div class="space-y-4 border-t border-dashed border-gray-200 pt-4">
                            <div>
                                <label for="password" class="{{ $labelCls }}">Password Baru</label>
                                <input type="password" id="password" name="password" class="{{ $errors->has('password') ? $inputErr : $inputOk }}" autocomplete="new-password">
                                @error('password')<p class="{{ $errText }}">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="password_confirmation" class="{{ $labelCls }}">Konfirmasi Password Baru</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="{{ $errors->has('password_confirmation') ? $inputErr : $inputOk }}" autocomplete="new-password">
                                @error('password_confirmation')<p class="{{ $errText }}">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </section>

                {{-- TOMBOL AKSI --}}
                <div class="fade-up rounded-3xl border border-gray-100 bg-white p-5 shadow-sm" style="animation-delay:120ms">
                    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                        <a href="{{ route(auth()->user()->role === 'rt' ? 'dashboard.rt' : (auth()->user()->role === 'rw' ? 'dashboard.rw' : 'dashboard')) }}"
                           class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-gray-200 bg-white px-6 py-3.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50 hover:text-gray-900 active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500/70 focus-visible:ring-offset-2 sm:w-auto">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Batal
                        </a>
                        <button type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-emerald-600 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-emerald-600/20 transition hover:bg-emerald-700 active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500/70 focus-visible:ring-offset-2 sm:w-auto">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endif
@endsection