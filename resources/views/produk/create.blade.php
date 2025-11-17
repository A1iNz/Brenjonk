@extends('layouts.app')

@section('content')
<div class="container text-light mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-dark">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Tambah Produk Baru</h3>
                    <a href="{{ route('produk') }}" class="btn btn-secondary">Kembali</a>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('produk.store') }}" class="text-light" method="POST">
                        @csrf
                        <div class="mb-3 ">
                            <label for="nama" class="form-label">Nama Produk</label>
                            <input type="text" class="form-control rounded-md" id="nama" name="nama" value="{{ old('nama') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="kode" class="form-label">Kode Produk</label>
                            <input type="text"class="form-control rounded-md" id="kode" name="kode" id="kode" value="{{ old('kode') }}" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Simpan Produk</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection