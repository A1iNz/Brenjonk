<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
    header("Location: login.html");
    exit;
}
include 'koneksi.php';
$message = $_GET['message'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superadmin Panel - Program A2</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* (CSS Anda sama persis) */
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

        .container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
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
        
        .data-table { width: 100%; border-collapse: collapse; text-align: left; }
        .data-table th { background-color: var(--bg-dark); padding: 15px 20px; }
        .data-table td { padding: 15px 20px; border-bottom: 1px solid var(--border-color); }
        .data-table tr:last-child td { border-bottom: none; }
        .action-buttons { display: flex; gap: 10px; }
        .message {
            padding: 15px; border-radius: 8px; margin-bottom: 20px;
            background-color: rgba(48, 224, 160, 0.1); color: var(--accent-green);
            border: 1px solid var(--accent-green);
        }
    </style>
</head>
<body>

    <header class="dashboard-header">
        <a href="superadmin_panel.php" class="logo">Super<span class="highlight">Admin</span></a>
        <nav class="user-nav">
            <a href="logout.php" class="btn btn-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </nav>
    </header>

    <main class="container">
        <h1>Manajemen User</h1>

        <?php if (!empty($message)): ?>
            <div class="message"><?php echo htmlspecialchars(urldecode($message)); ?></div>
        <?php endif; ?>

        <div class="card-box">
            <div class="card-header"><h3><i class="fas fa-user-plus"></i> Tambah User Baru</h3></div>
            <div class="card-body">
                <form action="superadmin_aksi.php" method="POST">
                    <input type="hidden" name="aksi" value="tambah">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="nama_petani">Nama Petani / Nama Lengkap</label>
                        <input type="text" id="nama_petani" name="nama_petani" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select id="role" name="role">
                            <option value="petani">Petani</option>
                            <option value="admin">Admin (Kelompok Tani)</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah User</button>
                </form>
            </div>
        </div>

        <div class="card-box">
            <div class="card-header"><h3><i class="fas fa-users"></i> Daftar User</h3></div>
            <div class="card-body" style="padding:0;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Nama Petani</th>
                            <th>Role</th>
                            <th>Terdaftar Sejak</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_users = "SELECT id, username, nama_petani, role, created_at 
                                      FROM users 
                                      WHERE role != 'superadmin'
                                      ORDER BY id ASC";
                        $result_users = $koneksi->query($sql_users);

                        if ($result_users->num_rows > 0) {
                            while ($user = $result_users->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            
                            <td><?php echo htmlspecialchars($user['nama_petani'] ?? ''); ?></td>
                            
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                            <td><?php echo date('d M Y', strtotime($user['created_at'])); ?></td>
                            <td class="action-buttons">
                                <a href="superadmin_edit.php?id=<?php echo $user['id']; ?>" class="btn btn-secondary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="superadmin_aksi.php?aksi=hapus&id=<?php echo $user['id']; ?>" 
                                   class="btn btn-danger" 
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus user <?php echo htmlspecialchars($user['username']); ?>?');">
                                    <i class="fas fa-trash"></i> Hapus
                                </a>
                            </td>
                        </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='6' style='text-align:center;'>Belum ada user terdaftar.</td></tr>";
                        }
                        $koneksi->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>