<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Kategori;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::all();
        return view('supplier.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = Kategori::all();
        return view('supplier.create', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_supplier' => 'required|string|max:255|unique:suppliers,nama_supplier',
            'alamat' => 'required|string',
            'kategori_id' => 'exists:kategoris,id',
            'kontak' => 'required|string|max:255',
        ]);

        $data = $request->all();
        Supplier::create($data);

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier "' . $request->nama_supplier . '" berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        return view('supplier.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        $kategoris = Kategori::all();
        return view('supplier.edit', compact('supplier', 'kategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'nama_supplier' => 'required|string|max:255|unique:suppliers,nama_supplier,' . $supplier->id,
            'kategori_id' => 'exists:kategoris,id',
            'alamat' => 'required|string',
            'kontak' => 'required|string|max:20',
        ]);

        $supplier->update($validated);

        return redirect()->route('supplier.index')
            ->with('success', 'Data supplier "' . $supplier->nama_supplier . '" berhasil diperbarui');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $nama_supplier = $supplier->nama_supplier;
        $supplier->delete();

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier "' . $nama_supplier . '" berhasil dihapus');
    }
}
