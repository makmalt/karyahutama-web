<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ApiBarangController;   
use App\Http\Controllers\Api\ApiTransaksiController;

Route::get('/', function () {
    return response()->json([
        'message' => 'API is running'
    ]);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/barang', [ApiBarangController::class, 'index']);
    Route::get('/barang/search', [ApiBarangController::class, 'search']);
    Route::get('/barang/show/{id}', [ApiBarangController::class, 'show']);
    Route::get('/barang/barcode/{barcode}', [ApiBarangController::class, 'findByBarcode']);
    Route::post('/transaksi', [ApiTransaksiController::class, 'store']);
});
