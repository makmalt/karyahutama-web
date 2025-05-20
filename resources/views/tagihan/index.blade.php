@extends('layouts.app')

@section('title', 'Daftar Tagihan')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="#">Tagihan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Daftar Tagihan</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between">
                <h5 class="mb-0">Daftar Tagihan</h5>
                <a href="{{ route('tagihan.create') }}" class="btn btn-primary mb-3">
                    <i class="bx bx-plus"></i> Tambah Tagihan
                </a>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="mb-3 d-flex justify-content-center gap-2">
                        <a href="{{ route('tagihan.index') }}"
                            class="btn btn-outline-secondary btn-sm {{ request('status') == null ? 'active' : '' }}">Semua</a>
                        <a href="{{ route('tagihan.index', ['status' => 'lunas']) }}"
                            class="btn btn-outline-success btn-sm {{ request('status') == 'lunas' ? 'active' : '' }}">Lunas</a>
                        <a href="{{ route('tagihan.index', ['status' => 'belum']) }}"
                            class="btn btn-outline-warning btn-sm {{ request('status') == 'belum' ? 'active' : '' }}">Belum
                            Lunas</a>
                    </div>
                    <div class="table-responsive text-nowrap">
                        <table class="table" id="tagihan-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tagihan</th>
                                    <th>Supplier</th>
                                    <th>Nominal</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tagihans as $tagihan)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $tagihan->tagihan }}</td>
                                        <td>{{ $tagihan->supplier->nama_supplier ?? '-' }}</td>
                                        <td>Rp {{ number_format($tagihan->nominal_tagihan, 2, ',', '.') }}</td>
                                        <td>{{ $tagihan->jatuhTempo_tagihan->format('d-m-Y') }}</td>
                                        <td>
                                            @if ($tagihan->status_lunas)
                                                <span class="badge bg-success">Lunas</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Belum Lunas</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('tagihan.show', $tagihan->id) }}"
                                                class="btn btn-info btn-sm">
                                                <i class="bx bx-detail me-1"></i> Lihat
                                            </a>
                                            <form action="{{ route('tagihan.destroy', $tagihan->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Yakin hapus tagihan?')">
                                                    <i class="bx bx-trash me-1"></i> Hapus
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#perbaruiStatusModal{{ $tagihan->id }}">
                                                <i class="bx bx-refresh"></i> Perbarui
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada tagihan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Tempatkan seluruh modal di luar .table-responsive --}}
                    @foreach ($tagihans as $tagihan)
                        <!-- Modal Perbarui Status -->
                        <div class="modal fade" id="perbaruiStatusModal{{ $tagihan->id }}" tabindex="-1"
                            aria-labelledby="perbaruiStatusModalLabel{{ $tagihan->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('tagihan.updateStatus', $tagihan->id) }}" method="POST"
                                    class="modal-content" id="updateStatusForm{{ $tagihan->id }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="perbaruiStatusModalLabel{{ $tagihan->id }}">
                                            Perbarui Status Tagihan
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
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
                                                <span class="badge bg-success">LUNAS</span>
                                            @else
                                                <span class="badge bg-warning text-dark">BELUM LUNAS</span>
                                            @endif
                                        </div>

                                        <div class="form-check">
                                            <input type="hidden" name="status_lunas" value="0">
                                            <input class="form-check-input" type="checkbox" name="status_lunas"
                                                value="1" {{ $tagihan->status_lunas ? 'checked' : '' }}
                                                id="status_lunas{{ $tagihan->id }}">
                                            <label class="form-check-label" for="status_lunas{{ $tagihan->id }}">
                                                Tandai sebagai Lunas
                                            </label>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary"
                                            id="simpanStatusBtn{{ $tagihan->id }}">
                                            <i class="bx bx-save me-1"></i> Simpan Perubahan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach

                </div>
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
