<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\Supplier;
use Illuminate\Support\Facades\Storage;

class TagihanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $query = Tagihan::with('supplier');
        if ($request->status === 'lunas') {
            $query->where('status_lunas', 1);
        } elseif ($request->status === 'belum') {
            $query->where('status_lunas', 0);
        }
        if ($request->q) {
            $query->where(function ($q) use ($request) {
                $q->where('tagihan', 'like', '%' . $request->q . '%')
                    ->orWhereHas('supplier', function ($s) use ($request) {
                        $s->where('nama_supplier', 'like', '%' . $request->q . '%');
                    });
            });
        }
        $tagihans = $query->paginate(10);
        return view('tagihan.index', compact('tagihans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $suppliers = Supplier::all();
        return view('tagihan.create', compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'tagihan' => 'required|string|max:255',
            'nominal_tagihan' => 'required|numeric|min:0',
            'jatuhTempo_tagihan' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'status_lunas' => 'required|boolean',
            'keterangan' => 'nullable|string',
            'img_nota' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('img_nota')) {
            $image = $request->file('img_nota');
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            Storage::disk('public')->putFileAs('tagihan', $image, $imageName);

            $data['img_nota'] = 'tagihan/' . $imageName;
        }

        Tagihan::create($request->all());
        return redirect()->route('tagihan.index')
            ->with('success', 'Tagihan "' . $request->tagihan . '" berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $tagihan = Tagihan::findOrFail($id);
        return view('tagihan.show', compact('tagihan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $tagihan = Tagihan::findOrFail($id);
        $suppliers = Supplier::all();
        return view('tagihan.edit', compact('tagihan', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'tagihan' => 'required|string|max:255',
            'nominal_tagihan' => 'required|numeric|min:0',
            'jatuhTempo_tagihan' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'status_lunas' => 'required|boolean',
            'keterangan' => 'nullable|string',
            'img_nota' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $tagihan = Tagihan::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('img_nota')) {
            $image = $request->file('img_nota');
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            Storage::disk('public')->putFileAs('tagihan', $image, $imageName);

            $data['img_nota'] = 'tagihan/' . $imageName;
        }

        $tagihan->update($data);
        return redirect()->route('tagihan.index')
            ->with('success', 'Tagihan "' . $tagihan->tagihan . '" berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $tagihan = Tagihan::findOrFail($id);
        $tagihan->delete();
        return redirect()->route('tagihan.index')
            ->with('success', 'Tagihan "' . $tagihan->tagihan . '" berhasil dihapus');
    }

    public function updateStatus(Request $request, string $id)
    {
        //
        $tagihan = Tagihan::findOrFail($id);
        $tagihan->update(['status_lunas' => $request->status_lunas]);
        return redirect()->back()->with('success', 'Status tagihan "' . $tagihan->tagihan . '" berhasil diperbarui');
    }
}
