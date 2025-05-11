@extends('layouts.app')

@section('title', 'Daftar Supplier')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="#">Supplier</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Daftar Supplier</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between mb-3">
                <h5 class="mb-0">Daftar Supplier</h5>
                <a href="{{ route('supplier.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Tambah Supplier
                </a>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-end">
                    <form action="{{ route('supplier.index') }}" method="GET" class="d-flex justify-content-end mb-3"
                        style="max-width: 350px;">
                        <input type="text" name="q" class="form-control form-control-sm me-2"
                            placeholder="Cari supplier..." value="{{ request('q') }}">
                        <button class="btn btn-outline-primary btn-sm" type="submit">
                            <i class="bx bx-search"></i>
                        </button>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead>
                                <tr class="justify-content-between">
                                    <th class="px-4 py-3">No</th>
                                    <th class="px-4 py-3">Nama Supplier</th>
                                    <th class="px-4 py-3">Alamat</th>
                                    <th class="px-4 py-3">Kontak</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @forelse($suppliers as $supplier)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $supplier->nama_supplier }}</td>
                                        <td>{{ $supplier->alamat }}</td>
                                        <td>{{ $supplier->kontak }}</td>
                                        <td>
                                            <div class="d-flex justify-content-end gap-2 text-start">
                                                <a class="btn btn-info btn-sm"
                                                    href="{{ route('supplier.show', $supplier->id) }}">
                                                    <i class="bx bx-detail me-1"></i> Detail
                                                </a>
                                                <a class="btn btn-primary btn-sm"
                                                    href="{{ route('supplier.edit', $supplier->id) }}">
                                                    <i class="bx bx-edit-alt me-1"></i> Edit
                                                </a>
                                                <form action="{{ route('supplier.destroy', $supplier->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus supplier ini?')">
                                                        <i class="bx bx-trash me-1"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data supplier</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
