@extends('layouts.app')

@section('content')
<div class="container text-light mt-5">
    <div class="row justify-content-center">
        <div class="col-md-11">
            
            <div class="card bg-light border-primary shadow-lg"> <!-- Ganti bg-dark jadi bg-light -->
                
                <div class="card-header bg-primary d-flex justify-content-between align-items-center text-white">
                    <h3 class="mb-0">✅ Laporan Panen Menunggu Validasi</h3>
                </div>
                
                <div class="card-body p-4">
                    
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Berhasil!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive mt-3">
                        <table id="tabelValidasi" class="table table-striped table-hover align-middle text-center"> <!-- Hapus table-dark -->
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 5%;">No.</th>
                                    <th scope="col" class="text-start">Petani Pelapor</th>
                                    <th scope="col">Komoditas</th>
                                    <th scope="col">Estimasi Hasil</th>
                                    <th scope="col">Estimasi Waktu</th>
                                    <th scope="col" style="width: 20%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rencanaPanens as $rencana)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-start">
                                            <i class="fa-solid fa-user-tag me-1 text-info"></i> 
                                            {{ $rencana->petani->user->nama_lngkp ?? 'N/A' }} 
                                            <br><span class="badge bg-secondary text-dark">{{ $rencana->petani->kode ?? 'Kode N/A' }}</span>
                                        </td>
                                        <td>{{ $rencana->produk->nama ?? 'Produk N/A' }}</td>
                                        <td><span class="badge bg-primary text-white">{{ number_format($rencana->estimasi_hasil_panen, 2, ',', '.') }} kg</span></td>
                                        <td>{{ \Carbon\Carbon::parse($rencana->estimasi_waktu_panen)->format('d M Y') }}</td>
                                        
                                        {{-- Tombol Aksi --}}
                                        <td class="d-flex justify-content-center gap-2">
                                            {{-- Form Approve --}}
                                            <form action="{{ route('validasi.update', $rencana->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="btn btn-sm btn-success" title="Setujui Laporan">
                                                    <i class="fa-solid fa-check"></i> Approve
                                                </button>
                                            </form>
                                            
                                            {{-- Form Reject --}}
                                            <form action="{{ route('validasi.update', $rencana->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="reject">
                                                <button type="submit" class="btn btn-sm btn-danger" title="Tolak Laporan">
                                                    <i class="fa-solid fa-xmark"></i> Reject
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="alert alert-info mb-0">
                                                🎉 Tidak ada laporan panen baru yang menunggu validasi saat ini.
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
        $(document).ready(function() {
            new DataTable('#tabelValidasi', {
                info: true,
                ordering: true,
                paging: true,
                "language": {
                    "search": "Cari Laporan:",
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