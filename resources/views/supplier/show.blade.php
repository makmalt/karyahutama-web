@extends('layouts.app')

@section('title', 'Detail Supplier')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="#">Supplier</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('supplier.index') }}">Daftar Supplier</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail Supplier</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between mb-3">
                <h5 class="mb-0">Detail Supplier</h5>
                <div class="d-flex justify-end gap-2"><a href="{{ route('supplier.edit', $supplier->id) }}"
                        class="btn btn-primary">
                        <i class="bx bx-edit-alt me-1"></i> Edit
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="fw-semibold">Nama Supplier</h6>
                                <p class="mb-0">{{ $supplier->nama_supplier }}</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="fw-semibold">Alamat</h6>
                                <p class="mb-0">{{ $supplier->alamat }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="fw-semibold">Kontak</h6>
                                <p class="mb-0">{{ $supplier->kontak }}</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="fw-semibold">Tanggal Terdaftar</h6>
                                <p class="mb-0">{{ $supplier->created_at->format('d F Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
