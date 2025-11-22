@extends('layouts.app')

@section('content')
<div class="container text-dark mt-5">
    
    {{-- Header Halaman --}}
    {{-- PERBAIKAN: Mengganti text-white di atas bg-white menjadi text-dark dan text-muted agar terlihat --}}
    <div class="mb-4 p-3 bg-white rounded-md border border-primary shadow-sm">
        <h1 class="mb-0 fs-3 text-white">Stok Siap Jual 📦</h1>
        <p class="text-white mb-0">Kelola jumlah stok dan harga jual rata-rata komoditas.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12"> 
            
            {{-- Card menggunakan bg-white dan border-primary --}}
            <div class="card bg-white border-primary shadow-lg"> 
                
                {{-- Card Header menggunakan Dark Forest Green (bg-success) --}}
                <div class="card-header bg-success d-flex justify-content-between align-items-center text-white">
                    <h3 class="ms-1 mb-0"><i class="fa-solid fa-boxes-stacked me-2"></i> Daftar Komoditas & Stok Jual</h3>
                </div>
                
                <div class="card-body p-4">
                    
                    {{-- Area Notifikasi --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Berhasil!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    {{-- PERBAIKAN: Mengganti text-white menjadi text-dark --}}
                    <p class="text-white fst-italic">Tabel di bawah menampilkan semua produk terdaftar. Klik 'Edit' untuk menyesuaikan jumlah stok siap jual.</p>

                    <div class="table-responsive mt-3">
                        {{-- Tabel diubah ke light theme dan menggunakan text-dark --}}
                        <table id="tabelStok" class="table table-striped table-hover align-middle text-dark">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 5%;">#</th>
                                    <th scope="col" class="text-start">Nama Komoditas</th>
                                    <th scope="col" style="width: 35%;">Total Stok Ready (Kg)</th>
                                    <th scope="col" style="width: 20%;">Aksi</th> 
                                    {{-- Kolom Harga Dihilangkan --}}
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($produks as $produk)
                                    @php
                                        // Stok yang disetujui
                                        $stok = $produk->rencana_panens_sum_estimasi_hasil_panen ?? 0; 
                                        
                                        // Badge Class: Menggunakan Fresh Green jika stok ada
                                        $badgeClass = ($stok > 0) ? 'bg-primary' : 'bg-secondary';
                                        $badgeText = ($stok > 0) ? 'text-white' : 'text-dark';
                                    @endphp
                                    <tr>
                                        <td class="text-dark">{{ $loop->iteration }}</td>
                                        <td class="text-start text-dark">
                                            <i class="fa-solid fa-seedling me-2 text-success"></i>
                                            <strong>{{ $produk->nama }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge {{ $badgeClass }} {{ $badgeText }} fs-6 p-2">
                                                {{ number_format($stok, 0, ',', '.') }} Kg
                                            </span>
                                        </td>
                                        <td> 
                                            {{-- Tombol Edit (Tetap Warning/Kuning agar menonjol, tapi teksnya Dark Forest Green) --}}
                                            <a href="{{ route('stok.edit', $produk->id) }}" class="btn btn-sm btn-warning text-dark"> 
                                                <i class="fa-solid fa-pen-to-square"></i> Edit
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center"> {{-- colspan dikurangi menjadi 4 --}}
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
<script>
    // Inisialisasi DataTables untuk tabel Stok
    $(document).ready(function() {
        $('#tabelStok').DataTable({
            // Urutkan berdasarkan kolom Stok (indeks 2) secara descending
            "order": [[ 2, "desc" ]], 
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
            }
        });
    });
</script>
@endpush