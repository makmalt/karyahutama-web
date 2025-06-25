@extends('layouts.app')

@section('title', 'POS Karya Hutama Oxygen - Daftar Transaksi')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="#">Transaksi</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Daftar Transaksi</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between mb-3">
                <h5 class="mb-0">Daftar Transaksi</h5>
                <div>
                    <a href="{{ route('transaksi.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i> Tambah Transaksi
                    </a>
                </div>
            </div>
            <!-- Modal Filter -->
            <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('transaksi.index') }}" method="GET" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="filterModalLabel">Filter Tanggal Transaksi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="d-flex justify-content-end mb-2">
                                <i class="bx bx-trash text-danger" id="resetFilterBtn" role="button"
                                    style="cursor: pointer;" title="Reset Filter"></i>
                            </div>
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Dari Tanggal</label>
                                <input type="date" class="form-control" id="start_date" name="start_date"
                                    value="{{ request('start_date') }}">
                            </div>
                            <div class="mb-3">
                                <label for="end_date" class="form-label">Sampai Tanggal</label>
                                <input type="date" class="form-control" id="end_date" name="end_date"
                                    value="{{ request('end_date') }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Terapkan</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="row mb-3 d-flex justify-content-end">
                        <div class="col-md-3 d-flex align-items-center gap-2">
                            <a href="{{ route('transaksi.export') }}" class="btn btn-success me-2" id="exportBtn">
                                <i class="bx bx-file"></i> Export Excel
                            </a>
                            @php
                                $filterCount = 0;
                                if (request('start_date')) {
                                    $filterCount++;
                                }
                                if (request('end_date')) {
                                    $filterCount++;
                                }
                            @endphp
                            <button type="button" class="btn btn-outline-secondary position-relative ms-2"
                                data-bs-toggle="modal" data-bs-target="#filterModal">
                                <i class="bx bx-filter"></i>
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark"
                                    style="font-size: 0.7em;">
                                    {{ $filterCount }}
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table" id="transaksi-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No Transaksi</th>
                                    <th>Tanggal</th>
                                    <th>Grand Total</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transaksis as $transaksi)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $transaksi->no_transaksi }}</td>
                                        <td>{{ $transaksi->tgl_transaksi->format('d-m-Y H:i') }}</td>
                                        <td>Rp {{ number_format($transaksi->grand_total, 2, ',', '.') }}</td>
                                        <td>
                                            <a href="{{ route('transaksi.show', $transaksi->id) }}"
                                                class="btn btn-info btn-sm">
                                                <i class="bx bx-detail me-1"></i> Lihat
                                            </a>
                                            <a href="{{ route('transaksi.edit', $transaksi->id) }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                            <form action="{{ route('transaksi.destroy', $transaksi->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Yakin hapus transaksi?')">
                                                    <i class="bx bx-trash me-1"></i> Hapus
                                                </button>
                                            </form>
                                            <a href="{{ route('struk', $transaksi->id) }}" class="btn btn-success btn-sm"
                                                target="_blank">
                                                <i class="bx bx-printer me-1"></i>Cetak Struk
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const exportBtn = document.getElementById('exportBtn');
            const startDate = document.querySelector('input[name="start_date"]').value;
            const endDate = document.querySelector('input[name="end_date"]').value;

            if (startDate || endDate) {
                const url = new URL(exportBtn.href);
                if (startDate) url.searchParams.set('start_date', startDate);
                if (endDate) url.searchParams.set('end_date', endDate);
                exportBtn.href = url.toString();
            }

            // Reset filter button in modal
            const resetBtn = document.getElementById('resetFilterBtn');
            if (resetBtn) {
                resetBtn.addEventListener('click', function() {
                    document.getElementById('start_date').value = '';
                    document.getElementById('end_date').value = '';
                });
            }
        });

        $(document).ready(function() {
            $('#transaksi-table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                }
            });
        });
    </script>
@endpush
