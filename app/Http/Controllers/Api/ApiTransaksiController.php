<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\BarangTransaksi;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ApiTransaksiController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'barang_transaksis' => 'required|array',
            'barang_transaksis.*.barang_id' => 'required|exists:barangs,id',
            'barang_transaksis.*.harga_barang' => 'required|numeric|min:0',
            'barang_transaksis.*.quantity' => 'required|integer',
            'barang_transaksis.*.total_harga' => 'required|numeric|min:0',
            'grand_total' => 'required|numeric|min:0',
            'uang_pembayaran' => 'numeric|min:0',
            'uang_kembalian' => 'numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Check stock availability for each item
            foreach ($request->input('barang_transaksis') as $barangTransaksiData) {
                $barang = \App\Models\Barang::findOrFail($barangTransaksiData['barang_id']);
                if ($barang->stok_tersedia < $barangTransaksiData['quantity']) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Stok barang tidak mencukupi',
                        'barang' => $barang->nama_barang,
                        'stok_tersedia' => $barang->stok_tersedia,
                        'quantity_dibutuhkan' => $barangTransaksiData['quantity']
                    ], 422);
                }
            }

            $transaksi = Transaksi::create([
                'no_transaksi' => $request->input('no_transaksi'),
                'tgl_transaksi' => now(),
                'uang_pembayaran' => $request->input('uang_pembayaran'),
                'uang_kembalian' => $request->input('uang_kembalian'),
                'grand_total' => $request->input('grand_total'),
            ]);

            // Loop untuk menambahkan barang transaksis
            foreach ($request->input('barang_transaksis') as $barangTransaksiData) {
                BarangTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'barang_id' => $barangTransaksiData['barang_id'],
                    'harga_barang' => $barangTransaksiData['harga_barang'],
                    'quantity' => $barangTransaksiData['quantity'],
                    'total_harga' => $barangTransaksiData['total_harga'],
                ]);
            }

            // Commit transaksi database
            DB::commit();

            // Kembalikan respons sukses
            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi berhasil ditambahkan!',
                'data' => $transaksi,
            ], 201);
        } catch (\Exception $e) {
            // Rollback transaksi database jika terjadi kesalahan
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menambahkan transaksi.',
                'errors' => "Data barang tidak valid",
            ], 422);
        }
    }

    public function struk(Transaksi $transaksi)
    {
        return view('struk', compact('transaksi'));
    }
}
