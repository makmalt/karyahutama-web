<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthWebController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\UserController;



Route::get('/login', [AuthWebController::class, 'showLoginForm'])->middleware('guest')->name('login');
Route::post('/login', [AuthWebController::class, 'loginWeb'])->name('login.submit');
Route::post('/logout', [AuthWebController::class, 'logoutWeb'])->name('logout');
Route::get('/cek-waktu', function () {
    return now()->format('d-m-Y H:i');
});

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('/barang', BarangController::class);
    Route::post('/barang/tambah-stok/{id}', [BarangController::class, 'tambahStok'])->name('barang.tambah-stok');
    Route::post('/barang/kurang-stok/{id}', [BarangController::class, 'kurangStok'])->name('barang.kurang-stok');
    Route::resource('/supplier', SupplierController::class);
    Route::resource('/transaksi', TransaksiController::class);
    Route::get('/transaksi-export', [TransaksiController::class, 'export'])->name('transaksi.export');
    Route::resource('/tagihan', TagihanController::class);
    Route::put('/tagihan/update-status/{id}', [TagihanController::class, 'updateStatus'])->name('tagihan.updateStatus');
    Route::resource('/kategori', KategoriController::class);
    Route::get('/struk/{transaksi}', [TransaksiController::class, 'struk'])->name('struk');
    Route::resource('/user', UserController::class);
});
