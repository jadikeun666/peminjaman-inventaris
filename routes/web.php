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

// Dashboard (harus login)
Route::get('/', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// Katalog barang (publik)
Route::get('/katalog', [BarangController::class, 'index'])
    ->name('katalog');

// Route yang butuh login
Route::middleware('auth')->group(function () {

    // Peminjaman
    Route::resource('peminjaman', PeminjamanController::class);

    // Profile (FIX: pakai index)
    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile.show');

    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    // Logbook
    Route::get('/logbook', [LogbookController::class, 'index'])
        ->name('logbook');
});

// Route admin
Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->group(function () {

    Route::resource('barang', BarangController::class);

});