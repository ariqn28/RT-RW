<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\IuranController;
use App\Http\Controllers\InformasiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Public warga landing page
Route::get('/warga', function () {
    return view('warga.index');
})->name('warga.landing');

// Auth
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'store'])->name('register.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Pengajuan (wajib login)
Route::middleware('auth')->group(function () {
        Route::get('/pengaturan', 'App\\Http\\Controllers\\ProfileController@edit')->name('profile.edit');
        Route::put('/pengaturan', 'App\\Http\\Controllers\\ProfileController@update')->name('profile.update');

    // Admin routes
    Route::middleware('role:admin,rt,rw')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', 'App\\Http\\Controllers\\UserController');
    });

    // RT/RW dashboards (allow rt,rw access)
    Route::middleware('role:rt,rw')->group(function () {
        Route::get('/admin/rt', [PengajuanController::class, 'index'])->name('dashboard.rt');
        Route::get('/admin/rw', [PengajuanController::class, 'index'])->name('dashboard.rw');
    });

    // Dashboard utama (semua roles)
    Route::get('/dashboard', [PengajuanController::class, 'index'])->name('dashboard');
    // RT/RW special dashboard
    Route::middleware('role:rt,rw')->get('/rt-rw-dashboard', [PengajuanController::class, 'index'])->name('dashboard.rt-rw');

    // Approve/reject routes
    Route::middleware('role:rt,rw')->group(function () {
        Route::post('/status/{pengajuan}/approve', [PengajuanController::class, 'approve'])->name('status.approve');
        Route::post('/status/{pengajuan}/reject', [PengajuanController::class, 'reject'])->name('status.reject');
    });

    // Ajukan surat
    Route::get('/ajukan', [PengajuanController::class, 'create'])->name('ajukan');
    Route::post('/ajukan', [PengajuanController::class, 'store'])->name('pengajuan.store');

    // Status surat
    Route::get('/status/{pengajuan}', [PengajuanController::class, 'show'])->name('status.show');
    // Riwayat
    Route::get('/riwayat', [PengajuanController::class, 'history'])->name('riwayat');
    Route::middleware('role:rt,rw')->group(function () {
        Route::get('/status/{pengajuan}/edit', [PengajuanController::class, 'edit'])->name('status.edit');
        Route::put('/status/{pengajuan}', [PengajuanController::class, 'update'])->name('status.update');
    });

    // Hapus pengajuan
    Route::delete('/status/{pengajuan}', [PengajuanController::class, 'destroy'])->name('status.destroy');
});


// Pastikan route ini ada di dalam group 'auth'
Route::middleware(['auth'])->group(function () {
    Route::get('/iuran', [IuranController::class, 'index'])->name('iuran.index');
});
   Route::middleware(['auth'])->group(function () {
    // ... rute lainnya
    Route::get('/informasi', [InformasiController::class, 'index'])->name('informasi.index');
});

