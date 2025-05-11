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
                <div class="card-header d-flex justify-content-end">

                    <form action="{{ route('tagihan.index') }}" method="GET" class="d-flex justify-content-end"
                        style="max-width: 350px;">
                        <input type="text" name="q" class="form-control form-control-sm me-2"
                            placeholder="Cari tagihan..." value="{{ request('q') }}">
                        @if (request('status'))
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                        <button class="btn btn-outline-primary btn-sm" type="submit">
                            <i class="bx bx-search"></i>
                        </button>
                    </form>
                </div>
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
                        <table class="table">
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
                                        <td>{{ $tagihan->jatuhTempo_tagihan }}</td>
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
                                            <a href="{{ route('tagihan.edit', $tagihan->id) }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
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
                                    <!-- Modal Perbarui Status -->
                                    <div class="modal fade" id="perbaruiStatusModal{{ $tagihan->id }}" tabindex="-1"
                                        aria-labelledby="perbaruiStatusModalLabel{{ $tagihan->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form action="{{ route('tagihan.update', $tagihan->id) }}" method="POST"
                                                class="modal-content">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="perbaruiStatusModalLabel{{ $tagihan->id }}">Perbarui Status
                                                        Lunas</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-check">
                                                        <input type="hidden" name="status_lunas" value="0">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="status_lunas{{ $tagihan->id }}" name="status_lunas"
                                                            value="1" {{ $tagihan->status_lunas ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="status_lunas{{ $tagihan->id }}">Lunas</label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data tagihan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($tagihans->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $tagihans->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
