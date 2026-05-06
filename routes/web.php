<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LogbookController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Auth (login, register, dll)
Auth::routes();

// ==============================
// ✅ HALAMAN UTAMA (PUBLIK)
// ==============================
Route::get('/', [BarangController::class, 'index'])->name('home');

// (opsional, tetap bisa akses /katalog)
Route::get('/katalog', [BarangController::class, 'index'])->name('katalog');


// ==============================
// 🔒 ROUTE YANG BUTUH LOGIN
// ==============================
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Peminjaman
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


