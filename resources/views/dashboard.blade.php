@extends('layouts.app')

@section('content')
    <div class="container w-75 mt-5 text-light">
        <div class="row ">
            {{-- DIUBAH: Menggunakan d-flex untuk menyejajarkan ikon dan teks --}}
            <div class="col card mx-3 p-3 bg-dark text-light d-flex flex-row align-items-center">
                <i class="fa-solid fa-box-open fa-xl text-success"></i>
                <div class="ms-4">
                    <h3>Produk Terdaftar</h3>
                    <p class="">1</p>
                </div>
            </div>
            {{-- DIUBAH: Menggunakan d-flex untuk menyejajarkan ikon dan teks --}}
            <div class="col card mx-3 p-3 bg-dark text-light d-flex flex-row align-items-center">
                <i class="fa-solid fa-hourglass-half fa-xl text-success"></i>
                <div class="ms-4">
                    <h3>Menunggu Verifikasi</h3>
                    <p class="">0</p>
                </div>
            </div>
            {{-- DIUBAH: Menggunakan d-flex untuk menyejajarkan ikon dan teks --}}
            <div class="col card ms-3 p-3 bg-dark text-light d-flex flex-row align-items-center">
                <i class="fa-solid fa-check-circle fa-xl text-success"></i>
                <div class="ms-4">
                    <h3>Produk Disetujui</h3>
                    <p class="">0</p>
                </div>
            </div>
        </div>

        <div class="row my-3">
            {{-- DIUBAH: Menggunakan d-flex untuk menyejajarkan ikon dan teks --}}
            <div class="col card mx-3 p-4 bg-dark text-light d-flex flex-row ">
                <i class="fa-solid fa-circle-plus text-secondary fa-xl mt-2"></i>
                <div class="ms-4">
                    <h3>Lapor Rencana Panen</h3>
                    <br>
                    <div>
                        <form action="{{ route('panen.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <div class="row mb-3">
                                    <label class="col-sm-6 col-form-label">Nama Produk</label>
                                    <div class="col-sm-12">
                                        <select class="form-select" name="produk_id" required>
                                            <option value="" selected disabled>Pilih Nama Produk</option>
                                            @foreach ($produks as $produk)
                                                <option value="{{ $produk->id }}">{{ $produk->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-6 col-form-label">Estimasi Hasil Panen</label>
                                    <div class=" col-sm-12 ">
                                        <div class="input-group">
                                            <input type="number" step="0.01" class="form-control" name="estimasi_hasil" placeholder="Contoh: 50.5" required>
                                            <span class="input-group-text">Kg</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-6 col-form-label">Estimasi Waktu Panen</label>
                                    <div class="col-sm-12">
                                        <input type="date" class="form-control" name="estimasi_waktu" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Kirim</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            {{-- DIUBAH: Menggunakan d-flex untuk menyejajarkan ikon dan teks --}}
            <div class="col card p-4 bg-dark text-light d-flex flex-row ">
                <div class="row">
                    <div class="col ">
                        <i class="col-md-1 fa-solid fa-list-check fa-xl text-secondary mt-2"></i>
                    </div>
                    <div class="col mt-1 col-md-10">
                        <h3>Riwayat Lapor Panen</h3>
                    </div>
                    <table class="col display table table-dark table-striped table-hover" id="tabelPanen">
                        {{-- Konten riwayat panen akan ditambahkan di sini nanti --}}
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection