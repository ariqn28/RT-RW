<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\IuranController;
use App\Http\Controllers\InformasiController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\HealthController;

Route::get('/health', HealthController::class)->name('health');

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

    return match ($role) {
        'warga' => redirect()->route('warga.dashboard'),
        'rt' => redirect()->route('dashboard.rt'),
        'rw' => redirect()->route('dashboard.rw'),
        'admin' => redirect()->route('dashboard'),
        default => redirect()->route('login'),
    };
});


// Public warga PWA landing page.
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
Route::middleware(['auth'])->group(function () {
    Route::get('/pengaturan', 'App\\Http\\Controllers\\ProfileController@edit')->name('profile.edit');
    Route::put('/pengaturan', 'App\\Http\\Controllers\\ProfileController@update')->name('profile.update');

    // Admin routes (jaga kompatibilitas route: admin.users.*)
    Route::middleware('role:admin,rt,rw')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::resource('users', 'App\\Http\\Controllers\\UserController');
        });

    Route::middleware('role:admin,rt,rw')->prefix('admin/konten')->name('admin.content.')->group(function () {
        Route::get('/', [ContentController::class, 'index'])->name('index');
        Route::post('/berita', [ContentController::class, 'storeAnnouncement'])->name('announcements.store');
        Route::delete('/berita/{announcement}', [ContentController::class, 'destroyAnnouncement'])->name('announcements.destroy');
        Route::post('/iuran', [ContentController::class, 'storeDue'])->name('dues.store');
        Route::delete('/iuran/{due}', [ContentController::class, 'destroyDue'])->name('dues.destroy');
        Route::put('/kontak', [ContentController::class, 'updateContact'])->name('contact.update');
    });

    // RT/RW dashboards
    Route::get('/admin/rt', [PengajuanController::class, 'index'])->middleware('role:rt')->name('dashboard.rt');
    Route::get('/admin/rw', [PengajuanController::class, 'index'])->middleware('role:rw')->name('dashboard.rw');


    // Dashboard RT/RW
    Route::get('/dashboard', [PengajuanController::class, 'index'])->name('dashboard');
    Route::get('/rt-rw-dashboard', [PengajuanController::class, 'index'])->name('dashboard.rt-rw');

    // Approve/reject routes
    Route::post('/status/{pengajuan}/approve', [PengajuanController::class, 'approve'])->middleware('role:rt,rw')->name('status.approve');
    Route::post('/status/{pengajuan}/reject', [PengajuanController::class, 'reject'])->middleware('role:rt,rw')->name('status.reject');

    // Ajukan surat (kalau memang RT/RW yang mengajukan di web)
    Route::get('/ajukan', [PengajuanController::class, 'create'])->name('ajukan');
    Route::post('/ajukan', [PengajuanController::class, 'store'])->name('pengajuan.store');

    // Status surat
    Route::get('/status/{pengajuan}', [PengajuanController::class, 'show'])->name('status.show');
    // Riwayat
    Route::get('/riwayat', [PengajuanController::class, 'history'])->name('riwayat');

    // Edit/update status
    Route::get('/status/{pengajuan}/edit', [PengajuanController::class, 'edit'])->middleware('role:rt,rw')->name('status.edit');
    Route::put('/status/{pengajuan}', [PengajuanController::class, 'update'])->middleware('role:rt,rw')->name('status.update');

    // Hapus pengajuan
    Route::delete('/status/{pengajuan}', [PengajuanController::class, 'destroy'])->middleware('role:rt,rw')->name('status.destroy');
});


// Pastikan route ini ada di dalam group 'auth'
Route::middleware(['auth'])->group(function () {
    Route::get('/iuran', [IuranController::class, 'index'])->name('iuran.index');
    Route::post('/iuran/{due}/metode', [IuranController::class, 'choosePaymentMethod'])->name('iuran.payment-method');
    Route::get('/iuran/invoice/{paymentRequest}', [IuranController::class, 'invoice'])->name('iuran.invoice');
});
   Route::middleware(['auth'])->group(function () {
    // ... rute lainnya
    Route::get('/informasi', [InformasiController::class, 'index'])->name('informasi.index');
});

// Tambahkan atau pastikan rute ini ada
Route::middleware(['auth'])
    ->get('/warga/dashboard', [PengajuanController::class, 'index'])
    ->name('warga.dashboard');

Route::get('/warga/stats', [App\Http\Controllers\PengajuanController::class, 'getStats'])->name('warga.stats');
