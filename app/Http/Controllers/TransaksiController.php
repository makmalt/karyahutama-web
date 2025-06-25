<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Barang;
use App\Models\BarangTransaksi;
use Spatie\SimpleExcel\SimpleExcelWriter;

use function Pest\Laravel\get;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaksi::query();

        if ($request->start_date) {
            $query->whereDate('tgl_transaksi', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('tgl_transaksi', '<=', $request->end_date);
        }

        $transaksis = $query->orderBy('tgl_transaksi', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return view('transaksi.index', compact('transaksis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $barangs = Barang::all();
        $no_transaksi = 'TRX-' . now()->format('dmY') . '-' . strtoupper(uniqid());
        return view('transaksi.create', compact('barangs', 'no_transaksi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_transaksi' => 'required|unique:transaksi,no_transaksi',
            'tgl_transaksi' => 'required|date',
            'grand_total' => 'required|numeric|min:0',
            'uang_pembayaran' => 'nullable|numeric|min:0',
            'uang_kembalian' => 'nullable|numeric|min:0',
        ]);
        $transaksi = Transaksi::create($request->all());
        
        $barangTransaksi = [];

        foreach ($request->barang_id as $index => $barangId) {
            $barangTransaksi[] = [
                'barang_id' => $barangId,
                'transaksi_id' => $transaksi->id,
                'quantity' => $request->quantity[$index],
                'harga_barang' => $request->harga_barang[$index],
                'total_harga' => $request->total_harga[$index],
            ];
        }

        foreach ($barangTransaksi as $item) {
            BarangTransaksi::create($item);
        }
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $transaksi = Transaksi::findOrFail($id);
        return view('transaksi.show', compact('transaksi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $transaksi = Transaksi::findOrFail($id);
        $barangs = Barang::all();
        $barangTransaksi = BarangTransaksi::with('barang')->where('transaksi_id', $id)->get();
        return view('transaksi.edit', compact('transaksi', 'barangs', 'barangTransaksi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $request->validate([
            'no_transaksi' => 'required|unique:transaksi,no_transaksi,' . $id,
            'tgl_transaksi' => 'required',
            'grand_total' => 'required',
            'uang_pembayaran' => 'nullable',
            'uang_kembalian' => 'nullable',
        ]);

        // Update data utama transaksi
        $transaksi->update([
            'no_transaksi' => $request->no_transaksi,
            'tgl_transaksi' => $request->tgl_transaksi,
            'grand_total' => $request->grand_total,
            'uang_pembayaran' => $request->uang_pembayaran,
            'uang_kembalian' => $request->uang_kembalian,
        ]);

        // Hapus dan isi ulang barang_transaksi
        BarangTransaksi::where('transaksi_id', $transaksi->id)->delete();

        foreach ($request->barang_id as $index => $barangId) {
            BarangTransaksi::create([
                'barang_id' => $barangId,
                'transaksi_id' => $transaksi->id,
                'quantity' => $request->quantity[$index],
                'harga_barang' => $request->harga_barang[$index],
                'total_harga' => $request->total_harga[$index],
            ]);
        }

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diubah');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $transaksi = Transaksi::findOrFail($id);
        foreach ($transaksi->barangTransaksi as $item) {
            $item->delete(); // Ini akan memicu event dan menjalankan addStock
        }

        $transaksi->delete();
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus');
    }

    public function struk(Transaksi $transaksi)
    {
        return view('struk', compact('transaksi'));
    }

    public function export(Request $request)
    {
        $query = Transaksi::query();

        if ($request->start_date) {
            $query->whereDate('tgl_transaksi', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('tgl_transaksi', '<=', $request->end_date);
        }

        $transaksis = $query->with(['barangTransaksi.barang'])->get();

        $rows = [];
        foreach ($transaksis as $transaksi) {
            foreach ($transaksi->barangTransaksi as $item) {
                $rows[] = [
                    'No Transaksi' => $transaksi->no_transaksi,
                    'Tanggal' => $transaksi->tgl_transaksi ? $transaksi->tgl_transaksi->format('d-m-Y') : '',
                    'Grand Total' => $transaksi->grand_total,
                    'Nama Barang' => $item->barang->nama_barang ?? '',
                    'SKU' => $item->barang->sku_id ?? '',
                    'Harga Barang' => $item->harga_barang,
                    'Quantity' => $item->quantity,
                    'Total Harga' => $item->total_harga,
                ];
            }
        }

        $filename = 'transaksi';
        if ($request->start_date && $request->end_date) {
            $filename .= '_' . $request->start_date . '_to_' . $request->end_date;
        } elseif ($request->start_date) {
            $filename .= '_from_' . $request->start_date;
        } elseif ($request->end_date) {
            $filename .= '_until_' . $request->end_date;
        }
        $filename .= '.xlsx';

        $writer = SimpleExcelWriter::streamDownload($filename);
        $writer->addRows($rows);

        return $writer->toBrowser();
    }
}
