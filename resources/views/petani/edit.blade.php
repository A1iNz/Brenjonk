@extends('layouts.app')

@section('content')
<div class="container text-dark mt-5">
    
    {{-- Header Halaman --}}
    <div class="mb-4 p-3 bg-white rounded-md border border-primary shadow-sm">
        <h1 class="mb-0 fs-3 text-dark">Edit Data Petani ⚙️</h1>
        <p class="text-muted">Perbarui informasi akun dan data petani terkait.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-9"> 
            
            {{-- Kartu Utama Form (bg-white, border-primary) --}}
            <div class="card bg-white border-primary shadow-lg"> 
                
                {{-- Card Header menggunakan Fresh Green (bg-primary) --}}
                <div class="card-header bg-primary d-flex justify-content-between align-items-center text-white">
                    <h3 class="mb-0"><i class="fa-solid fa-user-pen me-2"></i> Edit Data Petani: {{ $petani->user->nama_lngkp ?? $petani->user->name }}</h3>
                    {{-- Tombol Kembali menggunakan Dark Forest Green (btn-success) --}}
                    <a href="{{ route('petani') }}" class="btn btn-sm btn-success text-white"><i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar</a>
                </div>
                
                <div class="card-body text-dark">
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

                    <form action="{{ route('petani.update', $petani->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        {{-- Data Akun Login --}}
                        <h4 class="text-success mb-3 mt-2"><i class="fa-solid fa-id-card me-1"></i> Data Akun Login</h4>
                        <hr class="mt-0 mb-4 border-success">
                        
                        {{-- Nama Lengkap (Data User) --}}
                        <div class="mb-3">
                            <label for="nama_lngkp" class="form-label text-dark">Nama Lengkap Petani</label>
                            <input 
                                type="text" 
                                class="form-control rounded-md bg-secondary text-dark border-0 @error('nama_lngkp') is-invalid @enderror" 
                                id="nama_lngkp" 
                                name="nama_lngkp" 
                                value="{{ old('nama_lngkp', $petani->user->nama_lngkp) }}" 
                                required
                            >
                            @error('nama_lngkp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        
                        {{-- Username (Data User) --}}
                        <div class="mb-3">
                            <label for="name" class="form-label text-dark">Username (Digunakan untuk Login)</label>
                            <input 
                                type="text" 
                                class="form-control rounded-md bg-secondary text-dark border-0 @error('name') is-invalid @enderror" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', $petani->user->name) }}" 
                                required
                            >
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        
                        {{-- Ubah Password --}}
                        <h4 class="text-success mb-3 mt-5"><i class="fa-solid fa-key me-1"></i> Ubah Password</h4>
                        <hr class="mt-0 mb-4 border-success">
                        
                        {{-- Password Baru --}}
                        <div class="mb-3">
                            <label for="password" class="form-label text-dark">Password Baru</label>
                            <input 
                                type="password" 
                                class="form-control rounded-md bg-secondary text-dark border-0 @error('password') is-invalid @enderror" 
                                id="password" 
                                name="password"
                            >
                            <small class="form-text text-muted">Kosongkan jika tidak ingin mengganti *password*.</small>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        
                        {{-- Konfirmasi Password Baru --}}
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label text-dark">Konfirmasi Password Baru</label>
                            <input 
                                type="password" 
                                class="form-control rounded-md bg-secondary text-dark border-0" 
                                id="password_confirmation" 
                                name="password_confirmation"
                            >
                        </div>
                        
                        {{-- Data Petani --}}
                        <h4 class="text-success mb-3 mt-5"><i class="fa-solid fa-barcode me-1"></i> Data Petani</h4>
                        <hr class="mt-0 mb-4 border-success">
                        
                        {{-- Kode Petani --}}
                        <div class="mb-4">
                            <label for="kode" class="form-label text-dark">Kode Petani</label>
                            <input 
                                type="text" 
                                class="form-control rounded-md bg-secondary text-dark border-0 @error('kode') is-invalid @enderror" 
                                id="kode" 
                                name="kode" 
                                value="{{ old('kode', $petani->kode) }}" 
                                placeholder="Contoh: PTN001" 
                                required
                            >
                            @error('kode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-grid mt-5">
                            {{-- Tombol Simpan menggunakan Dark Forest Green (btn-success) --}}
                            <button type="submit" class="btn btn-lg btn-success text-white fw-bold">
                                <i class="fa-solid fa-floppy-disk me-2"></i> Simpan Perubahan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection