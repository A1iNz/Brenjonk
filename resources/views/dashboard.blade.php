@extends('layouts.app')

@section('content')
    <div class="container mt-5 text-light">
        
        {{-- Header Selamat Datang --}}
        <div class="mb-4 p-3 bg-dark rounded-md border-primary shadow-sm">
            <h1 class="mb-0 fs-3">Halo, {{ auth()->user()->nama_lngkp ?? 'Petani' }}! 👋</h1>
            <p class="text-muted">Selamat datang di Dashboard Laporan Rencana Panen.</p>
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
                
                <div class="card bg-dark border-secondary mb-3 shadow-sm">
                    <div class="card-body p-3 d-flex flex-row align-items-center">
                        <i class="fa-solid fa-hourglass-half fa-xl text-warning me-4"></i>
                        <div>
                            <h3 class="mb-0">{{ $jumlahPending }}</h3> 
                            <p class="text-muted mb-0">Menunggu Verifikasi</p>
                        </div>
                    </div>
                </div>

                <div class="card bg-dark border-secondary mb-3 shadow-sm">
                    <div class="card-body p-3 d-flex flex-row align-items-center">
                        <i class="fa-solid fa-check-circle fa-xl text-success me-4"></i>
                        <div>
                            <h3 class="mb-0">{{ $jumlahApproved }}</h3> 
                            <p class="text-muted mb-0">Laporan Disetujui</p>
                        </div>
                    </div>
                </div>
                
                <div class="card bg-dark border-secondary shadow-sm">
                    <div class="card-body p-3 d-flex flex-row align-items-center">
                        <i class="fa-solid fa-box-open fa-xl text-primary me-4"></i>
                        <div>
                            <h3 class="mb-0">{{ $jumlahProdukTerdaftar }}</h3> 
                            <p class="text-muted mb-0">Produk Terdaftar</p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- KOLOM KANAN: FORM LAPOR RENCANA PANEN --}}
            <div class="col-md-8">
                <div class="card bg-dark border-success shadow-lg">
                    <div class="card-header bg-success text-white">
                        <h3 class="ms-1 mb-0"><i class="fa-solid fa-circle-plus me-2"></i> Form Lapor Rencana Panen</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('panen.store') }}" method="POST">
                            @csrf
                            
                            {{-- Input Produk --}}
                            <div class="mb-3">
                                <label class="form-label text-white">Nama Komoditas/Produk</label>
                                <select class="form-select bg-secondary text-light border-0 @error('produk_id') is-invalid @enderror" name="produk_id" required>
                                    <option value="" selected disabled>Pilih Nama Produk</option>
                                    @foreach ($produks as $produk)
                                        <option value="{{ $produk->id }}" {{ old('produk_id') == $produk->id ? 'selected' : '' }}>{{ $produk->nama }}</option>
                                    @endforeach
                                </select>
                                @error('produk_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            
                            {{-- Input Estimasi Hasil Panen --}}
                            <div class="mb-3">
                                <label class="form-label text-white">Estimasi Hasil Panen (Kg)</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control bg-secondary text-light border-0 @error('estimasi_hasil') is-invalid @enderror" name="estimasi_hasil" placeholder="Contoh: 50.5" value="{{ old('estimasi_hasil') }}" required>
                                    <span class="input-group-text bg-secondary text-light border-0">Kg</span>
                                    @error('estimasi_hasil')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            
                            {{-- Input Estimasi Waktu Panen --}}
                            <div class="mb-4">
                                <label class="form-label text-white">Estimasi Waktu Panen</label>
                                <input type="date" class="form-control bg-secondary text-light border-0 @error('estimasi_waktu') is-invalid @enderror" name="estimasi_waktu" value="{{ old('estimasi_waktu') }}" required>
                                @error('estimasi_waktu')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-success fw-bold">
                                    <i class="fa-solid fa-paper-plane me-2"></i> Kirim Laporan
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
                <div class="card bg-dark border-info shadow-lg">
                    <div class="card-header bg-info text-dark">
                        <h3 class="ms-1 mb-0"><i class="fa-solid fa-list-check me-2"></i> Riwayat Laporan Panen Anda</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-dark table-striped table-hover align-middle text-center" id="tabelPanen">
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
                                            <td class="text-start">{{ $panen->produk->nama ?? 'Produk Dihapus' }}</td>
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
        // Inisialisasi DataTables untuk tabel Riwayat Lapor Panen
        $(document).ready(function() {
            new DataTable('#tabelPanen', {
                info: true,
                ordering: true,
                paging: true,
                // Opsi bahasa
                "language": {
                    "search": "Cari Riwayat:",
                    "lengthMenu": "Tampilkan _MENU_ entri",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    "paginate": {
                        "previous": "Sebelumnya",
                        "next": "Berikutnya"
                    }
                }
            });
        });
    </script>
@endpush