@extends('layouts.app')

@section('title', 'POS Karya Hutama Oxygen - Detail Tagihan')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('tagihan.index') }}">Tagihan</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('tagihan.index') }}">Daftar Tagihan</a></li>
                    <li class="breadcrumb-item active">Detail Tagihan</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between mb-3">
                <h5 class="mb-0">Detail Tagihan</h5>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#perbaruiStatusModal{{ $tagihan->id }}">
                        <i class="bx bx-refresh"></i> Perbarui
                    </button>
                    <a href="{{ route('tagihan.edit', $tagihan->id) }}" class="btn btn-warning">
                        <i class="bx bx-edit-alt"></i> Edit
                    </a>
                </div>
            </div>
            <div class="card">
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
        <div class="modal fade" id="perbaruiStatusModal{{ $tagihan->id }}" tabindex="-1"
            aria-labelledby="perbaruiStatusModalLabel{{ $tagihan->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('tagihan.updateStatus', $tagihan->id) }}" method="POST" class="modal-content"
                    id="updateStatusForm{{ $tagihan->id }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="perbaruiStatusModalLabel{{ $tagihan->id }}">
                            Perbarui Status Tagihan
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Detail Tagihan</label>
                            <div class="row">
                                <div class="col-6">
                                    <p class="mb-1"><strong>Nominal:</strong> Rp
                                        {{ number_format($tagihan->nominal_tagihan, 2, ',', '.') }}</p>
                                    <p class="mb-1"><strong>Jatuh Tempo:</strong>
                                        {{ $tagihan->jatuhTempo_tagihan->format('d-m-Y') }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-1"><strong>Supplier:</strong>
                                        {{ $tagihan->supplier->nama_supplier ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info" role="alert">
                            <i class="bx bx-info-circle me-2"></i>
                            Status saat ini:
                            @if ($tagihan->status_lunas)
                                <span class="badge bg-success">Lunas</span>
                            @else
                                <span class="badge bg-warning text-dark">Belum Lunas</span>
                            @endif
                        </div>

                        <div class="form-check">
                            <input type="hidden" name="status_lunas" value="0">
                            <input class="form-check-input" type="checkbox" name="status_lunas" value="1"
                                {{ $tagihan->status_lunas ? 'checked' : '' }}>
                            <label class="form-check-label" for="status_lunas{{ $tagihan->id }}">
                                Tandai sebagai Lunas
                            </label>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="simpanStatusBtn{{ $tagihan->id }}">
                                <i class="bx bx-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#tagihan-table').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                    }
                });
            });
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('updateStatusForm{{ $tagihan->id }}');
                const submitBtn = document.getElementById('simpanStatusBtn{{ $tagihan->id }}');

                form.addEventListener('submit', function(e) {
                    submitBtn.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menyimpan...';
                    submitBtn.disabled = true;
                });
            });
        </script>
    @endpush
@endsection
