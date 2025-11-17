<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PetaniController;
use App\Http\Controllers\ValidasiController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\RencanaPanenController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/panen', [RencanaPanenController::class, 'store'])->name('panen.store');
});

Route::middleware('auth')->group(function () {
    // Profil
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ROUTE MANAJEMEN STOK
Route::middleware('auth')->group(function () {
    Route::get('/stok', [StokController::class, 'index'])->name('stok');
    
    // [EDIT STOK] Route untuk menampilkan form edit stok
    Route::get('/stok/{id}/edit', [StokController::class, 'edit'])->name('stok.edit'); 
    
    // [UPDATE STOK] Route untuk memproses update stok
    Route::patch('/stok/{id}', [StokController::class, 'update'])->name('stok.update'); 
    
    // Route::delete('/stok', [StokController::class, 'destroy'])->name('stok.destroy'); // Tidak umum untuk stok
});

// VALIDASI
Route::middleware('auth')->group(function () {
    Route::get('/validasi', [ValidasiController::class, 'index'])->name('validasi');
    Route::patch('/validasi/{id}', [ValidasiController::class, 'update'])->name('validasi.update');
    // Route::delete('/validasi', [ValidasiController::class, 'destroy'])->name('validasi.destroy');
});

// HISTORY
Route::middleware('auth')->group(function () {
    Route::get('/history', [HistoryController::class, 'index'])->name('history');
    Route::post('/history', [HistoryController::class, 'store'])->name('history.store');
    Route::get('/history/{id}', [HistoryController::class, 'edit'])->name('history.edit');
    Route::patch('/history/{id}', [HistoryController::class, 'update'])->name('history.update');
    Route::delete('/history', [HistoryController::class, 'destroy'])->name('history.destroy');
});

// ADMIN (PETANI & PRODUK)
Route::middleware('auth')->group(function () {
    // Petani
    Route::get('/admin/petani', [PetaniController::class, 'index'])->name('petani');
    Route::get('/admin/petani/create', [PetaniController::class, 'create'])->name('petani.create');
    Route::post('/admin/petani', [PetaniController::class, 'store'])->name('petani.store');
    Route::get('/admin/petani/{id}/edit', [PetaniController::class, 'edit'])->name('petani.edit'); // Koreksi: Menggunakan /edit
    Route::patch('/admin/petani/{id}', [PetaniController::class, 'update'])->name('petani.update');
    Route::delete('/admin/petani/{id}', [PetaniController::class, 'destroy'])->name('petani.destroy');

    // Produk
    Route::get('/admin/produk', [ProdukController::class, 'index'])->name('produk');
    Route::get('/admin/produk/create', [ProdukController::class, 'create'])->name('produk.create');
    Route::post('/admin/produk', [ProdukController::class, 'store'])->name('produk.store'); // Koreksi: Store di /admin/produk
    Route::get('/admin/produk/{id}/edit', [ProdukController::class, 'edit'])->name('produk.edit'); // Tambahan: Route edit produk
    Route::patch('/admin/produk/{id}', [ProdukController::class, 'update'])->name('produk.update'); // Koreksi: Menggunakan {id}
    Route::delete('/admin/produk/{id}', [ProdukController::class, 'destroy'])->name('produk.destroy'); // Koreksi: Menggunakan {id}
});

require __DIR__.'/auth.php';