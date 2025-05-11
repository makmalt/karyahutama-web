<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\BarangTransaksi;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Total items
        $totalItems = Barang::count();

        // Transactions this month
        $transactionsThisMonth = Transaksi::whereMonth('tgl_transaksi', now()->month)
            ->whereYear('tgl_transaksi', now()->year)
            ->count();

        // Grand total this month
        $grandTotalThisMonth = Transaksi::whereMonth('tgl_transaksi', now()->month)
            ->whereYear('tgl_transaksi', now()->year)
            ->sum('grand_total');

        // Top items (bar chart) with month filter
        $topSelectedMonth = $request->input('top_month', now()->format('Y-m'));
        [$topYear, $topMonth] = explode('-', $topSelectedMonth);

        // Sorting for topItems
        $sortField = $request->input('sort_field', 'sold');
        $sortDir = $request->input('sort_dir', 'desc');

        // Top items (bar chart) with month filter, no manual sorting (handled by DataTables)
        $topItems = BarangTransaksi::select('barang_id', DB::raw('SUM(quantity) as sold'))
            ->whereHas('transaksi', function ($q) use ($topMonth, $topYear) {
                $q->whereMonth('tgl_transaksi', $topMonth)
                    ->whereYear('tgl_transaksi', $topYear);
            })
            ->groupBy('barang_id')
            ->with('barang')
            ->get();

        // Purchases per day (line chart) with month filter
        $selectedMonth = $request->input('month', now()->format('Y-m'));
        [$year, $month] = explode('-', $selectedMonth);
        $purchases = Transaksi::select(
            DB::raw('DATE(tgl_transaksi) as date'),
            DB::raw('SUM(grand_total) as total')
        )
            ->whereMonth('tgl_transaksi', $month)
            ->whereYear('tgl_transaksi', $year)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('dashboard', compact(
            'totalItems',
            'transactionsThisMonth',
            'grandTotalThisMonth',
            'topItems',
            'purchases'
        ));
    }
}
