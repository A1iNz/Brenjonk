@extends('layouts.app')

@section('content')
<div class="container text-light mt-5">
    <div class="row justify-content-center">
        <div class="col-md-11"> 
            
            <div class="card bg-dark border-primary shadow-lg"> 
                
                <div class="card-header bg-primary d-flex justify-content-between align-items-center text-white">
                    <h3 class="mb-0">📋 Daftar Komoditas & Stok Jual</h3>
                </div>
                
                <div class="card-body p-4">
                    
                    {{-- Area Notifikasi --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Berhasil!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <p class="text-muted fst-italic">Tabel di bawah menampilkan semua produk terdaftar. Klik 'Edit' untuk menyesuaikan jumlah stok siap jual dan harga.</p>

                    <div class="table-responsive mt-3">
                        <table id="tabelStok" class="table table-dark table-striped table-hover align-middle text-center">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 5%;">#</th>
                                    <th scope="col" class="text-start">Nama Komoditas</th>
                                    <th scope="col" style="width: 25%;">Total Stok Ready (Kg)</th>
                                    <th scope="col" style="width: 25%;">Rata-rata Harga Jual</th>
                                    <th scope="col" style="width: 15%;">Aksi</th> 
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($produks as $produk)
                                    @php
                                        $stok = $produk->rencana_panens_sum_estimasi_hasil_panen ?? 0;
                                        $harga = $produk->rencana_panens_avg_harga_jual ?? 0;
                                        $badgeClass = ($stok > 0) ? 'bg-warning' : 'bg-secondary';
                                        $badgeText = ($stok > 0) ? 'text-dark' : 'text-light';
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-start">
                                            <i class="fa-solid fa-seedling me-2 text-success"></i>
                                            <strong>{{ $produk->nama }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge {{ $badgeClass }} {{ $badgeText }} fs-6 p-2">
                                                {{ number_format($stok, 0, ',', '.') }} Kg
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-info">
                                                Rp {{ number_format($harga, 0, ',', '.') }}
                                            </span> / Kg
                                        </td>
                                        <td> 
                                            <a href="{{ route('stok.edit', $produk->id) }}" class="btn btn-sm btn-warning">
                                                <i class="fa-solid fa-pen-to-square"></i> Edit
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center"> 
                                            <div class="alert alert-danger mb-0">
                                                ‼️ Tidak ada produk komoditas terdaftar di sistem. Silakan daftarkan produk di menu "Produk".
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
@endsection

@push('scripts')
// ... (Script DataTables)
@endpush