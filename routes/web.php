<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LogbookController;

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

    // Peminjaman (WAJIB LOGIN)
    Route::resource('peminjaman', PeminjamanController::class);

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

    Route::resource('barang', BarangController::class);
});


// ==============================
// POST KHUSUS (DENDA)
// ==============================
Route::post('/peminjaman/{peminjaman}/kembali',
    [PeminjamanController::class, 'hitungDenda']
)->name('peminjaman.kembali');