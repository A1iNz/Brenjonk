@extends('layouts.app')

@section('content')
<div class="container text-light mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10"> {{-- Kartu Utama Manajemen Produk --}}
            <div class="card bg-dark border-secondary shadow-lg"> <div class="card-header bg-secondary d-flex justify-content-between align-items-center"> <h3 class="mb-0 text-white">📦 Manajemen Produk Komoditas</h3>
                    
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createProdukModal">
                        <i class="fa-solid fa-seedling me-1"></i> Tambah Produk Baru
                    </button>
                </div>
                
                <div class="card-body">
                    
                    {{-- AREA ALERT DAN ERROR --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Berhasil!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Gagal!</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> Ada beberapa masalah input.
                            <ul class="mt-2 mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    {{-- AKHIR AREA ALERT --}}

                    <div class="table-responsive mt-3">
                        <table class="table table-dark table-striped table-hover align-middle text-center" id="produkTable"> <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nama Komoditas</th>
                                    <th scope="col">Kode Produk</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($produks as $produk)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $produk->nama }}</td>
                                        <td><span class="badge bg-info text-dark">{{ $produk->kode }}</span></td>
                                        
                                        {{-- Tombol Aksi --}}
                                        <td class="d-flex justify-content-center gap-2">
                                            {{-- Tombol Edit (Modal Trigger) --}}
                                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editProdukModal-{{ $produk->id }}">
                                                <i class="fa-solid fa-pencil"></i>
                                            </button>
                                            
                                            {{-- Form Hapus --}}
                                            <form class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk {{ $produk->nama }}? Data yang terkait mungkin ikut terpengaruh!');" action="{{ route('produk.destroy', $produk->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash-can"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            <div class="alert alert-secondary mb-0">
                                                Tidak ada data komoditas yang terdaftar. Silakan tambahkan produk baru.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            {{-- Modal Edit Produk (Loop di luar card-body, seperti yang sudah Anda lakukan) --}}
            @foreach ($produks as $produk)
                <div class="modal fade" id="editProdukModal-{{ $produk->id }}" tabindex="-1" aria-labelledby="editProdukModalLabel-{{ $produk->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content bg-dark text-light border-warning"> <div class="modal-header bg-warning text-dark">
                                <h5 class="modal-title" id="editProdukModalLabel-{{ $produk->id }}">Edit Produk: {{ $produk->nama }}</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('produk.update', $produk->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="modal-body text-start">
                                    <div class="mb-3">
                                        <label for="nama-{{ $produk->id }}" class="form-label">Nama Produk</label>
                                        <input type="text" class="form-control bg-secondary text-light border-0" id="nama-{{ $produk->id }}" name="nama" value="{{ old('nama', $produk->nama) }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="kode-{{ $produk->id }}" class="form-label">Kode Produk</label>
                                        <input type="text" class="form-control bg-secondary text-light border-0" id="kode-{{ $produk->id }}" name="kode" value="{{ old('kode', $produk->kode) }}" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="modal fade" id="createProdukModal" tabindex="-1" aria-labelledby="createProdukModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-light border-primary"> <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createProdukModalLabel">➕ Tambah Produk Komoditas Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('produk.store') }}" method="POST">
                @csrf
                <div class="modal-body text-start">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Komoditas/Produk</label>
                        <input type="text" class="form-control bg-secondary text-light border-0" id="nama" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: Beras, Cabai Merah, Jagung">
                    </div>
                    <div class="mb-3">
                        <label for="kode" class="form-label">Kode Produk (Singkatan Unik)</label>
                        <input type="text" class="form-control bg-secondary text-light border-0" id="kode" name="kode" value="{{ old('kode') }}" required placeholder="Contoh: BR-01, CM-A, JG-1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk me-1"></i> Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.datatables.net/2.3.5/js/dataTables.js"></script>
<script>
    // Inisialisasi DataTables untuk tampilan tabel yang lebih baik
    $(document).ready(function() {
        $('#produkTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true
        });
    });
    
    // Jika ada error validasi, buka kembali modal secara otomatis
    @if ($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        var createModal = new bootstrap.Modal(document.getElementById('createProdukModal'));
        
        // Cek apakah error terkait dengan modal TAMBAH
        // Ini adalah asumsi sederhana, idealnya menggunakan pengecekan yang lebih spesifik
        @if (!empty(old('nama')) && !empty(old('kode')))
            createModal.show();
        @endif
    });
    @endif
</script>
@endpush