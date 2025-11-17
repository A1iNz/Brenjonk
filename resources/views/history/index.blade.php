@extends('layouts.app')

@section('content')
<div class="container text-light mt-5">
    <div class="row justify-content-center text-center">
        <div class="col-md-11">
            <div class="card bg-dark">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Riwayat Laporan Panen</h3>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="table table-dark table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Petani</th>
                                    <th scope="col">Produk</th>
                                    <th scope="col">Estimasi Hasil</th>
                                    <th scope="col">Estimasi Waktu</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($panens as $panen)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $panen->petani->user->nama_lngkp ?? 'Admin' }} ({{ $panen->petani->kode ?? 'N/A' }})</td>
                                        <td>{{ $panen->produk->nama }}</td>
                                        <td>{{ $panen->estimasi_hasil_panen }} kg</td>
                                        <td>{{ $panen->estimasi_waktu_panen }}</td>
                                        <td>
                                            @if($panen->status === 'pending')
                                                <span class="badge bg-warning">{{ $panen->status }}</span>
                                            @elseif($panen->status === 'approved')
                                                <span class="badge bg-success">{{ $panen->status }}</span>
                                            @elseif($panen->status === 'reject')
                                                <span class="badge bg-danger">{{ $panen->status }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada riwayat laporan panen.</td>
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
