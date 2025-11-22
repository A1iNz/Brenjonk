@extends('layouts.app')

@section('content')
<div class="container text-dark mt-5">
    
    {{-- Header Halaman --}}
    <div class="mb-4 p-3 bg-white rounded-md border border-primary shadow-sm">
        <h1 class="mb-0 fs-3 text-dark">Edit Stok & Harga Jual ✍️</h1>
        <p class="text-muted">Lakukan penyesuaian pada stok total dan harga jual rata-rata untuk komoditas ini.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-7"> 
            
            {{-- Card menggunakan bg-white dan border-primary --}}
            <div class="card bg-white border-primary shadow-lg"> 
                
                {{-- Card Header menggunakan Fresh Green (bg-primary) --}}
                <div class="card-header bg-primary d-flex justify-content-between align-items-center text-white">
                    <h3 class="mb-0"><i class="fa-solid fa-pen-to-square me-2"></i> Edit Stok Komoditas: {{ $produk->nama }}</h3>
                    {{-- Tombol Kembali menggunakan Dark Forest Green (btn-success) --}}
                    <a href="{{ route('stok') }}" class="btn btn-sm btn-success text-white">
                        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar Stok
                    </a>
                </div>
                
                <div class="card-body text-dark">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> Ada masalah input.<br>
                            <ul class="mt-2 mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('stok.update', $produk->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-4 text-center">
                            <p class="mb-1 fs-5 text-muted">Stok Saat Ini (Gabungan Rencana Panen yang Disetujui):</p>
                            {{-- Badge menggunakan Fresh Green (bg-primary) --}}
                            <span class="badge bg-primary text-white fs-2 p-3 shadow-sm">
                                {{ number_format($produk->rencana_panens_sum_estimasi_hasil_panen ?? 0, 0, ',', '.') }} Kg
                            </span>
                        </div>
                        
                        {{-- Garis Pemisah menggunakan warna Primary --}}
                        <hr class="mb-4 border-primary">
                        
                        {{-- Field untuk input Stok Baru --}}
                        <div class="mb-4">
                            {{-- Label text-dark --}}
                            <label for="new_stock" class="form-label text-dark">Masukkan Total Stok Baru (Kg)</label>
                            <input 
                                type="number" 
                                {{-- Input bg-secondary (Putih Pudar) dan text-dark --}}
                                class="form-control rounded-md bg-secondary text-dark border-0 @error('new_stock') is-invalid @enderror" 
                                id="new_stock" 
                                name="new_stock" 
                                value="{{ old('new_stock', $produk->rencana_panens_sum_estimasi_hasil_panen ?? 0) }}"
                                required
                                min="0"
                                placeholder="Masukkan jumlah stok total baru dalam kilogram"
                            >
                            @error('new_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Angka ini akan menggantikan total estimasi stok yang ada saat ini untuk {{ $produk->nama }}.</small>
                        </div>
                        
                        {{-- Field untuk Harga Jual (Opsional) --}}
                        <div class="mb-4">
                            <label for="harga" class="form-label text-dark">Harga Jual Rata-Rata per Kg (Opsional)</label>
                            <input 
                                type="number" 
                                {{-- Input bg-secondary (Putih Pudar) dan text-dark --}}
                                class="form-control rounded-md bg-secondary text-dark border-0 @error('harga') is-invalid @enderror" 
                                id="harga" 
                                name="harga" 
                                value="{{ old('harga', $produk->rata_rata_harga ?? 0) }}"
                                min="0"
                                placeholder="Masukkan harga rata-rata komoditas ini per Kg"
                            >
                            @error('harga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid mt-5">
                            {{-- Tombol Simpan menggunakan Dark Forest Green (btn-success) --}}
                            <button type="submit" class="btn btn-lg btn-success text-white fw-bold">
                                <i class="fa-solid fa-floppy-disk me-2"></i> Simpan Perubahan Stok
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection