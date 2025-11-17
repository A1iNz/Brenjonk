<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
    header("Location: login.html");
    exit;
}
include 'koneksi.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: superadmin_panel.php?message=ID user tidak valid");
    exit;
}
$id_user = $_GET['id'];

// Ambil data user dari DB (termasuk nama_petani)
$sql = "SELECT username, nama_petani, role FROM users WHERE id = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    header("Location: superadmin_panel.php?message=User tidak ditemukan");
    exit;
}
$user = $result->fetch_assoc();
$stmt->close();
$koneksi->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Program A2</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #1A1A1A; --bg-light-dark: #2A2A2A; --text-light: #F5F5F5;
            --text-medium: #AAAAAA; --accent-green: #30E0A0; --accent-red: #E74C3C;
            --accent-blue: #3498DB; --accent-yellow: #f1c40f; --border-color: #3A3A3A;
            --border-radius-modern: 16px; --transition: all 0.3s ease-in-out;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-dark); color: var(--text-medium); line-height: 1.6; }
        h1, h2, h3 { color: var(--text-light); font-weight: 600; }
        .dashboard-header {
            background-color: var(--bg-light-dark); border-bottom: 1px solid var(--border-color);
            padding: 20px 30px; display: flex; justify-content: space-between; align-items: center;
        }
        .logo { font-size: 1.8rem; font-weight: 700; color: var(--text-light); text-decoration: none; }
        .logo .highlight { color: var(--accent-green); }
        .user-nav { display: flex; align-items: center; gap: 15px; }
        .btn {
            display: inline-flex; align-items: center; gap: 8px; padding: 8px 20px;
            border-radius: 50px; text-decoration: none; font-weight: 600;
            cursor: pointer; transition: var(--transition); border: 2px solid transparent; font-size: 0.9rem;
        }
        .btn-danger { background-color: var(--accent-red); color: var(--text-light); border-color: var(--accent-red); }
        .btn-danger:hover { background-color: #c0392b; border-color: #c0392b; }
        .btn-primary { background-color: var(--accent-green); color: #1A1A1A; }
        .btn-primary:hover { transform: translateY(-2px); }
        .btn-secondary { background-color: var(--accent-blue); color: var(--text-light); }
        .btn-secondary:hover { background-color: #2980b9; }

        .container { max-width: 700px; margin: 40px auto; padding: 0 20px; }
        .card-box {
            background-color: var(--bg-light-dark); border: 1px solid var(--border-color);
            border-radius: var(--border-radius-modern); margin-bottom: 30px;
        }
        .card-header { padding: 20px 30px; border-bottom: 1px solid var(--border-color); }
        .card-body { padding: 30px; }

        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 500; margin-bottom: 8px; color: var(--text-medium); }
        .form-group input, .form-group select {
            width: 100%; padding: 12px; background-color: var(--bg-dark);
            border: 1px solid var(--border-color); border-radius: 8px;
            color: var(--text-light); font-size: 1rem;
        }
        .form-group input:focus { border-color: var(--accent-green); outline: none; }
        .form-group input[readonly] { background-color: #222; color: var(--text-medium); }
    </style>
</head>
<body>
    <header class="dashboard-header">
        <a href="superadmin_panel.php" class="logo">Super<span class="highlight">Admin</span></a>
        <nav class="user-nav">
            <a href="superadmin_panel.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="logout.php" class="btn btn-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </nav>
    </header>

    <main class="container">
        <div class="card-box">
            <div class="card-header">
                <h3><i class="fas fa-user-edit"></i> Edit User: <?php echo htmlspecialchars($user['username']); ?></h3>
            </div>
            <div class="card-body">
                <form action="superadmin_aksi.php" method="POST">
                    <input type="hidden" name="aksi" value="edit">
                    <input type="hidden" name="id" value="<?php echo $id_user; ?>">
                    
                    <div class="form-group">
                        <label>Username (Tidak bisa diubah)</label>
                        <input type="text" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="nama_petani">Nama Petani / Nama Lengkap</label>
                        <input type="text" id="nama_petani" name="nama_petani" value="<?php echo htmlspecialchars($user['nama_petani'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select id="role" name="role">
                            <option value="petani" <?php if($user['role'] == 'petani') echo 'selected'; ?>>
                                Petani
                            </option>
                            <option value="admin" <?php if($user['role'] == 'admin') echo 'selected'; ?>>
                                Admin (Kelompok Tani)
                            </option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password Baru (Kosongkan jika tidak ingin diubah)</label>
                        <input type="password" id="password" name="password">
                    </div>
                    
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                    </form>
            </div>
        </div>
    </main>
</body>
</html>