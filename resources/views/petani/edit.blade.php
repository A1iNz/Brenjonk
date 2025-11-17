@extends('layouts.app')

@section('content')
<div class="container text-light mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-dark">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Edit Data Petani</h3>
                    <a href="{{ route('petani') }}" class="btn btn-secondary">Kembali</a>
                </div>
                <div class="card-body text-light">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('petani.update', $petani->id) }}" id="tabelPetani" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label for="name" class="form-label">Username</label>
                            <input type="text" class="form-control rounded-md" id="name" name="name" value="{{ old('name', $petani->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama_lngkp" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control rounded-md" id="nama_lngkp" name="nama_lngkp" value="{{ old('nama_lngkp', $petani->nama_lngkp) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru (Opsional)</label>
                            <input type="password" class="form-control rounded-md" id="password" name="password">
                            <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control rounded-md" id="password_confirmation" name="password_confirmation">
                        </div>

                        <hr class="my-4">

                        <div class="mb-3">
                            <label for="kode" class="form-label">Kode Petani</label>
                            <input type="text" class="form-control rounded-md" id="kode" name="kode" value="{{ old('kode', $petani->kode) }}" placeholder="Contoh: PTN001" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection