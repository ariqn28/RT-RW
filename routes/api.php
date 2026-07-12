<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



use App\Http\Controllers\Api\MobileAuthController;
use App\Http\Controllers\Api\MobileProfileController;
use App\Http\Controllers\Api\MobilePengajuanController;
use App\Http\Controllers\Api\MobileRiwayatController;




Route::post('/mobile/login', [MobileAuthController::class, 'mobileLogin']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [MobileProfileController::class, 'index']);
    Route::put('/profile', [MobileProfileController::class, 'update']);

    Route::get('/pengajuan', [MobilePengajuanController::class, 'index']);
    Route::post('/pengajuan', [MobilePengajuanController::class, 'store']);
    Route::get('/pengajuan/{pengajuan}', [MobilePengajuanController::class, 'show']);

    Route::get('/riwayat', [MobileRiwayatController::class, 'index']);


    Route::post('/logout', [MobileAuthController::class, 'logout']);
});

// legacy: keep compatibility
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

