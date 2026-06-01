<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LaporanApiController;
use App\Http\Controllers\Api\KategoriApiController;
use App\Http\Controllers\Api\NotifikasiApiController;

/*
|--------------------------------------------------------------------------
| API Routes — Mobile Flutter (User/Warga)
|--------------------------------------------------------------------------
*/

// ── PUBLIC (tidak perlu token) ──────────────────────────────────────────
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// ── PROTECTED (wajib Bearer Token) ────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);
    Route::put('/me',      [AuthController::class, 'updateProfile']);

    // Kategori (read only)
    Route::get('/kategori', [KategoriApiController::class, 'index']);

    // Laporan
    Route::get('/laporan',        [LaporanApiController::class, 'index']);
    Route::post('/laporan',       [LaporanApiController::class, 'store']);
    Route::get('/laporan/semua',  [LaporanApiController::class, 'semuaLaporan']);
    Route::get('/laporan/{id}',   [LaporanApiController::class, 'show']);

    // Notifikasi
    Route::get('/notifikasi',                  [NotifikasiApiController::class, 'index']);
    Route::post('/notifikasi/tandai-semua',    [NotifikasiApiController::class, 'tandaiSemua']);
    Route::post('/notifikasi/{id}/baca',       [NotifikasiApiController::class, 'tandaiSatu']);
});
