@extends('layouts.app')

@section('title', 'Edit Barang')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="#">Barang</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('barang.index') }}">Daftar Barang</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Barang</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between mb-3">
                <h5 class="mb-0">Edit Barang</h5>
                <div class="d-flex justify-end gap-2">
                    <a href="{{ route('barang.show', $barang->id) }}" class="btn btn-primary">
                        <i class="bx bx-detail me-1"></i> Detail
                    </a>
                    <a href="{{ route('barang.destroy', $barang->id) }}" class="btn btn-danger">
                        <i class="bx bx-trash me-1"></i> Hapus
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    <form action="{{ route('barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="nama_barang" class="form-label">Nama Barang</label>
                                    <input type="text" class="form-control @error('nama_barang') is-invalid @enderror"
                                        id="nama_barang" name="nama_barang"
                                        value="{{ old('nama_barang', $barang->nama_barang) }}" required>
                                    @error('nama_barang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="kategori_id" class="form-label">Kategori</label>
                                    <select class="form-select @error('kategori_id') is-invalid @enderror" id="kategori_id"
                                        name="kategori_id" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($kategoris as $kategori)
                                            <option value="{{ $kategori->id }}"
                                                {{ old('kategori_id', $barang->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                                {{ $kategori->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kategori_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="supplier_id" class="form-label">Supplier</label>
                                    <select class="form-select @error('supplier_id') is-invalid @enderror" id="supplier_id"
                                        name="supplier_id" required>
                                        <option value="">Pilih Supplier</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}"
                                                {{ old('supplier_id', $barang->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->nama_supplier }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('supplier_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="hargaBeli" class="form-label">Harga Beli</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control @error('hargaBeli') is-invalid @enderror"
                                            id="hargaBeli" name="hargaBeli"
                                            value="{{ old('hargaBeli', $barang->hargaBeli) }}" required min="0">
                                    </div>
                                    @error('hargaBeli')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="stok_tersedia" class="form-label">Stok</label>
                                    <input type="number" class="form-control @error('stok_tersedia') is-invalid @enderror"
                                        id="stok_tersedia" name="stok_tersedia"
                                        value="{{ old('stok_tersedia', $barang->stok_tersedia) }}" required min="0">
                                    @error('stok_tersedia')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="harga" class="form-label">Harga</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control @error('harga') is-invalid @enderror"
                                            id="harga" name="harga" value="{{ old('harga', $barang->harga) }}"
                                            required min="0">
                                    </div>
                                    @error('harga')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi</label>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Foto Barang</label>
                                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                                            id="image" name="image" accept="image/*">
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="mt-2">
                                            @if ($barang->image)
                                                <img id="preview" src="{{ asset('storage/' . $barang->image) }}"
                                                    alt="Preview" class="img-thumbnail" style="max-width: 100%;">
                                            @else
                                                <img id="preview" src="#" alt="Preview" class="img-thumbnail"
                                                    style="display: none; max-width: 100%;">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                        <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Preview image before upload
            document.getElementById('foto').addEventListener('change', function(e) {
                const preview = document.getElementById('preview');
                const file = e.target.files[0];
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }

                if (file) {
                    reader.readAsDataURL(file);
                }
            });
        </script>
    @endpush
@endsection
