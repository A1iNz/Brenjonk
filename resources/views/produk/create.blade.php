@extends('layouts.app')

@section('content')
<div class="container text-light mt-5">
    <div class="row justify-content-center">
        <div class="col-md-7"> 
            
            {{-- Kartu Utama Form --}}
            <div class="card bg-dark border-primary shadow-lg"> 
                
                <div class="card-header bg-primary d-flex justify-content-between align-items-center text-white">
                    <h3 class="mb-0">➕ Tambah Komoditas Baru</h3>
                    <a href="{{ route('produk') }}" class="btn btn-sm btn-light">
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
                            <label for="nama" class="form-label text-white">Nama Komoditas/Produk</label>
                            <input 
                                type="text" 
                                class="form-control rounded-md bg-secondary text-light border-0 @error('nama') is-invalid @enderror" 
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
                            <label for="kode" class="form-label text-white">Kode Produk (Singkatan Unik)</label>
                            <input 
                                type="text"
                                class="form-control rounded-md bg-secondary text-light border-0 @error('kode') is-invalid @enderror" 
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
                            <button type="submit" class="btn btn-lg btn-success">
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