<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\TambahStok;
use Illuminate\Validation\Rule;
use App\Services\StokServices;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Barang::query();
        if ($request->q) {
            $query->where('nama_barang', 'like', '%' . $request->q . '%')
                ->orWhere('sku_id', 'like', '%' . $request->q . '%');
        }
        $barangs = $query->paginate(10)->appends($request->all());
        $suppliers = Supplier::all();
        return view('barang.index', compact('barangs', 'suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = Kategori::all();
        $suppliers = Supplier::all();
        return view('barang.create', compact('kategoris', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255|unique:barangs,nama_barang',
            'deskripsi' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'barcode' => 'nullable|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok_tersedia' => 'required|integer|min:0',
            'sku_id' => 'nullable|string|max:255|unique:barangs,sku_id',
            'hargaBeli' => 'required|numeric|min:0',
            'kategori_id' => 'required|exists:kategoris,id'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            Storage::disk('public')->putFileAs('barang', $image, $imageName);

            $data['image'] = 'barang/' . $imageName;
        }

        $barang = Barang::create($data);

        $stokServices = new StokServices();
        $tambahStok = $stokServices->awalStok($barang->id, $request->supplier_id, $request->stok_tersedia, $request->hargaBeli);

        TambahStok::create($tambahStok);

        return redirect()->route('barang.index')
            ->with('success', 'Barang "' . $request->nama_barang . '" berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $barang = Barang::with('kategori')->findOrFail($id);
        $suppliers = Supplier::all();
        $stokServices = new StokServices();
        $tambahStok = $stokServices->getTambahStok($id);

        return view('barang.show', compact('barang', 'suppliers', 'tambahStok'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        $kategoris = Kategori::all();
        $suppliers = Supplier::all();
        return view('barang.edit', compact('barang', 'kategoris', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $request->validate([
            'nama_barang' => 'required|string|max:255|unique:barangs,nama_barang,' . $id,
            'deskripsi' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'barcode' => 'nullable|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok_tersedia' => 'required|integer|min:0',
            'sku_id' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('barangs', 'sku_id')->ignore($barang->id),
            ],
            'hargaBeli' => 'required|numeric|min:0',
            'kategori_id' => 'required|exists:kategoris,id'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // Delete old image
            if ($barang->image) {
                Storage::disk('public')->delete($barang->image);
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            Storage::disk('public')->putFileAs('barang', $image, $imageName);

            $data['image'] = 'barang/' . $imageName;
        }

        $barang->update($data);

        return redirect()->route('barang.index')
            ->with('success', 'Data barang "' . $barang->nama_barang . '" berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $barang = Barang::findOrFail($id);
            $barang->delete();

            return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
        } catch (\Exception $e) {
            // Kirim pesan ke view sebagai flash message
            return redirect()->route('barang.index')->with('success', $e->getMessage());
        }
    }

    public function tambahStok(Request $request, $id)
    {
        $stokServices = new StokServices();
        $tambahStok = $stokServices->tambahStokBarang(
            $id,
            $request->supplier_id,
            $request->tgl_masuk,
            $request->kuantitas,
            $request->harga_satuan
        );


        return redirect()->route('barang.index')
            ->with('success', 'Stok ' . $request->kuantitas . ' ' . $tambahStok->barang->nama_barang . ' berhasil ditambahkan');
    }

    public function kurangStok($id)
    {
        $stokServices = new StokServices();
        $barangId = $stokServices->kurangStokBarang($id);

        return redirect()->route('barang.show', $barangId)
            ->with('success', 'Stok berhasil dikurangi');
    }
}
