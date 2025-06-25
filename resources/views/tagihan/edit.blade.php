@extends('layouts.app')

@section('title', 'POS Karya Hutama Oxygen - Edit Tagihan')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="#">Tagihan</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('tagihan.index') }}">Daftar Tagihan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Tagihan</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between mb-3">
                <h5 class="mb-0">Edit Tagihan</h5>
                <div class="d-flex gap-2 justify-content-end mb-3">
                    <a href="{{ route('tagihan.show', $tagihan->id) }}" class="btn btn-primary">
                        <i class="bx bx-detail me-1"></i> Detail
                    </a>
                    <form action="{{ route('tagihan.destroy', $tagihan->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin hapus tagihan?')">
                            <i class="bx bx-trash me-1"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <form action="{{ route('tagihan.update', $tagihan->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="tagihan" class="form-label">Tagihan <span style="color: red;">*</span></label>
                            <input type="text" class="form-control @error('tagihan') is-invalid @enderror" id="tagihan"
                                name="tagihan" value="{{ old('tagihan', $tagihan->tagihan) }}" required>
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
                                        {{ old('supplier_id', $tagihan->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->nama_supplier }}</option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="nominal_tagihan" class="form-label">Nominal Tagihan <span
                                    style="color: red;">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('nominal_tagihan') is-invalid @enderror"
                                    id="nominal_tagihan" name="nominal_tagihan"
                                    value="{{ old('nominal_tagihan', $tagihan->nominal_tagihan) }}" required
                                    min="0">
                            </div>
                            @error('nominal_tagihan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="jatuhTempo_tagihan" class="form-label">Jatuh Tempo <span
                                    style="color: red;">*</span></label>
                            <input type="date" class="form-control @error('jatuhTempo_tagihan') is-invalid @enderror"
                                id="jatuhTempo_tagihan" name="jatuhTempo_tagihan"
                                value="{{ old('jatuhTempo_tagihan', $tagihan->jatuhTempo_tagihan->format('Y-m-d')) }}"
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
                                rows="3">{{ old('keterangan', $tagihan->keterangan) }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="img_nota" class="form-label">Upload Nota (Opsional)</label>
                            <input type="file" class="form-control @error('img_nota') is-invalid @enderror"
                                id="img_nota" name="img_nota" accept="image/*">
                            @if ($tagihan->img_nota)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $tagihan->img_nota) }}" alt="Nota"
                                        class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            @endif
                            @error('img_nota')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('tagihan.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#transaksiForm').on('submit', function(e) {
            let barangIds = [];
            let duplicate = false;

            $('.barang-select').each(function() {
                let val = $(this).val();
                if (val && barangIds.includes(val)) {
                    duplicate = true;
                    return false; // break loop
                }
                barangIds.push(val);
            });

            if (duplicate) {
                alert('Tidak boleh ada barang yang sama dalam satu transaksi!');
                e.preventDefault();
            }
        });
    </script>
@endsection
