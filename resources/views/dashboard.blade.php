@extends('layouts.app')

@section('content')
    <div class="container mt-5 text-dark"> 
        
        {{-- Header Selamat Datang (Background Putih, Border Fresh Green, Teks PUTIH) --}}
        {{-- Untuk membuat teks putih terlihat di background putih, kita akan tambahkan sedikit overlay warna --}}
        <div class="mb-4 p-3 bg-white rounded-md border border-primary shadow-sm">
            {{-- Tambahkan div dengan background Fresh Green untuk teks putih --}}
            <div class="p-3 rounded" style="background-color: #4CAF50;"> 
                <h1 class="mb-0 fs-3 text-white">Halo, {{ auth()->user()->nama_lngkp ?? 'Petani' }}! 👋</h1>
                <p class="text-white mb-0">Selamat datang di Dashboard Laporan Rencana Panen.</p>
            </div>
        </div>

        {{-- Notifikasi Error/Success --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Berhasil!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> Ada beberapa masalah saat mengirim laporan.<br>
                <ul class="mt-2 mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ROW UTAMA: STATISTIK & FORM LAPOR --}}
        <div class="row g-4 mb-5">
            
            {{-- KOLOM KIRI: STATISTIK RINGKAS --}}
            <div class="col-md-4">
                <h4 class="text-primary mb-3">Statistik Laporan Anda</h4>
                
                {{-- Card Statistik --}}
                <div class="card bg-white border border-info mb-3 shadow-sm">
                    <div class="card-body p-3 d-flex flex-row align-items-center">
                        <i class="fa-solid fa-hourglass-half fa-xl text-warning me-4"></i>
                        <div>
                            <h3 class="mb-0 text-dark">{{ $jumlahPending }}</h3> 
                            <p class="text-muted mb-0">Menunggu Verifikasi</p>
                        </div>
                    </div>
                </div>

                <div class="card bg-white border border-info mb-3 shadow-sm">
                    <div class="card-body p-3 d-flex flex-row align-items-center">
                        <i class="fa-solid fa-check-circle fa-xl text-success me-4"></i>
                        <div>
                            <h3 class="mb-0 text-dark">{{ $jumlahApproved }}</h3> 
                            <p class="text-muted mb-0">Laporan Disetujui</p>
                        </div>
                    </div>
                </div>
                
                <div class="card bg-white border border-info shadow-sm">
                    <div class="card-body p-3 d-flex flex-row align-items-center">
                        <i class="fa-solid fa-box-open fa-xl text-primary me-4"></i>
                        <div>
                            <h3 class="mb-0 text-dark">{{ $jumlahProdukTerdaftar }}</h3> 
                            <p class="text-muted mb-0">Produk Terdaftar</p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- KOLOM KANAN: FORM LAPOR RENCANA PANEN --}}
            <div class="col-md-8">
                <div class="card bg-white border-primary shadow-lg">
                    {{-- HEADER: bg-primary (Fresh Green), TULISAN DIUBAH KE PUTIH --}}
                    <div class="card-header bg-primary text-white"> 
                        <h3 class="ms-1 mb-0"><i class="fa-solid fa-circle-plus me-2"></i> Form Lapor Rencana Panen</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('panen.store') }}" method="POST">
                            @csrf
                            
                            {{-- Input Produk --}}
                            <div class="mb-3">
                                <label class="form-label text-dark">Nama Komoditas/Produk</label>
                                <select class="form-select bg-secondary border-0 @error('produk_id') is-invalid @enderror" name="produk_id" required>
                                    <option value="" selected disabled>Pilih Nama Produk</option>
                                    @foreach ($produks as $produk)
                                        <option value="{{ $produk->id }}" {{ old('produk_id') == $produk->id ? 'selected' : '' }}>{{ $produk->nama }}</option>
                                    @endforeach
                                </select>
                                @error('produk_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            
                            {{-- Input Estimasi Hasil Panen --}}
                            <div class="mb-3">
                                <label class="form-label text-dark">Estimasi Hasil Panen (Kg)</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control bg-secondary border-0 @error('estimasi_hasil') is-invalid @enderror" name="estimasi_hasil" placeholder="Contoh: 50.5" value="{{ old('estimasi_hasil') }}" required>
                                    <span class="input-group-text bg-secondary border-0 text-dark">Kg</span>
                                    @error('estimasi_hasil')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            
                            {{-- Input Estimasi Waktu Panen --}}
                            <div class="mb-4">
                                <label class="form-label text-dark">Estimasi Waktu Panen</label>
                                <input type="date" class="form-control bg-secondary border-0 @error('estimasi_waktu') is-invalid @enderror" name="estimasi_waktu" value="{{ old('estimasi_waktu') }}" required>
                                @error('estimasi_waktu')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-success fw-bold"> <i class="fa-solid fa-paper-plane me-2"></i> Kirim Laporan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- ROW BAWAH: RIWAYAT LAPORAN PANEN --}}
        <div class="row my-4">
            <div class="col-md-12">
                <div class="card bg-white border-primary shadow-lg">
                    {{-- HEADER: bg-success (Fresh Green), TULISAN DIUBAH KE PUTIH --}}
                    <div class="card-header bg-success text-white"> 
                        <h3 class="ms-1 mb-0"><i class="fa-solid fa-list-check me-2"></i> Riwayat Laporan Panen Anda</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle text-center" id="tabelPanen"> 
                                <thead>
                                    <tr>
                                        <th style="width: 5%">No</th>
                                        <th class="text-start">Komoditas</th>
                                        <th>Hasil Panen (Kg)</th>
                                        <th>Waktu Panen (Estimasi)</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($panens as $panen)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-start text-dark">{{ $panen->produk->nama ?? 'Produk Dihapus' }}</td>
                                            <td>{{ number_format($panen->estimasi_hasil_panen, 2, ',', '.') }} Kg</td>
                                            <td>{{ \Carbon\Carbon::parse($panen->estimasi_waktu_panen)->format('d M Y') }}</td>
                                            <td>
                                                @if ($panen->status == 'approved')
                                                    <span class="badge bg-success">Disetujui</span>
                                                @elseif ($panen->status == 'reject')
                                                    <span class="badge bg-danger">Ditolak</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                <div class="alert alert-secondary mb-0">
                                                    Anda belum memiliki riwayat laporan rencana panen.
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
    // Inisialisasi DataTables untuk tabel Riwayat Laporan Panen
    $(document).ready(function() {
        $('#tabelPanen').DataTable({
            "order": [[ 3, "desc" ]], // Urutkan berdasarkan kolom Waktu Panen (indeks 3) secara descending
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
            }
        });
    });
</script>
@endpush