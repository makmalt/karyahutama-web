@extends('layouts.app')

@section('title', 'POS Karya Hutama Oxygen - Daftar Barang')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="#">Barang</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Daftar Barang</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between mb-3">
                <h5 class="mb-0">Daftar Barang</h5>
                <a href="{{ route('barang.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Tambah Barang
                </a>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-end">
                    <form action="{{ route('barang.index') }}" method="GET" class="d-flex justify-content-end mb-3"
                        style="max-width: 450px;">
                        <input type="text" name="q" class="form-control form-control-sm me-2"
                            placeholder="Cari barang..." value="{{ request('q') }}">
                        <button class="btn btn-outline-primary btn-sm" type="submit">
                            <i class="bx bx-search"></i>
                        </button>
                    </form>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse ($barangs as $barang)
                            <div class="col-md-3 mb-4">
                                <div class="card h-100 p-2 position-relative">
                                    <div class="d-flex align-items-start">
                                        <div class="w-100">
                                            <div class="text-center mb-2">
                                                @if ($barang->image)
                                                    <img src="{{ asset('storage/' . $barang->image) }}"
                                                        alt="{{ $barang->nama_barang }}" class="img-fluid rounded"
                                                        style="width: 100%; aspect-ratio: 1/1; object-fit: cover;">
                                                @else
                                                    <div style="width: 100%; aspect-ratio: 1/1; overflow: hidden;">
                                                        <img src="{{ asset('assets/img/no-image.png') }}" alt="No Image"
                                                            style="width: 100%; height: 100%; object-fit: cover; border-radius: .25rem;">
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="text-center small text-muted mb-1">
                                                {!! $barang->sku_id ? e($barang->sku_id) : '<em>no sku_id</em>' !!}
                                            </div>
                                            <div class="text-center text-dark fw-bold mb-1">{{ $barang->nama_barang }}</div>
                                            <div class="text-center text-dark mb-2">
                                                Rp. {{ number_format($barang->harga, 2, ',', '.') }}
                                            </div>
                                            <div class="d-flex justify-content-center gap-2 mb-2">
                                                <a href="{{ route('barang.show', $barang->id) }}"
                                                    class="text-secondary text-decoration-none">
                                                    <i class="bx bx-show"></i> Lihat
                                                </a>
                                                <a href="{{ route('barang.edit', $barang->id) }}"
                                                    class="text-warning text-decoration-none">
                                                    <i class="bx bx-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('barang.destroy', $barang->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-link p-0 m-0 text-danger text-decoration-none"
                                                        style="box-shadow:none;"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">
                                                        <i class="bx bx-trash"></i> Hapus
                                                    </button>
                                                </form>
                                                <a href="#" class="text-success text-decoration-none"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#tambahStokModal{{ $barang->id }}">
                                                    <i class="bx bx-plus"></i> Tambah Stok
                                                </a>
                                            </div>
                                        </div>
                                    </div>
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
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('barang.tambah-stok', $barang->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="supplier_id{{ $barang->id }}"
                                                        class="form-label">Supplier</label>
                                                    <select class="form-select" id="supplier_id{{ $barang->id }}"
                                                        name="supplier_id">
                                                        <option value="">Pilih Supplier</option>
                                                        @foreach ($suppliers as $supplier)
                                                            <option value="{{ $supplier->id }}"
                                                                {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                                {{ $supplier->nama_supplier }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="tgl_masuk{{ $barang->id }}" class="form-label">Tanggal
                                                        Masuk <span style="color: red;">*</span></label>
                                                    <input type="date" class="form-control"
                                                        id="tgl_masuk{{ $barang->id }}" name="tgl_masuk" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="harga_satuan{{ $barang->id }}" class="form-label">Harga
                                                        Satuan</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control"
                                                            id="harga_satuan{{ $barang->id }}" name="harga_satuan"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="kuantitas{{ $barang->id }}" class="form-label">Jumlah
                                                        Stok</label>
                                                    <input type="number" class="form-control"
                                                        id="kuantitas{{ $barang->id }}" name="kuantitas"
                                                        min="1" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="total_harga{{ $barang->id }}" class="form-label">Total
                                                        Harga</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control"
                                                            id="total_harga{{ $barang->id }}" name="total_harga"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success">Tambah</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info text-center">
                                    Tidak ada data barang.
                                </div>
                            </div>
                        @endforelse
                    </div>

                    @if ($barangs->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $barangs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @foreach ($barangs as $barang)
                const hargaSatuanInput{{ $barang->id }} = document.getElementById(
                    'harga_satuan{{ $barang->id }}');
                const kuantitasInput{{ $barang->id }} = document.getElementById('kuantitas{{ $barang->id }}');
                const totalHargaInput{{ $barang->id }} = document.getElementById(
                    'total_harga{{ $barang->id }}');

                function updateTotalHarga{{ $barang->id }}() {
                    const harga = parseInt(hargaSatuanInput{{ $barang->id }}.value) || 0;
                    const qty = parseInt(kuantitasInput{{ $barang->id }}.value) || 0;
                    totalHargaInput{{ $barang->id }}.value = harga * qty;
                }

                hargaSatuanInput{{ $barang->id }}.addEventListener('input',
                    updateTotalHarga{{ $barang->id }});
                kuantitasInput{{ $barang->id }}.addEventListener('input', updateTotalHarga{{ $barang->id }});
            @endforeach
        });
    </script>
@endpush
