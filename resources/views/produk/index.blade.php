@extends('layouts.app')

@section('content')
<div class="container text-dark mt-5">
    
    {{-- Header Halaman --}}
    <div class="mb-4 p-3 bg-white rounded-md border border-primary shadow-sm">
        <h1 class="mb-0 fs-3 text-white">Manajemen Produk Komoditas 📦</h1>
        <p class="text-white mb-0">Daftar, tambah, edit, dan hapus komoditas yang tersedia di sistem.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10"> 
            
            {{-- Kartu Utama Manajemen Produk --}}
            {{-- Card menggunakan bg-white dan border-primary --}}
            <div class="card bg-white border-primary shadow-lg"> 
                
                {{-- Card Header menggunakan Dark Forest Green (bg-success) --}}
                <div class="card-header bg-success d-flex justify-content-between align-items-center text-white"> 
                    <h3 class="ms-1 mb-0"><i class="fa-solid fa-book-open me-2"></i> Daftar Produk</h3>
                    
                    {{-- Tombol Tambah Produk menggunakan Fresh Green (btn-primary) --}}
                    <button type="button" class="btn btn-primary text-white" data-bs-toggle="modal" data-bs-target="#createProdukModal">
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
                            <strong>Whoops!</strong> Ada beberapa masalah input.<br>
                            <ul class="mt-2 mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    {{-- AKHIR AREA ALERT --}}

                    <div class="table-responsive mt-3">
                        {{-- Tabel diubah ke light theme dan text-dark --}}
                        <table class="table table-striped table-hover align-middle text-dark" id="produkTable"> 
                            <thead>
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
                                        <td class="text-start text-dark">{{ $produk->nama }}</td>
                                        <td>
                                            {{-- Badge diubah ke bg-primary (Fresh Green) dengan teks putih --}}
                                            <span class="badge bg-primary text-white">{{ $produk->kode }}</span>
                                        </td>
                                        
                                        {{-- Tombol Aksi --}}
                                        <td class="d-flex justify-content-center gap-2">
                                            {{-- Tombol Edit (Fresh Green) --}}
                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editProdukModal-{{ $produk->id }}">
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
            
            {{-- MODAL EDIT PRODUK --}}
            @foreach ($produks as $produk)
                <div class="modal fade" id="editProdukModal-{{ $produk->id }}" tabindex="-1" aria-labelledby="editProdukModalLabel-{{ $produk->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        {{-- Modal Content bg-white, border-primary --}}
                        <div class="modal-content bg-white border-primary"> 
                            {{-- Modal Header bg-primary text-white --}}
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="editProdukModalLabel-{{ $produk->id }}">Edit Produk: {{ $produk->nama }}</h5>
                                {{-- Gunakan btn-close biasa (warna default Bootstrap gelap) --}}
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> 
                            </div>
                            <form action="{{ route('produk.update', $produk->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="modal-body text-start text-dark">
                                    <div class="mb-3">
                                        <label for="nama-{{ $produk->id }}" class="form-label text-dark">Nama Produk</label>
                                        {{-- Input bg-secondary, text-dark --}}
                                        <input type="text" class="form-control bg-secondary text-dark border-0" id="nama-{{ $produk->id }}" name="nama" value="{{ old('nama', $produk->nama) }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="kode-{{ $produk->id }}" class="form-label text-dark">Kode Produk</label>
                                        {{-- Input bg-secondary, text-dark --}}
                                        <input type="text" class="form-control bg-secondary text-dark border-0" id="kode-{{ $produk->id }}" name="kode" value="{{ old('kode', $produk->kode) }}" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    {{-- Tombol Batal bg-secondary text-dark --}}
                                    <button type="button" class="btn btn-secondary text-dark" data-bs-dismiss="modal">Batal</button>
                                    {{-- Tombol Simpan (Fresh Green) --}}
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

{{-- MODAL TAMBAH PRODUK --}}
<div class="modal fade" id="createProdukModal" tabindex="-1" aria-labelledby="createProdukModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        {{-- Modal Content bg-white, border-primary --}}
        <div class="modal-content bg-white border-primary"> 
            {{-- Modal Header bg-primary text-white --}}
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createProdukModalLabel">➕ Tambah Produk Komoditas Baru</h5>
                {{-- Gunakan btn-close biasa --}}
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('produk.store') }}" method="POST">
                @csrf
                <div class="modal-body text-start text-dark">
                    <div class="mb-3">
                        <label for="nama" class="form-label text-dark">Nama Komoditas/Produk</label>
                        {{-- Input bg-secondary, text-dark --}}
                        <input type="text" class="form-control bg-secondary text-dark border-0" id="nama" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: Beras, Cabai Merah, Jagung">
                    </div>
                    <div class="mb-3">
                        <label for="kode" class="form-label text-dark">Kode Produk (Singkatan Unik)</label>
                        {{-- Input bg-secondary, text-dark --}}
                        <input type="text" class="form-control bg-secondary text-dark border-0" id="kode" name="kode" value="{{ old('kode') }}" required placeholder="Contoh: BR-01, CM-A, JG-1">
                    </div>
                </div>
                <div class="modal-footer">
                    {{-- Tombol Batal bg-secondary text-dark --}}
                    <button type="button" class="btn btn-secondary text-dark" data-bs-dismiss="modal">Batal</button>
                    {{-- Tombol Simpan (Dark Forest Green) --}}
                    <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk me-1"></i> Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{{-- DataTables script hanya perlu di-include sekali di layouts/app, tapi tidak masalah jika di sini juga --}}
<script>
    // 1. Buat flag dari PHP/Blade
    var validationErrors = @json($errors->any());
    var oldNama = @json(old('nama'));
    var oldKode = @json(old('kode'));
    
    // 2. Logika JavaScript
    $(document).ready(function() {
        // ... Inisialisasi DataTables ...

        if (validationErrors) {
            
            var modalId;
            
            // Asumsi: Jika ada old data (nama & kode), kemungkinan error dari modal Tambah Produk (Create)
            if (oldNama && oldKode) {
                // Cek rute mana yang disubmit. Jika bukan rute update, berarti rute store.
                // NOTE: Untuk menentukan ini secara akurat, Anda mungkin perlu passing ID error dari Controller
                modalId = 'createProdukModal';
            } else {
                // Ini harusnya logic untuk modal Edit, tapi karena sulit ditentukan, kita bisa skip atau tambahkan logic canggih.
                // Untuk amannya, hanya buka modal Tambah jika ada old data yang jelas.
                // Anda bisa menambahkan logic di sini untuk mencoba membuka modal Edit jika ada error form edit.
                
                // Jika error berasal dari Edit, Anda mungkin perlu menggunakan URL saat ini atau menyimpan ID produk yang gagal divalidasi.
                // Contoh:
                // // Simpel: Coba buka modal Edit jika ada error (perlu perbaikan di Controller)
                // // Ambil ID dari URL (asumsi URL edit)
                // var urlSegments = window.location.pathname.split('/');
                // var produkId = urlSegments[urlSegments.length - 2];
                // modalId = 'editProdukModal-' + produkId;
                
                return; // Keluar jika tidak bisa menentukan modal.
            }

            var modalElement = document.getElementById(modalId);
            if (modalElement) {
                var modal = new bootstrap.Modal(modalElement);
                modal.show();
            }
        }
    });
</script>
@endpush