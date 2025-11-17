@extends('layouts.app')

@section('content')
<div class="container text-light mt-5">
    <div class="row justify-content-center">
        <div class="col-md-7"> 
            
            <div class="card bg-dark border-warning shadow-lg"> 
                
                <div class="card-header bg-warning d-flex justify-content-between align-items-center text-dark">
                    <h3 class="mb-0">✍️ Edit Stok Komoditas: {{ $produk->nama }}</h3>
                    <a href="{{ route('stok') }}" class="btn btn-sm btn-dark">
                        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar Stok
                    </a>
                </div>
                
                <div class="card-body text-light">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> Ada masalah input.
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
                            <p class="mb-1 fs-5 text-muted">Stok Saat Ini (Gabungan Semua Rencana Panen yang Disetujui):</p>
                            <span class="badge bg-danger text-white fs-2 p-3 shadow-sm">
                                {{ number_format($produk->rencana_panens_sum_estimasi_hasil_panen ?? 0, 0, ',', '.') }} Kg
                            </span>
                        </div>
                        
                        <hr class="mb-4 border-warning">
                        
                        {{-- Field untuk input Stok Baru --}}
                        <div class="mb-4">
                            <label for="new_stock" class="form-label text-white">Masukkan Total Stok Baru (Kg)</label>
                            <input 
                                type="number" 
                                class="form-control rounded-md bg-secondary text-light border-0 @error('new_stock') is-invalid @enderror" 
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
                            <label for="harga" class="form-label text-white">Harga Jual Rata-Rata per Kg (Opsional)</label>
                            <input 
                                type="number" 
                                class="form-control rounded-md bg-secondary text-light border-0 @error('harga') is-invalid @enderror" 
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
                            <button type="submit" class="btn btn-lg btn-warning text-dark">
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