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

<body class="font-sans antialiased ">
    {{-- Palet Warna: #D7C097, #E7DEAF, #73AF6F, #007E6E --}}
    <div class="min-h-screen bg-black">
        <header class="p-3 px-5 bg-dark ">
            <div class="container">
                <div class="d-flex flex-wrap align-items-between justify-content-between    ">
                    <a href="{{ route('dashboard') }}" class="mb-2 mb-lg-0 text-decoration-none">
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
                            <a href="{{ route('login') }}" type="button" class="btn btn-primary- me-2">Login</a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>
        <div class="d-flex">
            @if (auth()->user() && auth()->user()->role == 'admin')
                <!-- Sidebar Admin -->
                <div class="d-flex flex-column flex-shrink-0 p-3 bg-dark" style="width: 250px; min-height: 91vh;">
                    <ul class="nav nav-pills flex-column mb-auto text-light">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link mt-3 text-light {{ request()->routeIs('dashboard') ? 'active' : '' }}" aria-current="page"><i class="fa-solid fa-house me-2"></i>Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('validasi') }}" class="nav-link mt-3 text-light {{ request()->routeIs('validasi') ? 'active' : '' }}" aria-current="page"><i class="fa-solid fa-list-check me-2"></i>Validasi Panen</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('history') }}" class="nav-link mt-3 text-light {{ request()->routeIs('history') ? 'active' : '' }}" aria-current="page"><i class="fa-solid fa-clock-rotate-left me-2"></i>History Validasi</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('stok') }}" class="nav-link mt-3 text-light {{ request()->routeIs('stok') ? 'active' : '' }}" aria-current="page"><i class="fa-solid fa-boxes-stacked me-2"></i>Stok Siap jual</a>
                        </li>
                        <hr class="divider mt-3 text-light">
                        <li class="nav-item">
                            <a href="{{ route('produk') }}" class="nav-link mt-3 text-light {{ request()->routeIs('produk') ? 'active' : '' }}" aria-current="page"><i class="fa-solid fa-book-open me-2"></i>Produk</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('petani') }}" class="nav-link mt-3 text-light {{ request()->routeIs('petani') ? 'active' : '' }}" aria-current="page"><i class="fa-solid fa-user-group me-2"></i>Petani</a>
                        </li>
                    </ul>
                </div>
            @endif
            <!-- Main Content -->
            <main class="flex-grow-1 p-4 bg-black ">
                @yield('content')
            </main>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/2.3.5/js/dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"> </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/680ecce84d.js" crossorigin="anonymous"></script>
{{-- Stack untuk menampung skrip dari halaman lain --}}

@stack('scripts')
</body>

</html>
