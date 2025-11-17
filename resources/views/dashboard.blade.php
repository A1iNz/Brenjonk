@extends('layouts.app')

@section('content')
    <div class="container mt-5 text-light gap-2" style="width: 80%;">
        <div class="row text center">
            {{-- DIUBAH: Menggunakan d-flex untuk menyejajarkan ikon dan teks --}}
            <div class="col card me-2 p-3 text-light bg-dark fw-bold d-flex flex-row align-items-center" style="">
                <i class="fa-solid fa-box-open fa-xl text-success"></i>
                <div class="ms-4">
                    <h3>Produk Terdaftar</h3> 
                    <p class="">{{ $jumlahProdukTerdaftar }}</p>
                </div>
            </div>
            {{-- DIUBAH: Menggunakan d-flex untuk menyejajarkan ikon dan teks --}}
            <div class="col card mx-2 p-3 text-light bg-dark d-flex flex-row align-items-center" style="">
                <i class="fa-solid fa-hourglass-half fa-xl text-success"></i>
                <div class="ms-4">
                    <h3>Menunggu Verifikasi</h3> 
                    <p class="">{{ $jumlahPending }}</p>
                </div>
            </div>
            {{-- DIUBAH: Menggunakan d-flex untuk menyejajarkan ikon dan teks --}}
            <div class="col card ms-2 p-3 text-light bg-dark d-flex flex-row align-items-center" style="">
                <i class="fa-solid fa-check-circle fa-xl text-success"></i>
                <div class="ms-4">
                    <h3>Produk Disetujui</h3> 
                    <p class="">{{ $jumlahApproved }}</p>
                </div>
            </div>
        </div>


        <div class="row my-4 gap-3">
            {{-- DIUBAH: Menggunakan d-flex untuk menyejajarkan ikon dan teks --}}
            <div class="col-md-4 card p-3 text-light bg-dark" style="width:30%">
                <div class="d-flex flex-row align-items-center mb-3">
                    <i class="fa-solid fa-circle-plus text-secondary fa-xl"></i>
                    <h3 class="ms-3 mb-0">Lapor Rencana Panen</h3>
                </div>
                <div>
                    <form action="{{ route('panen.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <div class="mb-3">
                                <label class="form-label">Nama Produk</label>
                                <div>
                                    <select class="form-select" name="produk_id" required>
                                        <option value="" selected disabled>Pilih Nama Produk</option>
                                        @foreach ($produks as $produk)
                                            <option value="{{ $produk->id }}">{{ $produk->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Estimasi Hasil Panen</label>
                                <div>
                                    <div class="input-group ">
                                        <input type="number" step="0.01" class="form-control rounded-md" name="estimasi_hasil" placeholder="Contoh: 50.5" required>
                                        <span class="input-group-text">Kg</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Estimasi Waktu Panen</label>
                                <div>
                                    <input type="date" class="form-control rounded-md" name="estimasi_waktu" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary text-light fw-bold" style="">Kirim</button>
                        </div>
                    </form>
                </div>
            </div>
            {{-- DIUBAH: Menggunakan d-flex untuk menyejajarkan ikon dan teks --}}
            <div class="col-md-8 card p-3 text-light bg-dark" style="width:68%">
                <div class="d-flex flex-row align-items-center mb-3">
                    <i class="fa-solid fa-list-check fa-xl text-secondary"></i>
                    <h3 class="ms-4 mb-0">Riwayat Lapor Panen</h3>
                </div>
                <table class="display table table-hover" id="tabelPanen" style="">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Hasil Panen</th>
                            <th>Waktu Panen</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($panens as $panen)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $panen->produk->nama }}</td>
                                <td>{{ $panen->estimasi_hasil_panen }} Kg</td>
                                <td>{{ \Carbon\Carbon::parse($panen->estimasi_waktu)->format('d M Y') }}</td>
                                <td>{{ $panen->status }}</td>
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
@endsection