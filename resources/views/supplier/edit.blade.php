@extends('layouts.app')

@section('title', 'POS Karya Hutama Oxygen - Edit Supplier')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="#">Supplier</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('supplier.index') }}">Daftar Supplier</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Supplier</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between mb-3">
                <h5 class="mb-0">Edit Supplier</h5>
                <div class="d-flex justify-end gap-2"><a href="{{ route('supplier.show', $supplier->id) }}"
                        class="btn btn-primary">
                        <i class="bx bx-detail me-1"></i> Detail
                    </a>
                    <form action="{{ route('supplier.destroy', $supplier->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin hapus supplier?')">
                            <i class="bx bx-trash me-1"></i> Hapus
                        </button>
                    </form>
                </div>

            </div>
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    <form action="{{ route('supplier.update', $supplier->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="nama_supplier" class="form-label">Nama Supplier <span
                                    style="color: red;">*</span></label>
                            <input type="text" class="form-control @error('nama_supplier') is-invalid @enderror"
                                id="nama_supplier" name="nama_supplier"
                                value="{{ old('nama_supplier', $supplier->nama_supplier) }}" required>
                            @error('nama_supplier')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori <span style="color: red;">*</span></label>
                            <select name="kategori_id" id="kategori_id" class="form-control" required>
                                <option value="">
                                    {{ old('kategori_id', optional($supplier->kategori)->nama_kategori ?? 'Pilih Kategori') }}
                                </option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}"
                                        {{ old('kategori_id', $supplier->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat <span style="color: red;">*</span></label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3"
                                required>{{ old('alamat', $supplier->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="kontak" class="form-label">Kontak <span style="color: red;">*</span></label>
                            <input type="text" class="form-control @error('kontak') is-invalid @enderror" id="kontak"
                                name="kontak" value="{{ old('kontak', $supplier->kontak) }}" required>
                            @error('kontak')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
