<?php
// SELALU di baris paling atas
session_start();

// Halaman ini HANYA untuk role 'admin'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit;
}

// 1. Sertakan file koneksi
include 'koneksi.php';

// -----------------------------------------------------------------
// BLOK BARU: Proses Form Update Status (jika ada POST request)
// -----------------------------------------------------------------
$error_message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    
    // Pastikan ID dan status dikirim
    if (isset($_POST['id_pesanan']) && isset($_POST['status_po'])) {
        $id_pesanan_to_update = $_POST['id_pesanan'];
        $new_status = $_POST['status_po'];
        
        // Validasi status untuk keamanan
        $valid_statuses = ['Diproses', 'Selesai', 'Dibatalkan'];
        
        if (in_array($new_status, $valid_statuses)) {
            try {
                // Update database
                $sql_update = "UPDATE pesanan SET status_po = ? WHERE id_pesanan = ?";
                $stmt_update = $koneksi->prepare($sql_update);
                $stmt_update->bind_param("ss", $new_status, $id_pesanan_to_update);
                
                if ($stmt_update->execute()) {
                    $stmt_update->close();
                    // Redirect ke halaman ini lagi (GET) untuk cegah resubmit
                    // Ini disebut PRG Pattern (Post-Redirect-Get)
                    header("Location: detail_po.php?id=" . $id_pesanan_to_update . "&status=updated");
                    exit;
                } else {
                    $error_message = "Gagal memperbarui status: " . $stmt_update->error;
                }
                $stmt_update->close();

            } catch (Exception $e) {
                $error_message = "Terjadi error: " . $e->getMessage();
            }
        } else {
            $error_message = "Status yang dipilih tidak valid.";
        }
    } else {
        $error_message = "Data form tidak lengkap.";
    }
}
// --- AKHIR BLOK BARU ---


// 2. Validasi ID Pesanan dari URL (GET request)
if (!isset($_GET['id'])) {
    header("Location: dashboard_kelompoktani.php#pesanan&error=IDTidakDitemukan");
    exit;
}
$id_pesanan = $_GET['id'];

// 3. Query 1: Ambil data PO utama
$po = null;
$sql_po = "SELECT nama_konsumen, tgl_pesanan, total_harga, status_po 
           FROM pesanan 
           WHERE id_pesanan = ?";
$stmt_po = $koneksi->prepare($sql_po);
$stmt_po->bind_param("s", $id_pesanan);
$stmt_po->execute();
$result_po = $stmt_po->get_result();

if ($result_po->num_rows === 1) {
    // Data PO akan diambil di sini. Jika ada update (dari blok POST di atas),
    // data ini akan otomatis mengambil status yang BARU.
    $po = $result_po->fetch_assoc();
} else {
    die("Error: Pesanan tidak ditemukan.");
}
$stmt_po->close();

// 4. Query 2: Ambil data item-item di dalam PO
$sql_items = "SELECT pp.nama_produk, dp.jumlah_kg, dp.harga_subtotal
              FROM detail_pesanan dp
              JOIN produk_panen pp ON dp.id_produk = pp.id
              WHERE dp.id_pesanan = ?";
$stmt_items = $koneksi->prepare($sql_items);
$stmt_items->bind_param("s", $id_pesanan);
$stmt_items->execute();
$result_items = $stmt_items->get_result();

// Menentukan kelas CSS untuk status (akan digunakan 2x)
$status_class = '';
if ($po['status_po'] == 'Selesai') {
    $status_class = 'status-verified';
} elseif ($po['status_po'] == 'Diproses') {
    $status_class = 'status-processing';
} elseif ($po['status_po'] == 'Dibatalkan') {
    $status_class = 'status-rejected';
} else {
    $status_class = 'status-pending';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan <?php echo htmlspecialchars($id_pesanan); ?> - Program A2</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <style>
        /* (SEMUA CSS ANDA SAMA PERSIS SEPERTI SEBELUMNYA) */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        :root {
            --bg-dark: #1A1A1A; --bg-light-dark: #2A2A2A; --text-light: #F5F5F5;
            --text-medium: #AAAAAA; --accent-green: #30E0A0; --accent-red: #E74C3C;
            --accent-blue: #3498DB; --accent-yellow: #f1c40f; --border-color: #3A3A3A;
            --border-radius-modern: 16px; --transition: all 0.3s ease-in-out;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif; line-height: 1.6;
            background-color: var(--bg-dark); color: var(--text-medium);
        }
        h1, h2, h3, h4, h5, h6 { color: var(--text-light); font-weight: 600; }
        .dashboard-header {
            background-color: var(--bg-light-dark); border-bottom: 1px solid var(--border-color);
            padding: 20px 30px; display: flex; justify-content: space-between;
            align-items: center; height: 75px;
        }
        .logo { font-size: 1.8rem; font-weight: 700; color: var(--text-light); text-decoration: none; }
        .logo .highlight { color: var(--accent-green); }
        .user-nav { display: flex; align-items: center; gap: 15px; }
        .user-welcome { font-size: 0.95rem; color: var(--text-light); }
        .user-welcome span { font-weight: 600; color: var(--accent-green); }
        .btn {
            display: inline-flex; align-items: center; gap: 8px; padding: 8px 20px;
            border-radius: 50px; text-decoration: none; font-weight: 600;
            cursor: pointer; transition: var(--transition); border: 2px solid transparent;
            font-size: 0.9rem;
        }
        .btn-primary { background-color: var(--accent-green); color: #1A1A1A; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 0 15px rgba(48, 224, 160, 0.4); }
        .btn-danger { background-color: var(--accent-red); color: var(--text-light); border-color: var(--accent-red); }
        .btn-danger:hover { background-color: #c0392b; border-color: #c0392b; }
        .btn-secondary { background-color: var(--accent-blue); color: var(--text-light); border-color: var(--accent-blue); }
        .btn-secondary:hover { background-color: #2980b9; border-color: #2980b9; }

        /* Style untuk halaman detail */
        .main-content-detail {
            padding: 40px;
            max-width: 1000px;
            margin: 0 auto;
        }
        .main-content-detail h2 {
            font-size: 2.2rem;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .card-box { 
            background-color: var(--bg-light-dark); 
            border: 1px solid var(--border-color); 
            border-radius: var(--border-radius-modern);
            margin-bottom: 30px;
        }

        .po-details {
            display: flex; flex-wrap: wrap;
            gap: 20px; padding: 30px;
        }
        .detail-item { flex: 1; min-width: 200px; }
        .detail-item h4 {
            color: var(--text-medium); font-size: 0.9rem;
            font-weight: 500; margin-bottom: 5px; text-transform: uppercase;
        }
        .detail-item p { color: var(--text-light); font-size: 1.1rem; font-weight: 600; }

        /* Tabel data (sama) */
        .data-table-wrapper { overflow-x: auto; }
        .data-table { width: 100%; border-collapse: collapse; text-align: left; }
        .data-table th {
            background-color: var(--bg-dark); color: var(--text-light);
            padding: 15px 20px; font-size: 0.9rem; text-transform: uppercase;
        }
        .data-table tr:first-child th:first-child { border-top-left-radius: var(--border-radius-modern); }
        .data-table tr:first-child th:last-child { border-top-right-radius: var(--border-radius-modern); }
        .data-table td { padding: 15px 20px; border-bottom: 1px solid var(--border-color); }
        .data-table tr:last-child td { border-bottom: none; }
        
        .data-table tfoot td {
            font-weight: 700; font-size: 1.2rem; color: var(--text-light);
            text-align: right; border-top: 1px solid var(--border-color);
        }
        .data-table tfoot td:first-child { text-align: left; }
        
        /* Status (sama) */
        .status { padding: 5px 12px; border-radius: 20px; font-weight: 600; font-size: 0.85rem; display: inline-block; }
        .status-pending { background-color: rgba(241, 196, 15, 0.1); color: var(--accent-yellow); }
        .status-verified { background-color: rgba(48, 224, 160, 0.1); color: var(--accent-green); }
        .status-processing { background-color: rgba(52, 152, 219, 0.1); color: var(--accent-blue); }
        .status-rejected { background-color: rgba(231, 76, 60, 0.1); color: var(--accent-red); }

        /* --- CSS BARU --- */
        /* Style untuk notifikasi sukses/error */
        .notification {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 10px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .notif-success {
            background-color: rgba(48, 224, 160, 0.1);
            color: var(--accent-green);
            border: 1px solid var(--accent-green);
        }
        .notif-error {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--accent-red);
            border: 1px solid var(--accent-red);
        }

        /* Style untuk form update status */
        .form-update-status {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 30px;
            flex-wrap: wrap; /* Biar rapi di HP */
        }
        .form-update-status label {
            font-size: 1rem;
            font-weight: 500;
            color: var(--text-medium);
        }
        .form-update-status select {
            background-color: var(--bg-dark);
            color: var(--text-light);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 12px;
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            flex-grow: 1; /* Biar select-nya memanjang */
        }
        /* --- AKHIR CSS BARU --- */

    </style>
</head>
<body>

    <header class="dashboard-header">
        <a href="dashboard_kelompoktani.php" class="logo">Program<span class="highlight">A2</span></a>
        <nav class="user-nav">
            <div class="user-welcome">
                Admin <span><?php echo htmlspecialchars($_SESSION['nama_petani']); ?></span>
            </div>
            <a href="dashboard_kelompoktani.php#pesanan" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
            <a href="logout.php" class="btn btn-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </nav>
    </header>

    <main class="main-content-detail">
        
        <?php if (isset($_GET['status']) && $_GET['status'] == 'updated'): ?>
            <div class="notification notif-success">
                <i class="fas fa-check-circle"></i> Status pesanan berhasil diperbarui.
            </div>
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
            <div class="notification notif-error">
                <i class="fas fa-times-circle"></i> <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        <h2><i class="fas fa-file-invoice"></i> Detail Pesanan <?php echo htmlspecialchars($id_pesanan); ?></h2>

        <div class="card-box po-details">
            <div class="detail-item">
                <h4>Konsumen</h4>
                <p><?php echo htmlspecialchars($po['nama_konsumen']); ?></p>
            </div>
            <div class="detail-item">
                <h4>Tanggal Pesan</h4>
                <p><?php echo date('d M Y', strtotime($po['tgl_pesanan'])); ?></p>
            </div>
            <div class="detail-item">
                <h4>Status Saat Ini</h4>
                <p><span class="status <?php echo $status_class; ?>">
                    <?php echo htmlspecialchars($po['status_po']); ?>
                </span></p>
            </div>
        </div>

        <div class="card-box">
            <form action="detail_po.php?id=<?php echo htmlspecialchars($id_pesanan); ?>" method="POST" class="form-update-status">
                <label for="status_po">Ubah Status:</label>
                <select name="status_po" id="status_po">
                    <option value="Diproses" <?php if ($po['status_po'] == 'Diproses') echo 'selected'; ?>>
                        Diproses
                    </option>
                    <option value="Selesai" <?php if ($po['status_po'] == 'Selesai') echo 'selected'; ?>>
                        Selesai
                    </option>
                    <option value="Dibatalkan" <?php if ($po['status_po'] == 'Dibatalkan') echo 'selected'; ?>>
                        Dibatalkan
                    </option>
                </select>
                
                <input type="hidden" name="id_pesanan" value="<?php echo htmlspecialchars($id_pesanan); ?>">
                
                <button type="submit" name="update_status" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Status
                </button>
            </form>
        </div>
        <h3>Item Dipesan</h3>
        <div class="card-box">
            <div class="data-table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Produk</th>
                            <th>Jumlah (Kg)</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result_items->num_rows > 0) {
                            $nomor = 1;
                            while ($item = $result_items->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?php echo $nomor++; ?>.</td>
                            <td><?php echo htmlspecialchars($item['nama_produk']); ?></td>
                            <td><?php echo htmlspecialchars($item['jumlah_kg']); ?> Kg</td>
                            <td>Rp <?php echo number_format($item['harga_subtotal'], 0, ',', '.'); ?></td>
                        </tr>
                        <?php
                            } // Akhir while
                        } else {
                            echo "<tr><td colspan='4' style='text-align: center;'>Tidak ada item detail untuk pesanan ini.</td></tr>";
                        }
                        $stmt_items->close();
                        $koneksi->close();
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3">Total Pesanan</td>
                            <td>Rp <?php echo number_format($po['total_harga'], 0, ',', '.'); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        
    </main>

</body>
</html>