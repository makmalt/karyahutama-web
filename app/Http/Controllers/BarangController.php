<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\TambahStok;
use Illuminate\Validation\Rule;

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
            'kategori_id' => 'required|exists:kategori,id'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            Storage::disk('public')->putFileAs('barang', $image, $imageName);

            $data['image'] = 'barang/' . $imageName;
        }

        $barang = Barang::create($data);
        TambahStok::create([
            'barang_id' => $barang->id,
            'supplier_id' => $request->supplier_id,
            'tgl_masuk' => now(),
            'kuantitas' => $request->stok_tersedia,
            'harga_satuan' => $request->hargaBeli,
            'total_harga' => $request->hargaBeli * $request->stok_tersedia,
            'keterangan' => "Awal Stok"
        ]);

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
        $tambahStok = TambahStok::with('supplier')
            ->where('barang_id', $id)
            ->orderBy('id', 'desc')
            ->get();

        // Tambahkan kondisi IF
        if ($tambahStok->isEmpty()) {
            session()->flash('warning', 'Barang ini belum pernah ditambah stok.');
        }

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
            'kategori_id' => 'required|exists:kategori,id'
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
        $barang = Barang::find($id);
        $barang->stok_tersedia += $request->kuantitas;
        $barang->save();
        TambahStok::create([
            'barang_id' => $barang->id,
            'supplier_id' => $request->supplier_id,
            'tgl_masuk' => $request->tgl_masuk,
            'kuantitas' => $request->kuantitas,
            'harga_satuan' => $request->harga_satuan,
            'total_harga' => $request->total_harga
        ]);
        return redirect()->route('barang.index')
            ->with('success', 'Stok ' . $request->kuantitas . ' ' . $barang->nama_barang . ' berhasil ditambahkan');
    }

    public function kurangStok($id)
    {
        $tambahStok = TambahStok::find($id);
        $barang = Barang::find($tambahStok->barang_id);

        $barang->stok_tersedia = max(0, $barang->stok_tersedia - $tambahStok->kuantitas);
        $barang->save();
        $tambahStok->delete();
        return redirect()->route('barang.show', $barang->id)
            ->with('success', 'Stok ' . $tambahStok->kuantitas . ' ' . $barang->nama_barang . ' berhasil dikurangi');
    }
}
