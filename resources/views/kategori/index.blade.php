@extends('layouts.app')

@section('title', 'Daftar Kategori')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3">
                    <li class="breadcrumb-item"><a href="#">Kategori</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Daftar Kategori</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between mb-3">
                <h5 class="mb-0">Daftar Kategori</h5>
                <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createKategoriModal">
                    <i class="bx bx-plus"></i> Tambah Kategori
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table" id="kategori-table">
                            <thead>
                                <tr class="justify-content-between">
                                    <th class="px-4 py-3">No</th>
                                    <th class="px-4 py-3">Nama Kategori</th>
                                    <th class="px-4 py-3">Jumlah Barang</th>
                                    <th class="px-4 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @forelse($kategoris as $kategori)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $kategori->nama_kategori }}</td>
                                        <td>{{ $kategori->barang->count() }}</td>
                                        <td>
                                            <div class="d-flex justify-content-start gap-2">
                                                <a class="btn btn-warning btn-sm" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#editKategoriModal{{ $kategori->id }}">
                                                    <i class="bx bx-edit-alt me-1"></i> Edit
                                                </a>
                                                <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                                        <i class="bx bx-trash me-1"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada data kategori</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Create Modal -->
    <div class="modal fade" id="createKategoriModal" tabindex="-1" aria-labelledby="createKategoriModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('kategori.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createKategoriModalLabel">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_kategori" class="form-label">Nama Kategori <span
                                style="color: red;">*</span></label>
                        <input type="text" class="form-control @error('nama_kategori') is-invalid @enderror"
                            id="nama_kategori" name="nama_kategori" value="{{ old('nama_kategori') }}" required>
                        @error('nama_kategori')
                            <div class="invalid-feedback">
                                {{-- Ganti pesan default dengan pesan Indonesia --}}
                                @if (str_contains($message, 'required'))
                                    Nama kategori wajib diisi.
                                @elseif (str_contains($message, 'unique'))
                                    Nama kategori sudah digunakan, silakan masukkan nama lain.
                                @else
                                    {{ $message }}
                                @endif
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- edit modal -->
    @foreach ($kategoris as $kategori)
        <div class="modal fade" id="editKategoriModal{{ $kategori->id }}" tabindex="-1"
            aria-labelledby="editKategoriModalLabel{{ $kategori->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('kategori.update', $kategori->id) }}" method="POST" class="modal-content">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editKategoriModalLabel{{ $kategori->id }}">
                            Edit Kategori</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_kategori{{ $kategori->id }}" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="nama_kategori{{ $kategori->id }}"
                                name="nama_kategori" value="{{ $kategori->nama_kategori }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#kategori-table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                }
            });
        });
    </script>
    @if ($errors->has('nama_kategori'))
        <script>
            const createModal = new bootstrap.Modal(document.getElementById('createKategoriModal'));
            createModal.show();
        </script>
    @endif

@endpush
