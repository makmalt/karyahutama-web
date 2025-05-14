@extends('layouts.app')

@section('title', 'Detail Transaksi')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="#">Transaksi</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail Transaksi</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between mb-3">
                <h5 class="mb-0">Detail Transaksi</h5>
                <a href="{{ route('transaksi.edit', $transaksi->id) }}" class="btn btn-warning">
                    <i class="bx bx-pencil"></i> Edit
                </a>
            </div>
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">No Transaksi</dt>
                        <dd class="col-sm-9">{{ $transaksi->no_transaksi }}</dd>
                        <dt class="col-sm-3">Tanggal Transaksi</dt>
                        <dd class="col-sm-9">{{ $transaksi->tgl_transaksi->format('d-m-Y H:i') }}</dd>
                    </dl>
                    <h6>Daftar Barang</h6>
                    <div class="table-responsive mb-3">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Barang</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaksi->barangTransaksi as $bt)
                                    <tr>
                                        <td>{{ $bt->barang->nama_barang ?? '-' }}</td>
                                        <td>Rp {{ number_format($bt->harga_barang, 2, ',', '.') }}</td>
                                        <td>{{ $bt->quantity }}</td>
                                        <td>Rp {{ number_format($bt->total_harga, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <dl class="row d-flex justify-end">
                        <dt class="col-sm-3 ">Grand Total</dt>
                        <dd class="col-sm-9">Rp {{ number_format($transaksi->grand_total, 2, ',', '.') }}</dd>
                        <dt class="col-sm-3">Uang Pembayaran</dt>
                        <dd class="col-sm-9">Rp {{ number_format($transaksi->uang_pembayaran, 2, ',', '.') }}</dd>
                        <dt class="col-sm-3">Uang Kembalian</dt>
                        <dd class="col-sm-9">Rp {{ number_format($transaksi->uang_kembalian, 2, ',', '.') }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
