@extends('layouts.app')

@section('title', 'Edit Transaksi')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="#">Transaksi</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Transaksi</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between mb-3">
                <h5 class="mb-0">Edit Transaksi</h5>
                <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">
                    <i class="bx bx-arrow-back"></i> Kembali
                </a>
            </div>
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    <form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST" id="transaksiForm">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="no_transaksi" class="form-label">No Transaksi</label>
                            <input type="text" class="form-control @error('no_transaksi') is-invalid @enderror"
                                id="no_transaksi" name="no_transaksi"
                                value="{{ old('no_transaksi', $transaksi->no_transaksi) }}" required>
                            @error('no_transaksi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="tgl_transaksi" class="form-label">Tanggal Transaksi</label>
                            <input type="datetime-local" class="form-control @error('tgl_transaksi') is-invalid @enderror"
                                id="tgl_transaksi" name="tgl_transaksi"
                                value="{{ old('tgl_transaksi', $transaksi->tgl_transaksi->format('Y-m-d\TH:i')) }}"
                                required>
                            @error('tgl_transaksi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <hr>
                        <h6>Daftar Barang</h6>
                        <div class="table-responsive mb-3">
                            <table class="table align-middle" id="barangTable">
                                <thead>
                                    <tr>
                                        <th>Barang</th>
                                        <th>Harga</th>
                                        <th>Qty</th>
                                        <th>Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transaksi->barangTransaksi as $bt)
                                        <tr>
                                            <td>
                                                <select name="barang_id[]" class="form-select barang-select" required>
                                                    <option value="">Pilih Barang</option>
                                                    @foreach ($barangs as $barang)
                                                        <option value="{{ $barang->id }}"
                                                            data-harga="{{ $barang->harga }}"
                                                            {{ $bt->barang_id == $barang->id ? 'selected' : '' }}>
                                                            {{ $barang->nama_barang }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="number" name="harga[]" class="form-control harga-input"
                                                    min="0" value="{{ $bt->harga }}" required></td>
                                            <td><input type="number" name="jumlah[]" class="form-control qty-input"
                                                    min="1" value="{{ $bt->jumlah }}" required></td>
                                            <td><input type="number" name="subtotal[]" class="form-control subtotal-input"
                                                    value="{{ $bt->subtotal }}" readonly></td>
                                            <td><button type="button"
                                                    class="btn btn-danger btn-sm remove-row">Hapus</button></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-success btn-sm" id="addRow">Tambah Barang</button>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label">Grand Total</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="grand_total" name="grand_total"
                                    value="{{ old('grand_total', $transaksi->grand_total) }}" readonly>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label">Uang Pembayaran</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="uang_pembayaran" name="uang_pembayaran"
                                    value="{{ old('uang_pembayaran', $transaksi->uang_pembayaran) }}" min="0">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label">Uang Kembalian</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="uang_kembalian" name="uang_kembalian"
                                    value="{{ old('uang_kembalian', $transaksi->uang_kembalian) }}" readonly>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            function updateSubtotal(row) {
                const harga = parseFloat(row.find('.harga-input').val()) || 0;
                const qty = parseInt(row.find('.qty-input').val()) || 0;
                row.find('.subtotal-input').val(harga * qty);
            }

            function updateGrandTotal() {
                let total = 0;
                $('#barangTable tbody tr').each(function() {
                    total += parseFloat($(this).find('.subtotal-input').val()) || 0;
                });
                $('#grand_total').val(total);
                const bayar = parseFloat($('#uang_pembayaran').val()) || 0;
                $('#uang_kembalian').val(bayar - total >= 0 ? bayar - total : 0);
            }
            $(document).on('input', '.harga-input, .qty-input', function() {
                const row = $(this).closest('tr');
                updateSubtotal(row);
                updateGrandTotal();
            });
            $('#addRow').click(function() {
                const row = $('#barangTable tbody tr:first').clone();
                row.find('select').val('');
                row.find('input').val('');
                $('#barangTable tbody').append(row);
            });
            $(document).on('click', '.remove-row', function() {
                if ($('#barangTable tbody tr').length > 1) {
                    $(this).closest('tr').remove();
                    updateGrandTotal();
                }
            });
            $(document).on('change', '.barang-select', function() {
                const harga = $(this).find('option:selected').data('harga') || 0;
                const row = $(this).closest('tr');
                row.find('.harga-input').val(harga).trigger('input');
            });
            $('#uang_pembayaran').on('input', updateGrandTotal);
            $(document).ready(function() {
                $('#barangTable tbody tr').each(function() {
                    updateSubtotal($(this));
                });
                updateGrandTotal();
            });
        </script>
    @endpush
@endsection
