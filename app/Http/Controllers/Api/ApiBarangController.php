<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\BarangCollection;
use App\Models\Barang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use function Laravel\Prompts\search;

class ApiBarangController extends Controller
{
    //
    public function index()
    {
        $barang = Barang::paginate(6);
        return new BarangCollection($barang);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('q');
        if (!$keyword) {
            return response()->json(['message' => 'Keyword tidak dikirim'], 400);
        }

        $barang = Barang::where('nama_barang', 'like', '%' . $keyword . '%');

        return response()->json([
            'status' => 'success',
            'data' => $barang->get(),
        ], 200);
    }

    public function show($id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json([
                'status' => 'error',
                'message' => 'Barang tidak ditemukan',
            ], 404);
        }

        // Mengganti kategori_id dengan nama kategori
        $barang->kategori_id = $barang->kategori->nama_kategori;

        return response()->json([
            'status' => 'success',
            'data' => $barang,
        ], 200);
    }

    public function findByBarcode($barcode)
    {
        $barang = Barang::where('barcode', $barcode)->first();

        if (!$barang) {
            return response()->json([
                'status' => 'error',
                'message' => 'Barang tidak ditemukan',
            ], 404);
        }

        // Mengganti kategori_id dengan nama kategori
        $barang->kategori_id = $barang->kategori->nama_kategori;

        return response()->json([
            'status' => 'success',
            'data' => $barang,
        ], 200);
    }
}
