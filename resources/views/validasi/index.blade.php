@extends('layouts.app')

@section('content')
<div class="container text-light mt-5">
    <div class="row justify-content-center text-center">
        <div class="col-md-11">
            <div class="card bg-dark">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Validasi Laporan Panen</h3>
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
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rencanaPanens as $rencana)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $rencana->petani->user->nama_lngkp }} ({{ $rencana->petani->kode }})</td>
                                        <td>{{ $rencana->produk->nama }}</td>
                                        <td>{{ $rencana->estimasi_hasil_panen }} kg</td>
                                        <td>{{ $rencana->estimasi_waktu_panen }}</td>
                                        <td class="d-flex justify-content-center gap-2">
                                            <form action="{{ route('validasi.update', $rencana->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('validasi.update', $rencana->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="reject">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    Reject
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada laporan panen yang menunggu validasi.</td>
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
