<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BarangCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function ($barang) {
                return [
                    'id' => $barang->id,
                    'nama_barang' => $barang->nama_barang,
                    'deskripsi' => $barang->deskripsi,
                    'image' => $barang->image,
                    'barcode' => $barang->barcode,
                    'harga' => $barang->harga,
                    'stok_tersedia' => $barang->stok_tersedia,
                    'sku_id' => $barang->sku_id,
                    'kategori_id' => $barang->kategori_id
                ];
            }),
            'meta' => [
                'api_version' => '1.0', // Contoh metadata tambahan
            ],
        ];
    }
}
