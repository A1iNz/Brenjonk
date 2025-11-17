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
                                        <td>
                                            <form onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');" action="{{ route('produk.destroy', $produk->id) }}" method="POST">
                                                <a href="{{ route('produk.edit', $produk->id) }}" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen-to-square"></i>Edit</a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i>Hapus</button>
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

{{-- Pastikan layout app.blade.php Anda memiliki @stack('scripts') sebelum tag </body> --}}
{{-- Contoh di layouts/app.blade.php: --}}
{{-- ... --}}
{{-- <script src="..."></script> --}}
{{-- @stack('scripts') --}}
{{-- </body> --}}