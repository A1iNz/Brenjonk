<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Petani Cerdas Penanggungan') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.5/css/dataTables.dataTables.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- CSS KUSTOM TEMA BARU (Fresh & Modern Green) --}}
    <style>
        /* Palet Warna: Fresh Green (#4CAF50), Light Sage (#E8F5E9), Dark Forest (#2E7D32), White (#FFFFFF) */
        
        /* ---------------------------------- */
        /* 1. PRIMARY COLORS (Fresh Green) */
        /* ---------------------------------- */
        .btn-primary, .bg-primary, .nav-link.active, .border-primary {
            background-color: #4CAF50 !important;
            border-color: #4CAF50 !important;
            color: #FFFFFF !important; /* Teks putih di tombol hijau */
        }
        .btn-primary:hover {
            background-color: #388E3C !important; 
            border-color: #388E3C !important;
        }

        /* ---------------------------------- */
        /* 2. HEADER & MAIN TEXT */
        /* ---------------------------------- */
        .bg-dark, header {
            background-color: #FFFFFF !important; /* Putih Bersih seperti contoh */
            border-color: #E0E0E0 !important; /* Garis pembatas abu-abu tipis */
        }

        /* Mengganti warna teks utama (Dark Forest) */
        .text-light, .text-gray-800 { 
            color: #2E7D32 !important; /* Dark Forest Green */
        }
        
        /* Mengubah ikon hamburger menjadi Dark Forest */
        .btn-outline-light i {
            color: #2E7D32 !important; 
        }

        /* ---------------------------------- */
        /* 3. SIDEBAR */
        /* ---------------------------------- */
        #sidebarMenu {
            background-color: #E8F5E9 !important; /* Light Sage Green */
        }

        /* Teks default di sidebar (Dark Forest) */
        #sidebarMenu .nav-link {
            color: #2E7D32 !important; 
        }
        /* Teks di link aktif (Putih) */
        #sidebarMenu .nav-link.active {
            color: #FFFFFF !important; 
        }
        /* Hover state sidebar (Fresh Green) */
        #sidebarMenu .nav-link:hover:not(.active) {
            background-color: #C8E6C9 !important; /* Hijau yang lebih pudar untuk hover */
        }
        
        /* Styling Divider di Sidebar (Dark Forest) */
        #sidebarMenu .divider {
            border-color: #A5D6A7 !important; 
        }

        /* ---------------------------------- */
        /* 4. BACKGROUND & INPUTS */
        /* ---------------------------------- */
        /* Warna latar belakang aplikasi utama (Putih Bersih) */
        .min-h-screen, main {
            background-color: #FFFFFF !important; 
        }

        /* Mengganti warna secondary (untuk form input) */
        .bg-secondary, .form-control {
            background-color: #F8F8F8 !important; /* Abu-abu Pudar */
            color: #2E7D32 !important; /* Teks Dark Forest di input */
        }
        
        /* Styling Toggle */
        .sidebar {
            transition: margin-left 0.3s ease-in-out;
        }
        .sidebar.collapsed {
            margin-left: -250px; 
        }
        .main-content-area {
            transition: margin-left 0.3s ease-in-out;
        }
        /* Styling untuk Success (digunakan di tombol dan badge) */
        .bg-success, .btn-success {
            background-color: #2E7D32 !important; /* Dark Forest Green */
            border-color: #2E7D32 !important;
            color: #FFFFFF !important;
        }
        .btn-success:hover {
            background-color: #1B5E20 !important; /* Lebih gelap saat hover */
            border-color: #1B5E20 !important;
        }
    </style>

</head>

<body class="font-sans antialiased">
    <div class="min-h-screen">
        
        <header class="p-3 px-5 bg-dark border-bottom"> 
            <div class="container-fluid"> 
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    
                    <div class="d-flex align-items-center">
                        {{-- 1. TOMBOL TOGGLE SIDEBAR BARU --}}
                        @if (auth()->user() && auth()->user()->role == 'admin')
                            <button class="btn btn-outline-light me-3" id="sidebarToggle" type="button">
                                <i class="fa-solid fa-bars"></i> 
                            </button>
                        @endif
                        
                        {{-- LOGO --}}
                        <a href="{{ route('dashboard') }}" class="mb-2 mb-lg-0 text-decoration-none">
                            <img src="{{ asset('template/img/logo_desa.png') }}"
                            class="block h-9 w-auto fill-current text-gray-800" alt="">
                        </a>
                    </div>
                    
                    {{-- 2. LOGOUT BUTTON (Dihapus dari sini dan dipindahkan ke Sidebar) --}}
                    <div class="text-end">
                        @auth
                            {{-- Jika bukan Admin, tampilkan tombol Logout di sini (opsional) --}}
                            @if (auth()->user()->role != 'admin')
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                    class="btn btn-danger">Logout</a>
                            @endif
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf</form>
                        @else
                            <a href="{{ route('login') }}" type="button" class="btn btn-primary me-2">Login</a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>
        
        <div class="d-flex">
            
            {{-- 3. SIDEBAR (Light Sage Green) --}}
            @if (auth()->user() && auth()->user()->role == 'admin')
                <div id="sidebarMenu" class="sidebar d-flex flex-column flex-shrink-0 p-3" style="width: 250px; min-height: 91vh;">
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
                        <hr class="divider mt-3">
                        <li class="nav-item">
                            <a href="{{ route('produk') }}" class="nav-link mt-3 text-light {{ request()->routeIs('produk') ? 'active' : '' }}" aria-current="page"><i class="fa-solid fa-book-open me-2"></i>Produk</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('petani') }}" class="nav-link mt-3 text-light {{ request()->routeIs('petani') ? 'active' : '' }}" aria-current="page"><i class="fa-solid fa-user-group me-2"></i>Petani</a>
                        </li>
                    </ul>
                    
                    <hr class="divider mt-3">

                    {{-- TOMBOL LOGOUT BARU DI SIDEBAR (Sesuai permintaan) --}}
                    @auth
                        <div class="mt-auto">
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="btn btn-sm btn-danger w-100 fw-bold">
                                <i class="fa-solid fa-right-from-bracket me-2"></i> Log Out
                            </a>
                        </div>
                    @endauth
                </div>
            @endif
            
            {{-- 4. MAIN CONTENT (White) --}}
            <main id="mainContent" class="main-content-area flex-grow-1 p-4">
                @yield('content')
            </main>
        </div>
    </div>
    
    {{-- Scripts --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawR/hF
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/2.3.5/js/dataTables.js"></script>
    <script src="https://kit.fontawesome.com/680ecce84d.js" crossorigin="anonymous"></script>
    
    <script>
        // SCRIPT JAVASCRIPT UNTUK TOGGLE SIDEBAR
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebarMenu');
            // Toggle class 'collapsed' pada sidebar
            sidebar.classList.toggle('collapsed');
        });
        
        // Memastikan jQuery terdefinisi
        $(document).ready(function() {
             // Inisialisasi DataTables jika diperlukan di sini
        });
        
    </script>

    @stack('scripts')
</body>

</html>