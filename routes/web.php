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
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $role = auth()->user()->role;

    // Arahkan sesuai role agar tidak loop redirect
    if (in_array($role, ['rt', 'rw'], true)) {
        return redirect()->route('dashboard');
    }

    // untuk warga atau role lain, arahkan ke landing warga
    return redirect()->route('warga.landing');
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

// Pengajuan (RT/RW saja)
Route::middleware(['auth', 'role:rt,rw'])->group(function () {
    Route::get('/pengaturan', 'App\\Http\\Controllers\\ProfileController@edit')->name('profile.edit');
    Route::put('/pengaturan', 'App\\Http\\Controllers\\ProfileController@update')->name('profile.update');

<<<<<<< HEAD
    // Admin routes (jaga kompatibilitas route: admin.users.*)
    Route::middleware('role:admin,rt,rw')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::resource('users', 'App\\Http\\Controllers\\UserController');
        });
=======
    // Admin routes
    Route::middleware('role:admin,rt,rw')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', 'App\\Http\\Controllers\\UserController');
    });
>>>>>>> b4a8a90c6a85d98c0dcb599462d2c3f18547fb1a

    // RT/RW dashboards
    Route::get('/admin/rt', [PengajuanController::class, 'index'])->name('dashboard.rt');
    Route::get('/admin/rw', [PengajuanController::class, 'index'])->name('dashboard.rw');


    // Dashboard RT/RW
    Route::get('/dashboard', [PengajuanController::class, 'index'])->name('dashboard');
    Route::get('/rt-rw-dashboard', [PengajuanController::class, 'index'])->name('dashboard.rt-rw');

    // Approve/reject routes
    Route::post('/status/{pengajuan}/approve', [PengajuanController::class, 'approve'])->name('status.approve');
    Route::post('/status/{pengajuan}/reject', [PengajuanController::class, 'reject'])->name('status.reject');

    // Ajukan surat (kalau memang RT/RW yang mengajukan di web)
    Route::get('/ajukan', [PengajuanController::class, 'create'])->name('ajukan');
    Route::post('/ajukan', [PengajuanController::class, 'store'])->name('pengajuan.store');

    // Status surat
    Route::get('/status/{pengajuan}', [PengajuanController::class, 'show'])->name('status.show');
    // Riwayat
    Route::get('/riwayat', [PengajuanController::class, 'history'])->name('riwayat');

    // Edit/update status
    Route::get('/status/{pengajuan}/edit', [PengajuanController::class, 'edit'])->name('status.edit');
    Route::put('/status/{pengajuan}', [PengajuanController::class, 'update'])->name('status.update');

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

// Tambahkan atau pastikan rute ini ada
Route::get('/warga/dashboard', function () {
    return view('warga.dashboard_warga');
})->name('warga.dashboard');

Route::get('/warga/stats', [App\Http\Controllers\PengajuanController::class, 'getStats'])->name('warga.stats');
