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
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware('auth')->group(function () {
    Route::get('/stok', [StokController::class, 'index'])->name('stok');
    // Route::get('/stok', [StokController::class, 'edit'])->name('stok.edit');
    Route::patch('/stok', [StokController::class, 'update'])->name('stok.update');
    Route::delete('/stok', [StokController::class, 'destroy'])->name('stok.destroy');
});
Route::middleware('auth')->group(function () {
    Route::get('/validasi', [ValidasiController::class, 'index'])->name('validasi');
    // Route::get('/validasi', [ValidasiController::class, 'edit'])->name('validasi.edit');
    Route::patch('/validasi/{id}', [ValidasiController::class, 'update'])->name('validasi.update');
    Route::delete('/validasi', [ValidasiController::class, 'destroy'])->name('validasi.destroy');
});
Route::middleware('auth')->group(function () {
    Route::get('/history', [HistoryController::class, 'index'])->name('history');
    // Route::get('/history', [HistoryController::class, 'create'])->name('history.create');
    Route::post('/history', [HistoryController::class, 'store'])->name('history.store');
    Route::get('/history/{id}', [HistoryController::class, 'edit'])->name('history.edit');
    Route::patch('/history/{id}', [HistoryController::class, 'update'])->name('history.update');
    Route::delete('/history', [HistoryController::class, 'destroy'])->name('history.destroy');
});
Route::middleware('auth')->group(function () {
    Route::get('/admin/petani', [PetaniController::class, 'index'])->name('petani');
    Route::get('/admin/petani/create', [PetaniController::class, 'create'])->name('petani.create');
    Route::post('/admin/petani', [PetaniController::class, 'store'])->name('petani.store');
    Route::get('/admin/petani/{id}', [PetaniController::class, 'edit'])->name('petani.edit');
    Route::patch('/admin/petani/{id}', [PetaniController::class, 'update'])->name('petani.update');
    Route::delete('/admin/petani/{id}', [PetaniController::class, 'destroy'])->name('petani.destroy');

    Route::get('/admin/produk', [ProdukController::class, 'index'])->name('produk');
    Route::get('/admin/produk/create', [ProdukController::class, 'create'])->name('produk.create');
    Route::post('/admin/produk/store', [ProdukController::class, 'store'])->name('produk.store');
    Route::patch('/admin/produk/{produk}', [ProdukController::class, 'update'])->name('produk.update');
    Route::delete('/admin/produk/{produk}', [ProdukController::class, 'destroy'])->name('produk.destroy');
});

require __DIR__.'/auth.php';
