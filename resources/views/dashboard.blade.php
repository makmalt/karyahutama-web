@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">Dashboard</h2>
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card p-3">
                    <div>Total Barang</div>
                    <h2>{{ $totalItems }}</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <div>Jumlah Transaksi Bulan Ini</div>
                    <h2>{{ $transactionsThisMonth }}</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <div>Grand Total Per Bulan ini</div>
                    <h2>Rp. {{ number_format($grandTotalThisMonth, 2, ',', '.') }}</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100 d-flex flex-column">
                    <div class="card-header py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Top Barang Bulan ini</h5>
                            <form method="GET" class="mb-0">
                                <div class="input-group input-group-sm">
                                    <input type="month" id="top_month" name="top_month" class="form-control"
                                        value="{{ request('top_month', now()->format('Y-m')) }}">
                                    <input type="hidden" name="month"
                                        value="{{ request('month', now()->format('Y-m')) }}">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body p-0 d-flex align-items-stretch flex-grow-1">
                        <canvas id="topItemsChart" style="height:100%; width:100%"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100 d-flex flex-column">
                    <div class="card-header py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Pembelian Bulan ini</h5>
                            <form method="GET" class="mb-0">
                                <div class="input-group input-group-sm">
                                    <input type="month" id="month" name="month" class="form-control"
                                        value="{{ request('month', now()->format('Y-m')) }}">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body p-0 d-flex align-items-stretch flex-grow-1">
                        <canvas id="purchasesChart" style="height:100%; width:100%"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5>Barang Keluar Bulan ini</h5>
            </div>
            <div class="card-body">
                <table class="table" id="barang-keluar">
                    <thead>
                        <tr>
                            <th>
                                Nama Barang
                            </th>
                            <th>
                                Jumlah
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topItems as $item)
                            <tr>
                                <td>{{ $item->barang->nama_barang }}</td>
                                <td>{{ $item->sold }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Bar Chart Data
        const topItemsLabels = {!! json_encode($topItems->map(fn($item) => $item->barang ? $item->barang->nama_barang : 'Unknown')) !!};
        const topItemsData = {!! json_encode($topItems->pluck('sold')) !!};

        new Chart(document.getElementById('topItemsChart'), {
            type: 'bar',
            data: {
                labels: topItemsLabels,
                datasets: [{
                    label: 'Barang Terjual',
                    data: topItemsData,
                    backgroundColor: 'rgba(13, 110, 253, 0.2)',
                    borderColor: 'rgba(13, 110, 253, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                }
            }
        });

        // Line Chart Data
        const purchasesLabels = {!! json_encode($purchases->pluck('date')) !!};
        const purchasesData = {!! json_encode($purchases->pluck('total')) !!};

        new Chart(document.getElementById('purchasesChart'), {
            type: 'line',
            data: {
                labels: purchasesLabels,
                datasets: [{
                    label: 'Grand Total',
                    data: purchasesData,
                    borderColor: 'rgba(13, 110, 253, 1)',
                    backgroundColor: 'rgba(13, 110, 253, 0.2)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                }
            }
        });

        // DataTables for all tables
        $(document).ready(function() {
            $('#barang-keluar').DataTable();
        });
    </script>
@endpush
