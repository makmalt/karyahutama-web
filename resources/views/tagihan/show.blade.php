@extends('layouts.app')

@section('title', 'Detail Tagihan')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Tagihan</h5>
                    <a href="{{ route('tagihan.index') }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Nama Tagihan</dt>
                        <dd class="col-sm-9">{{ $tagihan->tagihan }}</dd>

                        <dt class="col-sm-3">Supplier</dt>
                        <dd class="col-sm-9">{{ $tagihan->supplier->nama_supplier ?? '-' }}</dd>

                        <dt class="col-sm-3">Nominal Tagihan</dt>
                        <dd class="col-sm-9">Rp {{ number_format($tagihan->nominal_tagihan, 2, ',', '.') }}</dd>

                        <dt class="col-sm-3">Jatuh Tempo</dt>
                        <dd class="col-sm-9">{{ $tagihan->jatuhTempo_tagihan }}</dd>

                        <dt class="col-sm-3">Status</dt>
                        <dd class="col-sm-9">
                            @if ($tagihan->status_lunas)
                                <span class="badge bg-success">Lunas</span>
                            @else
                                <span class="badge bg-warning text-dark">Belum Lunas</span>
                            @endif
                        </dd>

                        <dt class="col-sm-3">Keterangan</dt>
                        <dd class="col-sm-9">{{ $tagihan->keterangan ?? '-' }}</dd>

                        <dt class="col-sm-3">Nota</dt>
                        <dd class="col-sm-9">
                            @if ($tagihan->img_nota)
                                <img src="{{ asset('storage/' . $tagihan->img_nota) }}" alt="Nota" class="img-thumbnail"
                                    style="max-width: 300px;">
                            @else
                                <span class="text-muted">Tidak ada nota</span>
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
