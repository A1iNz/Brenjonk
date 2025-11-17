@extends('layouts.app')

@section('content')
<div class="container text-light mt-5">
    <div class="row justify-content-center text-center">
            <div class="col-md-11">
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
                            
                            <table id="tabelPetani" class="table table-dark table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Kode Petani</th>
                                    <th scope="col">Nama Lengkap</th>
                                    {{-- <th scope="col">Username</th> --}}
                                    <th scope="col">Tanggal Bergabung</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($petanis as $item)
                                <tr>
                                    <td scope="row">{{ $loop->iteration }}</td>
                                    <td>{{ $item->kode }}</td>
                                    <td>{{ $item->user->nama_lngkp }}</td>
                                    <td>{{ $item->user->created_at->format('d M Y') }}</td>
                                    <td class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('petani.edit', $item->id) }}" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen-to-square"></i>Edit</a>
                                        
                                        <form action="{{ route('petani.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus petani ini? Tindakan ini juga akan menghapus akun login terkait.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i>Hapus</button>
                                        </form>
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

@push('scripts')
    <script>
        new DataTable('#tabelPetani', {
            info: true,
            ordering: true,
            paging: true
        });
    </script>
@endpush