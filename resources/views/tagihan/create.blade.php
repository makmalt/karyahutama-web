@extends('layouts.app')

@section('title', 'POS Karya Hutama Oxygen - Tambah Tagihan')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('tagihan.index') }}">Daftar Tagihan</a></li>
                    <li class="breadcrumb-item active">Tambah Tagihan</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between mb-3">
                <h5 class="mb-0">Tambah Tagihan</h5>
                <a href="{{ route('tagihan.index') }}" class="btn btn-secondary">
                    <i class="bx bx-arrow-back"></i> Kembali
                </a>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('tagihan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="tagihan" class="form-label">Tagihan <span style="color: red;">*</span></label>
                            <input type="text" class="form-control @error('tagihan') is-invalid @enderror" id="tagihan"
                                name="tagihan" value="{{ old('tagihan') }}" required>
                            @error('tagihan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="supplier_id" class="form-label">Supplier <span style="color: red;">*</span></label>
                            <select class="form-select @error('supplier_id') is-invalid @enderror" id="supplier_id"
                                name="supplier_id" required>
                                <option value="">Pilih Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->nama_supplier }}</option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="nominal_tagihan" class="form-label">Nominal Tagihan<span
                                    style="color: red;">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('nominal_tagihan') is-invalid @enderror"
                                    id="nominal_tagihan" name="nominal_tagihan" value="{{ old('nominal_tagihan') }}"
                                    required min="0">
                            </div>
                            @error('nominal_tagihan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="jatuhTempo_tagihan" class="form-label">Jatuh Tempo<span
                                    style="color: red;">*</span></label>
                            <input type="date" class="form-control @error('jatuhTempo_tagihan') is-invalid @enderror"
                                id="jatuhTempo_tagihan" name="jatuhTempo_tagihan" value="{{ old('jatuhTempo_tagihan') }}"
                                required>
                            @error('jatuhTempo_tagihan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="status_lunas" class="form-label">Status Lunas</label>

                            {{-- Hidden input untuk memastikan nilai 0 dikirim kalau tidak dicentang --}}
                            <input type="hidden" name="status_lunas" value="0">

                            <div class="form-check">
                                <input class="form-check-input @error('status_lunas') is-invalid @enderror" type="checkbox"
                                    id="status_lunas" name="status_lunas" value="1"
                                    {{ old('status_lunas', $model->status_lunas ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="status_lunas">Lunas</label>
                            </div>

                            @error('status_lunas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan"
                                rows="3">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="img_nota" class="form-label">Upload Nota (Opsional)</label>
                            <input type="file" class="form-control @error('img_nota') is-invalid @enderror"
                                id="img_nota" name="img_nota" accept="image/*">
                            @error('img_nota')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('tagihan.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
