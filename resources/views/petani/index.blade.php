@extends('layouts.app')

@section('content')
<div class="container text-light mt-5">
    <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card bg-dark">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3>Daftar Petani</h3>
                        <a href="{{ route('petani.create') }}" class="btn btn-primary">Tambah Petani</a>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                            @endif
                            
                            <table class="table table-dark table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Kode Petani</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Tanggal Bergabung</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($petanis as $item)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $item->kode }}</td>
                                    <td>{{ $item->user->name }}</td>
                                        <td>{{ $item->user->email }}</td>
                                        <td>{{ $item->user->created_at->format('d M Y') }}</td>
                                        <td>
                                            {{-- Tombol Aksi (Edit, Hapus) bisa ditambahkan di sini --}}
                                            <button class="btn btn-sm btn-warning">Edit</button>
                                            <button class="btn btn-sm btn-danger">Hapus</button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Belum ada data petani.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection