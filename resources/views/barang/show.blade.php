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
                <div class="d-flex gap-2">
                    <a href="{{ route('barang.edit', $barang->id) }}" class="btn btn-primary">
                        <i class="bx bx-edit-alt me-1"></i> Edit
                    </a>
                    <a href="#" class="btn btn-success" data-bs-toggle="modal"
                        data-bs-target="#tambahStokModal{{ $barang->id }}">
                        <i class="bx bx-plus"></i> Tambah Stok
                    </a>
                </div>
            </div>
            <!-- Modal Tambah Stok -->
            <div class="modal fade" id="tambahStokModal{{ $barang->id }}" tabindex="-1"
                aria-labelledby="tambahStokModalLabel{{ $barang->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="tambahStokModalLabel{{ $barang->id }}">Tambah
                                Stok - {{ $barang->nama_barang }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('barang.tambah-stok', $barang->id) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="supplier_id{{ $barang->id }}" class="form-label">Supplier</label>
                                    <select class="form-select" id="supplier_id{{ $barang->id }}" name="supplier_id">
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">
                                                {{ $supplier->nama_supplier }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="tgl_masuk{{ $barang->id }}" class="form-label">Tanggal
                                        Masuk</label>
                                    <input type="date" class="form-control" id="tgl_masuk{{ $barang->id }}"
                                        name="tgl_masuk" required>
                                </div>
                                <div class="mb-3">
                                    <label for="harga_satuan{{ $barang->id }}" class="form-label">Harga
                                        Satuan</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" id="harga_satuan{{ $barang->id }}"
                                            name="harga_satuan" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="kuantitas{{ $barang->id }}" class="form-label">Jumlah
                                        Stok</label>
                                    <input type="number" class="form-control" id="kuantitas{{ $barang->id }}"
                                        name="kuantitas" min="1" required>
                                </div>
                                <div class="mb-3">
                                    <label for="total_harga{{ $barang->id }}" class="form-label">Total
                                        Harga</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" id="total_harga{{ $barang->id }}"
                                            name="total_harga" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success">Tambah</button>
                            </div>
                        </form>
                    </div>
                </div>
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
                            <table class="table" id="riwayat-stok">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3">Tanggal</th>
                                        <th class="px-4 py-3">Kuantitas</th>
                                        <th class="px-4 py-3">Harga Satuan</th>
                                        <th class="px-4 py-3">Total Harga</th>
                                        <th class="px-4 py-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($barang->tambahStok as $tambahStok)
                                        <tr>
                                            <td>{{ $tambahStok->tgl_masuk->format('d F Y') }}</td>
                                            <td>{{ $tambahStok->kuantitas }}</td>
                                            <td>{{ $tambahStok->harga_satuan }}</td>
                                            <td>{{ $tambahStok->total_harga }}</td>
                                            <td>
                                                <form action="{{ route('barang.kurang-stok', $tambahStok->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus stok ini?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="bx bx-trash me-1"></i> Hapus
                                                    </button>
                                                </form>
                                            </td>
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
    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#riwayat-stok').DataTable();
            });
        </script>
    @endpush
@endsection
