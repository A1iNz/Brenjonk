@extends('layouts.app')

@section('content')
<div class="container text-dark mt-5">
    
    {{-- Header Halaman --}}
    <div class="mb-4 p-3 bg-white rounded-md border border-primary shadow-sm">
        <h1 class="mb-0 fs-3 text-dark">Tambah Komoditas Baru ➕</h1>
        <p class="text-muted">Isi formulir di bawah untuk mendaftarkan produk komoditas baru ke dalam sistem.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-7"> 
            
            {{-- Kartu Utama Form --}}
            {{-- Card menggunakan bg-white dan border-primary --}}
            <div class="card bg-white border-primary shadow-lg"> 
                
                {{-- Card Header menggunakan Fresh Green (bg-primary) --}}
                <div class="card-header bg-primary d-flex justify-content-between align-items-center text-white">
                    <h3 class="mb-0"><i class="fa-solid fa-circle-plus me-2"></i> Form Tambah Komoditas Baru</h3>
                    {{-- Tombol Kembali menggunakan Dark Forest Green (btn-success) --}}
                    <a href="{{ route('produk') }}" class="btn btn-sm btn-success text-white">
                        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar
                    </a>
                </div>
                
                <div class="card-body">
                    
                    {{-- Area Error Validasi --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> Ada beberapa masalah dengan input Anda.<br>
                            <ul class="mt-2 mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    {{-- Akhir Area Error --}}

                    <form action="{{ route('produk.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            {{-- Label text-dark --}}
                            <label for="nama" class="form-label text-dark">Nama Komoditas/Produk</label>
                            <input 
                                type="text" 
                                {{-- Input bg-secondary (Putih Pudar) dan text-dark --}}
                                class="form-control rounded-md bg-secondary text-dark border-0 @error('nama') is-invalid @enderror" 
                                id="nama" 
                                name="nama" 
                                value="{{ old('nama') }}" 
                                placeholder="Contoh: Beras, Cabai Merah"
                                required
                            >
                            @error('nama')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="kode" class="form-label text-dark">Kode Produk (Singkatan Unik)</label>
                            <input 
                                type="text"
                                {{-- Input bg-secondary (Putih Pudar) dan text-dark --}}
                                class="form-control rounded-md bg-secondary text-dark border-0 @error('kode') is-invalid @enderror" 
                                id="kode" 
                                name="kode" 
                                value="{{ old('kode') }}" 
                                placeholder="Contoh: BR-01, CM-A"
                                required
                            >
                            @error('kode')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            {{-- Tombol Simpan menggunakan Dark Forest Green (btn-success) --}}
                            <button type="submit" class="btn btn-lg btn-success text-white fw-bold">
                                <i class="fa-solid fa-floppy-disk me-2"></i> Simpan Produk Baru
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection