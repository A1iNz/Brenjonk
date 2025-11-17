<?php
// Mulai session
session_start();

// Cek apakah pengguna sudah login atau belum
// Jika belum, lempar kembali ke halaman login
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.html");
    exit;
}

// Jika lolos, pengguna sudah login.
// Kita bisa sapa pengguna dengan nama dari session
$username = $_SESSION["username"];

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Program A2</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        :root {
            --bg-dark: #1A1A1A;
            --bg-light-dark: #2A2A2A;
            --text-light: #F5F5F5;
            --text-medium: #AAAAAA;
            --accent-green: #30E0A0;
            --border-color: #3A3A3A;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-light);
            padding: 40px;
            text-align: center;
        }
        h1 {
            color: var(--accent-green);
            font-size: 3rem;
        }
        p {
            font-size: 1.2rem;
            color: var(--text-medium);
        }
        a {
            color: var(--accent-green);
            text-decoration: none;
            font-weight: 600;
            margin-top: 30px;
            display: inline-block;
            border: 2px solid var(--accent-green);
            padding: 10px 20px;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        a:hover {
            background-color: var(--accent-green);
            color: var(--bg-dark);
        }
    </style>
</head>
<body>
    
    <h1>Selamat Datang, <?php echo htmlspecialchars($username); ?>!</h1>
    <p>Anda telah berhasil login ke Dashboard Program A2.</p>
    <p>Ini adalah halaman yang dilindungi. Hanya pengguna yang sudah login yang bisa melihat ini.</p>
    
    <a href="logout.php">Logout</a>

</body>
</html>