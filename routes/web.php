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

// Landing page
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    return redirect()->route('dashboard');
})->name('home');

// Public landing page
Route::get('/warga', function () {
    return view('warga.index');
})->name('warga.landing');

// ==========================================================================
// AUTHENTICATION
// ==========================================================================

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'store'])->name('register.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================================================
// PROTECTED ROUTES - All Authenticated Users
// ==========================================================================

Route::middleware(['auth'])->group(function () {

    // Main dashboard - shows different UI based on role
    Route::get('/dashboard', [PengajuanController::class, 'index'])->name('dashboard');

    // User profile
    Route::get('/pengaturan', 'App\\Http\\Controllers\\ProfileController@edit')->name('profile.edit');
    Route::put('/pengaturan', 'App\\Http\\Controllers\\ProfileController@update')->name('profile.update');

    // Public info
    Route::get('/iuran', [IuranController::class, 'index'])->name('iuran.index');
    Route::get('/informasi', [InformasiController::class, 'index'])->name('informasi.index');
    // Pengajuan flow for all authenticated users
    Route::get('/ajukan', [PengajuanController::class, 'create'])->name('ajukan');
    Route::post('/ajukan', [PengajuanController::class, 'store'])->name('pengajuan.store');
    Route::get('/status/{pengajuan}', [PengajuanController::class, 'show'])->name('status.show');
    Route::get('/riwayat', [PengajuanController::class, 'history'])->name('riwayat');
    Route::get('/status/{pengajuan}/edit', [PengajuanController::class, 'edit'])->name('status.edit');
    Route::put('/status/{pengajuan}', [PengajuanController::class, 'update'])->name('status.update');
    Route::delete('/status/{pengajuan}', [PengajuanController::class, 'destroy'])->name('status.destroy');
    // Legacy warga routes
    Route::get('/warga/dashboard', [PengajuanController::class, 'index'])->name('warga.dashboard');
    Route::get('/warga/stats', [PengajuanController::class, 'getStats'])->name('warga.stats');

});

// ==========================================================================
// ADMIN ROUTES - RT/RW Only
// ==========================================================================

Route::middleware(['auth', 'role:rt,rw'])->group(function () {

    // User management
    Route::middleware('role:admin,rt,rw')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::resource('users', 'App\\Http\\Controllers\\UserController');
        });

    // Admin aliases
    Route::get('/admin/rt', [PengajuanController::class, 'index'])->name('dashboard.rt');
    Route::get('/admin/rw', [PengajuanController::class, 'index'])->name('dashboard.rw');
    Route::get('/rt-rw-dashboard', [PengajuanController::class, 'index'])->name('dashboard.rt-rw');

    // Admin dashboard (role: admin)
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [PengajuanController::class, 'index'])->name('admin.dashboard');
    });

    // Pengajuan management
    Route::post('/status/{pengajuan}/approve', [PengajuanController::class, 'approve'])->name('status.approve');
    Route::post('/status/{pengajuan}/reject', [PengajuanController::class, 'reject'])->name('status.reject');

});