@extends('layouts.app')

@section('content')
<div class="container mt-5 text-dark">
    
    {{-- Header Halaman --}}
    <div class="mb-4 p-3 bg-white rounded-md border border-primary shadow-sm">
        <h1 class="mb-0 fs-3 text-white">History Validasi Laporan Panen 📜</h1>
        <p class="text-white mb-0">Riwayat lengkap laporan panen yang telah disetujui atau ditolak.</p>
    </div>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Main Content: Tabel History --}}
    <div class="row justify-content-center">
        <div class="col-md-12">
            {{-- Card menggunakan bg-white dan border-primary --}}
            <div class="card bg-white border-primary shadow-lg"> 
                {{-- Header Card menggunakan Dark Forest Green (bg-success) --}}
                <div class="card-header bg-success text-white"> 
                    <h3 class="ms-1 mb-0"><i class="fa-solid fa-clock-rotate-left me-2"></i> Riwayat Laporan Tervalidasi</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        {{-- Tabel diubah ke light theme dan menggunakan DataTables --}}
                        <table class="table table-striped table-hover align-middle text-center" id="tabelHistory"> 
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 5%">No.</th>
                                    <th scope="col" class="text-start">Petani</th>
                                    <th scope="col" class="text-start">Produk</th>
                                    <th scope="col">Estimasi Hasil</th>
                                    <th scope="col">Estimasi Waktu</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($panens as $panen)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        {{-- text-dark (Dark Forest Green) --}}
                                        <td class="text-start text-dark">{{ $panen->petani->user->nama_lngkp ?? 'Admin' }} ({{ $panen->petani->kode ?? 'N/A' }})</td>
                                        <td class="text-start text-dark">{{ $panen->produk->nama ?? 'Produk Dihapus' }}</td>
                                        <td>{{ number_format($panen->estimasi_hasil_panen, 2, ',', '.') }} kg</td>
                                        <td>{{ \Carbon\Carbon::parse($panen->estimasi_waktu_panen)->format('d M Y') }}</td>
                                        <td>
                                            @if($panen->status === 'pending')
                                                <span class="badge bg-warning text-dark">{{ $panen->status }}</span>
                                            @elseif($panen->status === 'approved')
                                                {{-- bg-success adalah Dark Forest Green --}}
                                                <span class="badge bg-success">{{ $panen->status }}</span>
                                            @elseif($panen->status === 'reject')
                                                <span class="badge bg-danger">{{ $panen->status }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="alert alert-secondary mb-0">
                                                Tidak ada riwayat laporan panen yang telah divalidasi.
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
    // Inisialisasi DataTables untuk tabel History
    $(document).ready(function() {
        $('#tabelHistory').DataTable({
            // Urutkan berdasarkan kolom Waktu Panen (indeks 4) secara descending
            "order": [[ 4, "desc" ]], 
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
            }
        });
    });
</script>
@endpush