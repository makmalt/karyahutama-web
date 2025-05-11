@extends('layouts.app')

@section('title', 'Detail Barang')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="#">Barang</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('barang.index') }}">Daftar Barang</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail Barang</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between mb-3">
                <h5 class="mb-0">Detail Barang</h5>
                <a href="{{ route('barang.edit', $barang->id) }}" class="btn btn-primary">
                    <i class="bx bx-edit-alt me-1"></i> Edit
                </a>
            </div>
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="fw-semibold">Nama Barang</h6>
                                <p class="mb-0">{{ $barang->nama_barang }}</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="fw-semibold">Kategori</h6>
                                <p class="mb-0">{{ $barang->kategori->nama_kategori }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="fw-semibold">Stok</h6>
                                <p class="mb-0">{{ $barang->stok_tersedia }}</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="fw-semibold">Harga</h6>
                                <p class="mb-0">Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="fw-semibold">Status</h6>
                                <p class="mb-0">
                                    <span class="badge bg-{{ $barang->stok_tersedia > 0 ? 'success' : 'danger' }}">
                                        {{ $barang->stok_tersedia > 0 ? 'Tersedia' : 'Habis' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6 class="fw-semibold">Deskripsi</h6>
                        <p class="mb-0">{{ $barang->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                    </div>

                    <!-- Riwayat Stok -->
                    <div class="mt-4">
                        <h5 class="fw-semibold mb-3">Riwayat Stok</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3">Tanggal</th>
                                        <th class="px-4 py-3">Kuantitas</th>
                                        <th class="px-4 py-3">Harga Satuan</th>
                                        <th class="px-4 py-3">Total Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($barang->tambahStok as $tambahStok)
                                        <tr>
                                            <td>{{ $tambahStok->tgl_masuk->format('d F Y') }}</td>
                                            <td>{{ $tambahStok->kuantitas }}</td>
                                            <td>{{ $tambahStok->harga_satuan }}</td>
                                            <td>{{ $tambahStok->total_harga }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Tidak ada riwayat stok</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
