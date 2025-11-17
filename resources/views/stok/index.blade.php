@extends('layouts.app')

@section('content')
<div class="container text-light mt-5">
    <div class="row justify-content-center text-center">
        <div class="col-md-11">
            <div class="card bg-dark">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Stok Siap Jual</h3>
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
                                    <th scope="col">Produk</th>
                                    <th scope="col">Total Stok Siap Jual</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($produks as $produk)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $produk->nama }}</td>
                                        <td>{{ $produk->rencana_panens_sum_estimasi_hasil_panen ?? 0 }} kg</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center">Tidak ada stok siap jual.</td>
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
