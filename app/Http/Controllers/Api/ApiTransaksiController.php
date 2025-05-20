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
            // Generate nomor transaksi
            $no_transaksi = 'TRX-' . now()->format('dmY') . '-' . strtoupper(uniqid());

            $transaksi = Transaksi::create([
                'no_transaksi' => $no_transaksi,
                // 'no_transaksi' => $request->input('no_transaksi'),
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
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function struk(Transaksi $transaksi)
    {
        return view('struk', compact('transaksi'));
    }

    public function strukPlain($id)
    {
        $transaksi = Transaksi::with('barangTransaksi.barang')->findOrFail($id);

        $struk = "=== Karya Hutama Oxygen ===\n";
        $struk .= "No: {$transaksi->no_transaksi}\n";
        $struk .= "Tgl: " . $transaksi->tgl_transaksi->format('d/m/Y H:i') . "\n";
        $struk .= "-----------------------------\n";

        foreach ($transaksi->barangTransaksi as $item) {
            $struk .= substr($item->barang->nama_barang, 0, 15) . " ";
            $struk .= number_format($item->total_harga, 0, ',', '.') . "\n";
            $struk .= "{$item->quantity}x" . number_format($item->harga_barang, 0, ',', '.') . "\n";
        }

        $struk .= "-----------------------------\n";
        $struk .= "TOTAL     : Rp" . number_format($transaksi->grand_total, 0, ',', '.') . "\n";
        $struk .= "BAYAR     : Rp" . number_format($transaksi->uang_pembayaran, 0, ',', '.') . "\n";
        $struk .= "KEMBALIAN : Rp" . number_format($transaksi->uang_kembalian, 0, ',', '.') . "\n";
        $struk .= "-----------------------------\n";
        $struk .= "Terima kasih!\n";

        return response($struk)->header('Content-Type', 'text/plain');
    }
}
