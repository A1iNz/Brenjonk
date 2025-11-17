@extends('layouts.app')

@section('content')
<div class="container text-light mt-5">
    <div class="row justify-content-center">
        <div class="col-md-11">
            
            <div class="card bg-dark border-success shadow-lg"> <div class="card-header bg-success d-flex justify-content-between align-items-center text-white">
                    <h3 class="mb-0">👨‍🌾 Daftar Petani Binaan Program A2</h3>
                    
                    <a href="{{ route('petani.create') }}" class="btn btn-primary">
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
                        <table id="tabelPetani" class="table table-dark table-striped table-hover align-middle text-center">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Kode Petani</th>
                                    <th scope="col">Nama Lengkap</th>
                                    <th scope="col">Tanggal Bergabung</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($petanis as $item)
                                <tr>
                                    <td scope="row">{{ $loop->iteration }}</td>
                                    <td><span class="badge bg-info text-dark">{{ $item->kode }}</span></td>
                                    <td class="text-start">{{ $item->user->nama_lngkp }}</td>
                                    <td>{{ $item->user->created_at->format('d M Y') }}</td>
                                    
                                    {{-- Kolom Aksi --}}
                                    <td class="d-flex justify-content-center gap-2">
                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('petani.edit', $item->id) }}" class="btn btn-sm btn-warning">
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
        // Pastikan DataTables sudah dimuat (asumsi jQuery sudah ditambahkan di layouts/app.blade.php)
        $(document).ready(function() {
            new DataTable('#tabelPetani', {
                info: true,
                ordering: true,
                paging: true,
                // Opsi tambahan untuk dark theme (jika diperlukan)
                "dom": 'lfrtip',
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ entri",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    "paginate": {
                        "previous": "Sebelumnya",
                        "next": "Berikutnya"
                    }
                }
            });
        });
    </script>
@endpush