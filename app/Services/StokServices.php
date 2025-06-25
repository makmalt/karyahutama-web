<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\TambahStok;

class StokServices
{
    public function awalStok($barangId, $supplierId, $kuantitas, $hargaSatuan)
    {
        return [
            'barang_id' => $barangId,
            'supplier_id' => $supplierId,
            'tgl_masuk' => now()->format('d-m-Y H:i'),
            'kuantitas' => $kuantitas,
            'harga_satuan' => $hargaSatuan,
            'total_harga' => $this->totalHarga($kuantitas, $hargaSatuan),
            'keterangan' => "Awal Stok"
        ];
    }


    public function tambahStokBarang($barangId, $supplierId, $tglMasuk, $kuantitas, $hargaSatuan)
    {
        $barang = Barang::findOrFail($barangId);
        $barang->stok_tersedia += $kuantitas;
        $barang->save();

        return TambahStok::create([
            'barang_id' => $barang->id,
            'supplier_id' => $supplierId,
            'tgl_masuk' => $tglMasuk,
            'kuantitas' => $kuantitas,
            'harga_satuan' => $hargaSatuan,
            'total_harga' => $this->totalHarga($kuantitas, $hargaSatuan),
        ]);
    }

    public function kurangStokBarang($tambahStokId)
    {
        $tambahStok = TambahStok::find($tambahStokId);
        $barang = Barang::find($tambahStok->barang_id);

        $barang->stok_tersedia = max(0, $barang->stok_tersedia - $tambahStok->kuantitas);
        $barang->save();
        $tambahStok->delete();
    }

    public function totalHarga($kuantitas, $hargaSatuan)
    {
        return $kuantitas * $hargaSatuan;
    }

    public function getTambahStok($barangId)
    {
        return TambahStok::where('barang_id', $barangId)
            ->orderBy('id', 'desc')
            ->get();
    }
}
