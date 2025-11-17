@extends('layouts.app')

@section('content')
<div class="container text-light mt-5">
    <div class="row justify-content-center">
        <div class="col-md-9"> <div class="card bg-dark border-primary shadow-lg"> <div class="card-header bg-primary d-flex justify-content-between align-items-center text-white">
                    <h3 class="mb-0">👨‍🌾 Pendaftaran Akun Petani Baru</h3>
                    <a href="{{ route('petani') }}" class="btn btn-sm btn-light">
                        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar
                    </a>
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

                    <form action="{{ route('petani.store') }}" method="POST">
                        @csrf
                        
                        {{-- Bagian 1: Data Akun Login (User) --}}
                        <h4 class="text-primary mb-3 mt-2"><i class="fa-solid fa-id-card me-1"></i> Data Akun Login & Identitas</h4>
                        <hr class="mt-0 mb-4 border-primary">
                        
                        <div class="row">
                            {{-- Nama Lengkap --}}
                            <div class="col-md-6 mb-3">
                                <label for="nama_lngkp" class="form-label">Nama Lengkap Petani</label>
                                <input 
                                    type="text" 
                                    class="form-control rounded-md bg-secondary text-light border-0 @error('nama_lngkp') is-invalid @enderror" 
                                    id="nama_lngkp" 
                                    name="nama_lngkp" 
                                    value="{{ old('nama_lngkp') }}" 
                                    placeholder="Contoh: Budi Santoso"
                                    required
                                >
                                @error('nama_lngkp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Username --}}
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Username (Untuk Login)</label>
                                <input 
                                    type="text" 
                                    class="form-control rounded-md bg-secondary text-light border-0 @error('name') is-invalid @enderror" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name') }}" 
                                    placeholder="Contoh: budi_s_ptn"
                                    required
                                >
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            {{-- Password --}}
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input 
                                    type="password" 
                                    class="form-control rounded-md bg-secondary text-light border-0 @error('password') is-invalid @enderror" 
                                    id="password" 
                                    name="password" 
                                    required
                                >
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Konfirmasi Password --}}
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input 
                                    type="password" 
                                    class="form-control rounded-md bg-secondary text-light border-0" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    required
                                >
                            </div>
                        </div>

                        {{-- Bagian 2: Data Petani (Tabel Petanis) --}}
                        <h4 class="text-primary mb-3 mt-5"><i class="fa-solid fa-barcode me-1"></i> Kode Pendaftaran Petani</h4>
                        <hr class="mt-0 mb-4 border-primary">

                        <div class="mb-4">
                            <label for="kode" class="form-label">Kode Petani</label>
                            <input 
                                type="text" 
                                class="form-control rounded-md bg-secondary text-light border-0 @error('kode') is-invalid @enderror" 
                                id="kode" 
                                name="kode" 
                                value="{{ old('kode') }}" 
                                placeholder="Contoh: PTN001 (Kode unik yang diberikan)" 
                                required
                            >
                            @error('kode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-lg btn-success">
                                <i class="fa-solid fa-user-plus me-2"></i> Simpan Data Petani
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection