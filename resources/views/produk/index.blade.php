@extends('layouts.app')

@section('content')
<div class="container text-light mt-5">
    <div class="row justify-content-center text-submit">
        <div class="col-md-9">
            <div class="card bg-dark">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Manajemen Produk</h3>
                    <!-- Tombol untuk memicu modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProdukModal">
                        <i class="bi bi-plus-circle"></i> Tambah Produk
                    </button>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> Ada beberapa masalah dengan input Anda.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="table-responsive text-center">
                        <table class="table table-dark table-striped table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Nama Produk</th>
                                    <th scope="col">Kode Produk</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($produks as $produk)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $produk->nama }}</td>
                                        <td>{{ $produk->kode }}</td>
                                        {{-- Tombol Aksi --}}
                                        <td class="d-flex justify-content-center gap-2">
                                            {{-- Tombol Edit (Modal Trigger) --}}
                                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editProdukModal-{{ $produk->id }}">
                                                <i class="fa-solid fa-pen-to-square"></i> Edit
                                            </button>
                                            {{-- Form Hapus --}}
                                            <form class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');" action="{{ route('produk.destroy', $produk->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i> Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            <div class="alert alert-info mb-0">
                                                Belum ada data produk.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Modal Edit Produk (dipindahkan ke luar tabel) --}}
                    @foreach ($produks as $produk)
                        <div class="modal fade" id="editProdukModal-{{ $produk->id }}" tabindex="-1" aria-labelledby="editProdukModalLabel-{{ $produk->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content bg-dark text-light">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editProdukModalLabel-{{ $produk->id }}">Edit Produk</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('produk.update', $produk->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-body text-start">
                                            <div class="mb-3">
                                                <label for="nama-{{ $produk->id }}" class="form-label">Nama Produk</label>
                                                <input type="text" class="form-control rounded-md" id="nama-{{ $produk->id }}" name="nama" value="{{ old('nama', $produk->nama) }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="kode-{{ $produk->id }}" class="form-label">Kode Produk</label>
                                                <input type="text" class="form-control rounded-md" id="kode-{{ $produk->id }}" name="kode" value="{{ old('kode', $produk->kode) }}" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Produk -->
<div class="modal fade" id="createProdukModal" tabindex="-1" aria-labelledby="createProdukModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header">
                <h5 class="modal-title" id="createProdukModalLabel">Tambah Produk Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('produk.store') }}" method="POST">
                @csrf
                <div class="modal-body ">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control rounded-md" id="nama" name="nama" value="{{ old('nama') }}" required>
                    </div>
                    <div class="mb-3 ">
                        <label for="kode" class="form-label">Kode Produk</label>
                        <input type="text" class="form-control rounded-md" id="kode" name="kode" value="{{ old('kode') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Jika ada error validasi, buka kembali modal secara otomatis
    @if ($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        var createModal = new bootstrap.Modal(document.getElementById('createProdukModal'));
        createModal.show();
    });
    @endif
</script>
@endpush
