<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - Program A2</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #1A1A1A;
            --bg-light-dark: #2A2A2A;
            --text-light: #F5F5F5;
            --text-medium: #AAAAAA;
            --accent-green: #30E0A0;
            --border-color: #3A3A3A;
            --border-radius-modern: 16px;
            --transition: all 0.3s ease-in-out;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-medium);
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            min-height: 100vh; 
        }
        
        /* === HEADER === */
        .header-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            background-color: var(--bg-light-dark); /* Latar belakang solid */
            border-bottom: 1px solid var(--border-color); /* Garis batas bawah */
            width: 100%;
            z-index: 10;
        }
        .logo-desa {
            height: 60px; 
            width: auto;
        }
        .btn {
            display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px;
            border-radius: 50px; text-decoration: none; font-weight: 600;
            cursor: pointer; border: 2px solid transparent; font-size: 0.9rem;
            transition: var(--transition);
        }
        .btn-secondary { 
            background-color: var(--bg-dark); /* Diubah jadi gelap */
            color: var(--text-light); 
            border-color: var(--border-color);
        }
        .btn-secondary:hover { 
            background-color: #333; 
            border-color: var(--text-medium); 
        }

        /* === HERO SECTION === */
        .hero {
            flex-grow: 1; 
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 40px;
            
            /* Gambar Background Hero */
            background-image: url('https://source.unsplash.com/1600x900/?farm,harvest,vegetables');
            background-size: cover;
            background-position: center;
            position: relative; 
        }
        
        .hero::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6); /* Overlay gelap */
            z-index: 1;
        }

        .hero h1, .hero p {
            position: relative;
            z-index: 2;
        }
        
        .hero h1 {
            font-size: 4rem; 
            font-weight: 700;
            color: var(--text-light);
            margin-bottom: 15px;
            max-width: 700px;
            line-height: 1.2;
        }
        .hero h1 span {
            color: var(--accent-green);
        }
        .hero p {
            font-size: 1.25rem;
            max-width: 500px;
            margin-bottom: 40px;
            color: #f0f0f0; 
        }
    </style>
</head>
<body>

    <header class="header-nav">
        <a href="index.php">
            <img src="images[1].jpg" alt="Logo Desa" class="logo-desa">
        </a>
        <nav>
            <a href="login.html" class="btn btn-secondary">
                <i class="fas fa-user-shield"></i> Login Petani / Admin
            </a>
        </nav>
    </header>

    <main class="hero">
        <h1>Hubungkan Petani, Kelompok Tani, dan <span>Konsumen</span></h1>
        <p>Solusi pertanian terintegrasi untuk mengelola hasil panen melalui manajemen data real-time yang efisien.</p>
        
        </main>

</body>
</html>