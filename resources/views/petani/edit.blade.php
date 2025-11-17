@extends('layouts.app')

@section('content')
<div class="container text-light mt-5">
    <div class="row justify-content-center">
        <div class="col-md-9"> <div class="card bg-dark border-warning shadow-lg"> <div class="card-header bg-warning d-flex justify-content-between align-items-center text-dark">
                    <h3 class="mb-0">⚙️ Edit Data Petani: {{ $petani->user->nama_lngkp ?? $petani->user->name }}</h3>
                    <a href="{{ route('petani') }}" class="btn btn-sm btn-dark"><i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar</a>
                </div>
                
                <div class="card-body text-light">
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
                        
                        <h4 class="text-warning mb-3 mt-2"><i class="fa-solid fa-id-card me-1"></i> Data Akun Login</h4>
                        <hr class="mt-0 mb-4 border-warning">
                        
                        {{-- Nama Lengkap (Data User) --}}
                        <div class="mb-3">
                            <label for="nama_lngkp" class="form-label">Nama Lengkap Petani</label>
                            <input 
                                type="text" 
                                class="form-control rounded-md bg-secondary text-light border-0 @error('nama_lngkp') is-invalid @enderror" 
                                id="nama_lngkp" 
                                name="nama_lngkp" 
                                value="{{ old('nama_lngkp', $petani->user->nama_lngkp) }}" 
                                required
                            >
                            @error('nama_lngkp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        
                        {{-- Username (Data User) --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">Username (Digunakan untuk Login)</label>
                            <input 
                                type="text" 
                                class="form-control rounded-md bg-secondary text-light border-0 @error('name') is-invalid @enderror" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', $petani->user->name) }}" 
                                required
                            >
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        
                        <h4 class="text-warning mb-3 mt-5"><i class="fa-solid fa-key me-1"></i> Ubah Password</h4>
                        <hr class="mt-0 mb-4 border-warning">
                        
                        {{-- Password Baru --}}
                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru</label>
                            <input 
                                type="password" 
                                class="form-control rounded-md bg-secondary text-light border-0 @error('password') is-invalid @enderror" 
                                id="password" 
                                name="password"
                            >
                            <small class="form-text text-muted">Kosongkan jika tidak ingin mengganti *password*.</small>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        
                        {{-- Konfirmasi Password Baru --}}
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input 
                                type="password" 
                                class="form-control rounded-md bg-secondary text-light border-0" 
                                id="password_confirmation" 
                                name="password_confirmation"
                            >
                        </div>
                        
                        <h4 class="text-warning mb-3 mt-5"><i class="fa-solid fa-barcode me-1"></i> Data Petani</h4>
                        <hr class="mt-0 mb-4 border-warning">
                        
                        {{-- Kode Petani --}}
                        <div class="mb-4">
                            <label for="kode" class="form-label">Kode Petani</label>
                            <input 
                                type="text" 
                                class="form-control rounded-md bg-secondary text-light border-0 @error('kode') is-invalid @enderror" 
                                id="kode" 
                                name="kode" 
                                value="{{ old('kode', $petani->kode) }}" 
                                placeholder="Contoh: PTN001" 
                                required
                            >
                            @error('kode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-lg btn-warning text-dark">
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