<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Bootstrap demo</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    </head>
    <body class="text-bg-success">
        <header class="p-2 text-bg-light">
            <div class="container">
                <div class="d-flex flex-wrap align-items-center justify-content-between justify-content-lg-between">
                    <a href="/" class="d-flex align-items-center mb-1 mb-lg-0 text-white text-decoration-none">
                        <img src="{{ asset('template/img/logo_desa.png') }}" class="h-1 w-1  text-gray-800" alt="" style="width: 60px">
                    </a>
                    <div class="text-end">
                        <a href="{{ route('login') }}" class="btn btn-outline-success me-2">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-primary">Sign-up</a>
                    </div>
                </div>
            </div>
        </header>
        <main class=" ">
            <section class="py-5 text-center container">
                <div class="row py-lg-6">
                    <div class="col-lg-4 col-md-4 mx-auto">
                        <h1 class="fw-bold">Hubungkan Petani, Kelompok Tani, dan Konsumen</h1>
                        <p class="fw-bold text-body-secondary">
                            Solusi pertanian terintegrasi untuk mengelola hasil panen melalui manajemen data real-time yang efisien.
                        </p>
                    </div>
                </div>
            </section>
        </main>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
    </body>
</html>