@extends('layouts.app')

@section('title', 'POS Karya Hutama Oxygen - Tambah Kategori')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="#">Kategori</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('kategori.index') }}">Daftar Kategori</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah Kategori</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between mb-3">
                <h5 class="mb-0">Tambah Kategori</h5>
            </div>
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    <form action="{{ route('kategori.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nama_kategori" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control @error('nama_kategori') is-invalid @enderror"
                                id="nama_kategori" name="nama_kategori" value="{{ old('nama_kategori') }}" required>
                            @error('nama_kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
