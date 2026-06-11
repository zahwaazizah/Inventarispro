<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes - INVENTARISPRO
|--------------------------------------------------------------------------
*/

// ==================== RUTE PUBLIK ====================
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/scan/{hash}', [QrCodeController::class, 'publicShow'])->name('public.scan');

// ==================== RUTE LOGIN ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [AuthController::class, 'profile'])->name('index');
        Route::put('/', [AuthController::class, 'updateProfile'])->name('update');
        Route::put('/password', [AuthController::class, 'updatePassword'])->name('password');
    });

    // ==================== SEMUA ROLE ====================
    Route::get('/inventaris', [InventarisController::class, 'index'])->name('inventaris.index');
    Route::get('/inventaris/{id}', [InventarisController::class, 'show'])->name('inventaris.show');
    Route::get('/inventaris/search', [InventarisController::class, 'search'])->name('inventaris.search');

    // Scan QR Code (semua role)
    Route::get('/qr/scan', [QrCodeController::class, 'showScanPage'])->name('qr.scan');
    Route::post('/qr/scan/process', [QrCodeController::class, 'processScan'])->name('qr.scan.process');

    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::get('/riwayat/filter', [RiwayatController::class, 'filter'])->name('riwayat.filter');
    Route::get('/riwayat/export/excel', [RiwayatController::class, 'exportExcel'])->name('riwayat.export.excel');
    Route::get('/riwayat/export/pdf', [RiwayatController::class, 'exportPdf'])->name('riwayat.export.pdf');

    // ==================== ADMIN ONLY ====================
    Route::middleware(['role:admin'])->group(function () {
        // Inventaris (CRUD)
        Route::get('/inventaris/create', [InventarisController::class, 'create'])->name('inventaris.create');
        Route::post('/inventaris', [InventarisController::class, 'store'])->name('inventaris.store');
        Route::get('/inventaris/{id}/edit', [InventarisController::class, 'edit'])->name('inventaris.edit');
        Route::put('/inventaris/{id}', [InventarisController::class, 'update'])->name('inventaris.update');
        Route::delete('/inventaris/{id}', [InventarisController::class, 'destroy'])->name('inventaris.destroy');

        // Rute tambahan untuk create (alternatif)
        Route::get('/inventaris/tambah', [InventarisController::class, 'create'])->name('inventaris.tambah');

        // QR Code (kelola QR) – hanya admin
        Route::prefix('qr')->name('qr.')->group(function () {
            Route::get('/', [QrCodeController::class, 'index'])->name('index');
            Route::get('/generate/{id}', [QrCodeController::class, 'generate'])->name('generate');
            Route::get('/refresh/{id}', [QrCodeController::class, 'refresh'])->name('refresh');
            Route::get('/download/{id}', [QrCodeController::class, 'download'])->name('download');
            Route::get('/json/{id}', [QrCodeController::class, 'getJson'])->name('json');
            Route::delete('/destroy/{id}', [QrCodeController::class, 'destroyQr'])->name('destroy');
        });

        // Master data
        Route::resource('kategori', KategoriController::class);
        Route::resource('lokasi', LokasiController::class);
        Route::resource('users', UserController::class);

        // Laporan
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/', [LaporanController::class, 'index'])->name('index');
            Route::get('/inventaris', [LaporanController::class, 'laporanInventaris'])->name('inventaris');
            Route::get('/transaksi', [LaporanController::class, 'laporanTransaksi'])->name('transaksi');
            Route::get('/export/excel', [LaporanController::class, 'exportExcel'])->name('export.excel');
            Route::get('/export/pdf', [LaporanController::class, 'exportPdf'])->name('export.pdf');
        });
    });

    // ==================== PETUGAS ONLY ====================
    Route::middleware(['role:petugas'])->group(function () {
        Route::prefix('transaksi')->name('transaksi.')->group(function () {
            Route::get('/peminjaman', [TransaksiController::class, 'formPeminjaman'])->name('peminjaman.form');
            Route::get('/', [TransaksiController::class, 'index'])->name('index');
            Route::post('/pinjam', [TransaksiController::class, 'storePinjam'])->name('pinjam');
            Route::get('/{id}/kembali', [TransaksiController::class, 'formKembali'])->name('kembali.form');
            Route::post('/{id}/kembali', [TransaksiController::class, 'processKembali'])->name('kembali');
        });
    });
});

// Fallback
Route::fallback(function () {
    return view('errors.404');
});