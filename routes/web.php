<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\AdminController;

// Auth
Auth::routes();

// ==============================
// 🌐 PUBLIK (TIDAK PERLU LOGIN)
// ==============================

// Homepage langsung katalog
Route::get('/', [BarangController::class, 'index'])->name('home');

// Katalog tetap bisa diakses tanpa login
Route::get('/katalog', [BarangController::class, 'index'])->name('katalog');


// ==============================
// 🔒 WAJIB LOGIN (AKSI USER)
// ==============================
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Peminjaman
    Route::resource('peminjaman', PeminjamanController::class);

    // Halaman pembayaran
    Route::get('/peminjaman/{id}/bayar',
        [PeminjamanController::class, 'bayar']
    )->name('peminjaman.bayar');

    // Upload bukti pembayaran
    Route::post('/peminjaman/{id}/bayar',
        [PeminjamanController::class, 'uploadBukti']
    )->name('peminjaman.upload_bukti');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile.show');

    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    // Logbook
    Route::get('/logbook', [LogbookController::class, 'index'])
        ->name('logbook');
});


// ==============================
// 🔒 ADMIN ONLY
// ==============================
Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->group(function () {

    // CRUD Barang
    Route::resource('barang', BarangController::class);

    // QRIS Settings
    Route::get('/qris',
        [AdminController::class, 'qrisForm']
    )->name('admin.qris');

    Route::post('/qris',
        [AdminController::class, 'qrisUpdate']
    )->name('admin.qris.update');
});


// ==============================
// POST KHUSUS (DENDA)
// ==============================
Route::post('/peminjaman/{peminjaman}/kembali',
    [PeminjamanController::class, 'hitungDenda']
)->name('peminjaman.kembali');