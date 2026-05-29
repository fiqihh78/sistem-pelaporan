<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LaporanApiController;
use App\Http\Controllers\Api\KategoriApiController;
use App\Http\Controllers\Api\NotifikasiApiController;

/*
|--------------------------------------------------------------------------
| API Routes — Mobile (User / Warga)
|--------------------------------------------------------------------------
| Semua endpoint di sini diakses oleh aplikasi Flutter (mobile user).
| Autentikasi menggunakan Laravel Sanctum (Bearer Token).
|--------------------------------------------------------------------------
*/

// ── PUBLIC (tidak perlu login) ──────────────────────────────────────────
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// ── PROTECTED (harus login) ─────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);
    Route::put('/me',      [AuthController::class, 'updateProfile']);

    // Kategori (read-only untuk user)
    Route::get('/kategori', [KategoriApiController::class, 'index']);

    // Laporan
    Route::get('/laporan',       [LaporanApiController::class, 'index']);   // semua laporan milik user
    Route::post('/laporan',      [LaporanApiController::class, 'store']);   // buat laporan baru
    Route::get('/laporan/{id}',  [LaporanApiController::class, 'show']);    // detail laporan
    Route::get('/laporan/semua', [LaporanApiController::class, 'semuaLaporan']); // semua laporan warga (publik)

    // Notifikasi
    Route::get('/notifikasi',                 [NotifikasiApiController::class, 'index']);
    Route::post('/notifikasi/tandai-semua',   [NotifikasiApiController::class, 'tandaiSemua']);
    Route::post('/notifikasi/{id}/baca',      [NotifikasiApiController::class, 'tandaiSatu']);
});
