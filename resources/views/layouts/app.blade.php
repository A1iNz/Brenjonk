<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.5/css/dataTables.dataTables.css" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-black">
        <header class="p-3 px-5 text-bg-dark">
            <div class="container">
                <div class="d-flex flex-wrap align-items-between justify-content-between    ">
                    <a href="{{ route('dashboard') }}" class="mb-2 mb-lg-0 text-white text-decoration-none">
                        <img src="{{ asset('template/img/logo_desa.png') }}"
                        class="block h-9 w-auto fill-current text-gray-800" alt="">
                    </a>
                    <div class="text-end">
                        @auth
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="btn btn-danger">Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf</form>
                        @else
                            <a href="{{ route('login') }}" type="button" class="btn btn-outline-light me-2">Login</a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>
        <div class="d-flex">
            @if (auth()->user()->role == 'admin')
                <div class="d-flex flex-column flex-shrink-0 p-2 bg-dark text-light" style="width: 200px; height: 91vh;">
                    <ul class="nav nav-pills flex-column mb-auto fs-8">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link mt-3 text-white {{ request()->routeIs('dashboard') ? 'active' : '' }}" aria-current="page"><i class="fa-solid fa-house me-2"></i>Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('validasi') }}" class="nav-link mt-3 text-white {{ request()->routeIs('validasi') ? 'active' : '' }}" aria-current="page"><i class="fa-solid fa-list-check me-2"></i>Validasi Panen</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('history') }}" class="nav-link mt-3 text-white {{ request()->routeIs('history') ? 'active' : '' }}" aria-current="page"><i class="fa-solid fa-clock-rotate-left me-2"></i>History Validasi</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('stok') }}" class="nav-link mt-3 text-white {{ request()->routeIs('stok') ? 'active' : '' }}" aria-current="page"><i class="fa-solid fa-boxes-stacked me-2"></i>Stok Siap jual</a>
                        </li>
                        <hr class="divider mt-3"></hr>
                        <li class="nav-item">
                            <a href="{{ route('produk') }}" class="nav-link mt-3 text-white {{ request()->routeIs('produk') ? 'active' : '' }}" aria-current="page"><i class="fa-solid fa-book-open me-2"></i>Produk</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('petani') }}" class="nav-link mt-3 text-white {{ request()->routeIs('petani') ? 'active' : '' }}" aria-current="page"><i class="fa-solid fa-user-group me-2"></i>Petani</a>
                        </li>
                    </ul>
                </div>
            @endif

            <!-- Page Content -->
            <main class="flex-grow-1">
                @if (auth()->user()->role == 'petani')
                    <div class="container w-75 mt-5">
                        <div class="row ">
                            {{-- DIUBAH: Menggunakan d-flex untuk menyejajarkan ikon dan teks --}}
                            <div class="col card mx-3 p-4 bg-dark text-light d-flex flex-row align-items-center">
                                <i class="fa-solid fa-box-open fa-xl text-success"></i>
                                <div class="ms-4">
                                    <h3>Produk Terdaftar</h3>
                                    <p class="">1</p>
                                </div>
                            </div>
                            {{-- DIUBAH: Menggunakan d-flex untuk menyejajarkan ikon dan teks --}}
                            <div class="col card mx-3 p-4 bg-dark text-light d-flex flex-row align-items-center">
                                <i class="fa-solid fa-hourglass-half fa-xl text-success"></i>
                                <div class="ms-4">
                                    <h3>Menunggu Verifikasi</h3>
                                    <p class="">0</p>
                                </div>
                            </div>
                            {{-- DIUBAH: Menggunakan d-flex untuk menyejajarkan ikon dan teks --}}
                            <div class="col card mx-3 p-4 bg-dark text-light d-flex flex-row align-items-center">
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
                                        <form>
                                            <div class="form-group">
                                                <div class="row mb-3">
                                                    <label class="col-sm-6 col-form-label">Nama Produk</label>
                                                    <div class="col-sm-12">
                                                        <select class="form-select" aria-label="Default select example">
                                                            <option selected>Pilih Nama Produk</option>
                                                            <option value="Tomat">Tomat</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-sm-6 col-form-label">Estimasi Hasil Panen</label>
                                                    <div class=" col-sm-12 ">
                                                        <input type="number" class="input-group rounded-md form-control" id="estimasiPanen" placeholder="Estimasi Panen">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label class="col-sm-6 col-form-label">Estimasi Waktu Panen</label>
                                                    <div class="col-sm-12">
                                                        <input type="date" class=" rounded-md form-control" id="estimasiPanen" placeholder="Estimasi Panen">
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
                                    <table class="col display table" id="tabelPanen">
                                        <thead>
                                            <tr class="text-center">
                                                <th>No.</th>
                                                <th>Nama</th>
                                                <th>Hasil Panen</th>
                                                <th>Waktu Panen</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="text-center">
                                                <td>1</td>
                                                <td>Tomat</td>
                                                <td>20 Kg</td>
                                                <td>15/11/2025</td>
                                                <td>
                                                    <div>
                                                        <button class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></button>
                                                        <button class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif (auth()->user()->role != 'admin' && auth()->user()->role != 'petani')
                    <div class="p-3 bg-dark text-light">
                        {{ __("You're logged in!") }}
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/2.3.5/js/dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"> </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/680ecce84d.js" crossorigin="anonymous"></script>
<script>
    new DataTable('#tabelPanen', {
        info: false,
        ordering: false,
        paging: false
    });
</script>
</body>

</html>
