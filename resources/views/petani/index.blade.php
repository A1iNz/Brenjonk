@extends('layouts.app')

@section('content')
<div class="container text-dark mt-5">
    
    {{-- Header Halaman --}}
    <div class="mb-4 p-3 bg-white rounded-md border border-primary shadow-sm">
        <h1 class="mb-0 fs-3 text-white">Daftar Petani Binaan 👨‍🌾</h1>
        <p class="text-white mb-0">Manajemen data petani yang terdaftar dalam program.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-11">
            
            {{-- Card menggunakan bg-white dan border-primary --}}
            <div class="card bg-white border-primary shadow-lg"> 
                {{-- Card Header menggunakan Dark Forest Green (bg-success) --}}
                <div class="card-header bg-success d-flex justify-content-between align-items-center text-white">
                    <h3 class="ms-1 mb-0"><i class="fa-solid fa-user-group me-2"></i> Daftar Petani Binaan Program A2</h3>
                    
                    {{-- Tombol Tambah Petani menggunakan Fresh Green (btn-primary) --}}
                    <a href="{{ route('petani.create') }}" class="btn btn-primary text-white">
                        <i class="fa-solid fa-user-plus me-1"></i> Tambah Petani Baru
                    </a>
                </div>
                
                <div class="card-body">
                    
                    {{-- Area Notifikasi --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Berhasil!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <div class="table-responsive mt-3">
                        {{-- Tabel diubah ke light theme dan text-dark --}}
                        <table id="tabelPetani" class="table table-striped table-hover align-middle text-dark">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Kode Petani</th>
                                    <th scope="col" class="text-start">Nama Lengkap</th>
                                    <th scope="col">Tanggal Bergabung</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($petanis as $item)
                                <tr>
                                    <td class="text-dark" scope="row">{{ $loop->iteration }}</td>
                                    <td>
                                        {{-- Badge diubah ke bg-primary (Fresh Green) dengan teks putih --}}
                                        <span class="badge bg-primary text-white">{{ $item->kode }}</span>
                                    </td>
                                    <td class="text-start text-dark">{{ $item->user->nama_lngkp }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->user->created_at)->format('d M Y') }}</td>
                                    
                                    {{-- Kolom Aksi --}}
                                    <td class="d-flex justify-content-center gap-2">
                                        {{-- Tombol Edit (Kuning) --}}
                                        <a href="{{ route('petani.edit', $item->id) }}" class="btn btn-sm btn-warning text-dark">
                                            <i class="fa-solid fa-pencil"></i> Edit
                                        </a>
                                        
                                        {{-- Form Hapus --}}
                                        <form action="{{ route('petani.destroy', $item->id) }}" method="POST" onsubmit="return confirm('❗ PERINGATAN! Apakah Anda yakin ingin menghapus petani {{ $item->user->nama_lngkp }}? Tindakan ini juga akan menghapus akun login terkait.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fa-solid fa-trash-can"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <div class="alert alert-secondary mb-0">
                                            Tidak ada data petani yang terdaftar saat ini.
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
        // Pastikan DataTables sudah dimuat
        $(document).ready(function() {
            $('#tabelPetani').DataTable({
                info: true,
                ordering: true,
                paging: true,
                // Menggunakan bahasa Indonesia yang diimpor di layouts/app.blade.php
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
                }
            });
        });
    </script>
@endpush