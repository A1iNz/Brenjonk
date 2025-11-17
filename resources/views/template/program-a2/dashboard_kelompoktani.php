<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html"); 
    exit;
}
include 'koneksi.php';
$nama_kelompok_tani = $_SESSION['nama_petani'] ?? '[Nama Admin]';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Kelompok Tani - Program A2</title>
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
        body { font-family: 'Inter', sans-serif; line-height: 1.6; background-color: var(--bg-dark); color: var(--text-medium); overflow: hidden; }
        h1, h2, h3, h4, h5, h6 { color: var(--text-light); font-weight: 600; }
        .dashboard-header { background-color: var(--bg-light-dark); border-bottom: 1px solid var(--border-color); padding: 20px 30px; display: flex; justify-content: space-between; align-items: center; height: 75px; }
        .logo { font-size: 1.8rem; font-weight: 700; color: var(--text-light); text-decoration: none; }
        .logo .highlight { color: var(--accent-green); }
        .user-nav { display: flex; align-items: center; gap: 15px; }
        .user-welcome { font-size: 0.95rem; color: var(--text-light); }
        .user-welcome span { font-weight: 600; color: var(--accent-green); }
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 8px 20px; border-radius: 50px; text-decoration: none; font-weight: 600; cursor: pointer; transition: var(--transition); border: 2px solid transparent; font-size: 0.9rem; }
        .btn-primary { background-color: var(--accent-green); color: #1A1A1A; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 0 15px rgba(48, 224, 160, 0.4); }
        .btn-danger { background-color: var(--accent-red); color: var(--text-light); border-color: var(--accent-red); }
        .btn-danger:hover { background-color: #c0392b; border-color: #c0392b; transform: translateY(-2px); }
        .btn-secondary { background-color: var(--accent-blue); color: var(--text-light); border-color: var(--accent-blue); }
        .btn-secondary:hover { background-color: #2980b9; border-color: #2980b9; transform: translateY(-2px); }
        .dashboard-container { display: grid; grid-template-columns: 240px 1fr; height: calc(100vh - 75px); }
        .sidebar { background-color: var(--bg-light-dark); border-right: 1px solid var(--border-color); padding: 30px 20px; display: flex; flex-direction: column; }
        .sidebar-nav { list-style: none; flex-grow: 1; }
        .sidebar-nav li a { display: flex; align-items: center; gap: 15px; padding: 15px; text-decoration: none; color: var(--text-medium); font-weight: 500; border-radius: 10px; margin-bottom: 5px; transition: var(--transition); }
        .sidebar-nav li a:hover { background-color: var(--bg-dark); color: var(--text-light); }
        .sidebar-nav li a.active { background-color: var(--bg-dark); color: var(--accent-green); font-weight: 600; }
        .sidebar-nav li a i { width: 20px; text-align: center; }
        .main-content { padding: 40px; overflow-y: auto; height: 100%; }
        .content-section { display: none; }
        .content-section.active { display: block; }
        .content-section h2 { font-size: 2.2rem; margin-bottom: 30px; display: flex; align-items: center; gap: 15px; }
        .card-box { background-color: var(--bg-light-dark); border: 1px solid var(--border-color); border-radius: var(--border-radius-modern); }
        .data-table-wrapper { overflow-x: auto; }
        .data-table { width: 100%; border-collapse: collapse; text-align: left; }
        .data-table th { background-color: var(--bg-dark); color: var(--text-light); padding: 15px 20px; font-size: 0.9rem; text-transform: uppercase; }
        .data-table tr:first-child th:first-child { border-top-left-radius: var(--border-radius-modern); }
        .data-table tr:first-child th:last-child { border-top-right-radius: var(--border-radius-modern); }
        .data-table td { padding: 15px 20px; border-bottom: 1px solid var(--border-color); }
        .data-table tr:last-child td { border-bottom: none; }
        .status { padding: 5px 12px; border-radius: 20px; font-weight: 600; font-size: 0.85rem; display: inline-block; }
        .status-pending { background-color: rgba(241, 196, 15, 0.1); color: var(--accent-yellow); }
        .status-verified { background-color: rgba(48, 224, 160, 0.1); color: var(--accent-green); }
        .status-processing { background-color: rgba(52, 152, 219, 0.1); color: var(--accent-blue); }
        .status-rejected { background-color: rgba(231, 76, 60, 0.1); color: var(--accent-red); }
        .action-buttons { display: flex; gap: 10px; }
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.7); z-index: 1000;
            display: none; justify-content: center; align-items: center; backdrop-filter: blur(5px);
            opacity: 0; transition: opacity 0.3s ease;
        }
        .modal-overlay.show { display: flex; opacity: 1; }
        .modal-box {
            background-color: var(--bg-light-dark);
            border-radius: var(--border-radius-modern);
            border: 1px solid var(--border-color); padding: 30px;
            width: 90%; max-width: 500px; z-index: 1001;
            transform: scale(0.95); transition: transform 0.3s ease;
        }
        .modal-overlay.show .modal-box { transform: scale(1); }
        .modal-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 25px; padding-bottom: 10px;
            border-bottom: 1px solid var(--border-color);
        }
        .modal-header h3 { margin: 0; color: var(--text-light); }
        .close-btn { font-size: 1.8rem; color: var(--text-medium); cursor: pointer; background: none; border: none; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 500; margin-bottom: 8px; color: var(--text-medium); }
        .form-group input {
            width: 100%; padding: 12px; background-color: var(--bg-dark);
            border: 1px solid var(--border-color); border-radius: 8px;
            color: var(--text-light); font-size: 1rem;
        }
        .form-group input:read-only, .form-group input[readonly] { background-color: #222; color: var(--text-medium); }
        @media (max-width: 992px) { .dashboard-container { grid-template-columns: 1fr; height: auto; } body { overflow: auto; } .sidebar { border-right: none; border-bottom: 1px solid var(--border-color); padding: 15px; } .sidebar-nav { display: flex; justify-content: center; gap: 10px; } .sidebar-nav li a { padding: 10px 15px; margin-bottom: 0; } .sidebar-nav li a span { display: none; } .main-content { padding: 30px 20px; } }
        @media (max-width: 768px) { .user-welcome { display: none; } .content-section h2 { font-size: 1.8rem; } }
    </style>
</head>
<body>

    <header class="dashboard-header">
        <a href="dashboard_kelompoktani.php" class="logo">Program<span class="highlight">A2</span></a>
        <nav class="user-nav">
            <div class="user-welcome">
                Selamat Datang <span><?php echo htmlspecialchars($nama_kelompok_tani); ?></span>
            </div>
            <a href="logout.php" class="btn btn-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </nav>
    </header>

    <div class="dashboard-container">
        
        <nav class="sidebar">
            <ul class="sidebar-nav">
                <li><a href="#validasi" class="nav-link active"><i class="fas fa-tasks"></i><span>Validasi Panen</span></a></li>
                <li><a href="#riwayat" class="nav-link"><i class="fas fa-history"></i><span>Riwayat Validasi</span></a></li>
                <li><a href="#stok" class="nav-link"><i class="fas fa-boxes"></i><span>Stok Siap Jual</span></a></li>
            </ul>
        </nav>

        <main class="main-content">
            <section id="validasi" class="content-section active">
                <h2><i class="fas fa-tasks"></i> Validasi Rencana Panen</h2>
                <div class="card-box">
                    <div class="data-table-wrapper">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Nama Petani</th>
                                    <th>Estimasi (Kg)</th>
                                    <th>Tgl. Panen</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql_verif = "SELECT p.id, p.nama_produk, p.jumlah_kg, p.tgl_panen, u.username
                                            FROM produk_panen p
                                            JOIN users u ON p.petani_id = u.id
                                            WHERE p.status = 'Menunggu'
                                            ORDER BY p.tgl_panen ASC";
                                
                                $stmt_verif = $koneksi->prepare($sql_verif);
                                if ($stmt_verif === false) {
                                    echo "<tr><td colspan='6'>Error: " . htmlspecialchars($koneksi->error) . "</td></tr>";
                                } else {
                                    $stmt_verif->execute();
                                    $result_verif = $stmt_verif->get_result();
                                    if ($result_verif->num_rows === 0) {
                                        echo "<tr><td colspan='6' style='text-align: center;'>Tidak ada laporan panen yang menunggu validasi.</td></tr>";
                                    } else {
                                        while ($row_verif = $result_verif->fetch_assoc()) {
                                            $tanggal_formatted = date('d M Y', strtotime($row_verif['tgl_panen']));
                                ?>
                                <tr data-id="<?php echo $row_verif['id']; ?>" 
                                    data-produk="<?php echo htmlspecialchars($row_verif['nama_produk']); ?>"
                                    data-petani="<?php echo htmlspecialchars($row_verif['username']); ?>"
                                    data-jumlah="<?php echo htmlspecialchars($row_verif['jumlah_kg']); ?>">
                                    <td><?php echo htmlspecialchars($row_verif['nama_produk']); ?></td>
                                    <td><?php echo htmlspecialchars($row_verif['username']); ?></td>
                                    <td><?php echo htmlspecialchars($row_verif['jumlah_kg']); ?> Kg</td>
                                    <td><?php echo $tanggal_formatted; ?></td>
                                    <td><span class="status status-pending">Menunggu</span></td>
                                    <td class="action-buttons">
                                        <button class="btn btn-primary btn-setujui">Setujui</button>
                                        <a href="verifikasi_aksi.php?id=<?php echo $row_verif['id']; ?>&aksi=tolak" class="btn btn-danger">Tolak</a>
                                    </td>
                                </tr>
                                <?php
                                        } 
                                    } 
                                    $stmt_verif->close();
                                } 
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
            
            <section id="riwayat" class="content-section">
                <h2><i class="fas fa-history"></i> Riwayat Validasi</h2>
                <div class="card-box">
                    <div class="data-table-wrapper">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Petani</th>
                                    <th>Jumlah (Kg)</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Query diubah: p.harga_per_kg dihapus
                                $sql_log = "SELECT p.nama_produk, p.jumlah_kg, p.tgl_panen, p.status, u.username
                                            FROM produk_panen p
                                            JOIN users u ON p.petani_id = u.id
                                            WHERE p.status IN ('Tersedia', 'Ditolak')
                                            ORDER BY p.id DESC";
                                
                                $stmt_log = $koneksi->prepare($sql_log);
                                if ($stmt_log === false) {
                                    // Colspan diubah dari 5 menjadi 4
                                    echo "<tr><td colspan='4'>Error: " . htmlspecialchars($koneksi->error) . "</td></tr>";
                                } else {
                                    $stmt_log->execute();
                                    $result_log = $stmt_log->get_result();
                                    if ($result_log->num_rows === 0) {
                                        // Colspan diubah dari 5 menjadi 4
                                        echo "<tr><td colspan='4' style='text-align: center;'>Belum ada riwayat validasi.</td></tr>";
                                    } else {
                                        while ($row_log = $result_log->fetch_assoc()) {
                                            $status_class = 'status-rejected'; // Default 'Ditolak'
                                            if ($row_log['status'] == 'Tersedia') {
                                                $status_class = 'status-verified';
                                            }
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row_log['nama_produk']); ?></td>
                                    <td><?php echo htmlspecialchars($row_log['username']); ?></td>
                                    <td><?php echo htmlspecialchars($row_log['jumlah_kg']); ?> Kg</td>
                                    <td><span class="status <?php echo $status_class; ?>"><?php echo htmlspecialchars($row_log['status']); ?></span></td>
                                </tr>
                                <?php
                                        } 
                                    } 
                                    $stmt_log->close();
                                } 
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
            <section id="stok" class="content-section">
                <h2><i class="fas fa-boxes"></i> Rekap Stok Siap Jual</h2>
                <div class="card-box">
                    <div class="data-table-wrapper">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Petani</th>
                                    <th>Stok (Kg)</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql_inv = "SELECT p.nama_produk, p.jumlah_kg, p.tgl_panen, p.status, u.username
                                            FROM produk_panen p
                                            JOIN users u ON p.petani_id = u.id
                                            WHERE p.status = 'Tersedia'
                                            ORDER BY p.tgl_panen ASC";
                                
                                $stmt_inv = $koneksi->prepare($sql_inv);
                                if ($stmt_inv === false) {
                                    echo "<tr><td colspan='4'>Error: " . htmlspecialchars($koneksi->error) . "</td></tr>";
                                } else {
                                    $stmt_inv->execute();
                                    $result_inv = $stmt_inv->get_result();
                                    if ($result_inv->num_rows === 0) {
                                        echo "<tr><td colspan='4' style='text-align: center;'>Stok siap jual masih kosong.</td></tr>";
                                    } else {
                                        while ($row_inv = $result_inv->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row_inv['nama_produk']); ?></td>
                                    <td><?php echo htmlspecialchars($row_inv['username']); ?></td>
                                    <td><?php echo htmlspecialchars($row_inv['jumlah_kg']); ?> Kg</td>
                                    <td><span class="status status-verified">Tersedia</span></td>
                                </tr>
                                <?php
                                        } 
                                    } 
                                    $stmt_inv->close();
                                } 
                                $koneksi->close(); 
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <div id="validasi-modal" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-header">
                <h3><i class="fas fa-check-circle"></i> Konfirmasi Persetujuan</h3>
                <button id="close-modal-btn" class="close-btn">&times;</button>
            </div>
            
            <form action="verifikasi_aksi.php" method="POST">
                <input type="hidden" name="id" id="modal-id">
                <input type="hidden" name="aksi" value="setujui">
                
                <div class="form-group">
                    <label>Produk</label>
                    <input type="text" id="modal-produk" readonly>
                </div>
                <div class="form-group">
                    <label>Petani</label>
                    <input type="text" id="modal-petani" readonly>
                </div>
                <div class="form-group">
                    <label>Jumlah (Kg)</label>
                    <input type="text" id="modal-jumlah" readonly>
                </div>
                
                <p style="margin-bottom: 20px; color: var(--text-light);">Anda yakin ingin menyetujui produk ini?</p>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-check"></i> Ya, Setujui
                </button>
            </form>
        </div>
    </div>


    <script>
        // (JavaScript SAMA PERSIS seperti sebelumnya, tidak perlu diubah)
        const navLinks = document.querySelectorAll('.nav-link');
        const contentSections = document.querySelectorAll('.content-section');
        
        function handleHashChange() {
            let hash = window.location.hash;
            if (!hash || !document.querySelector(hash)) {
                hash = '#validasi';
            }
            navLinks.forEach(nav => nav.classList.remove('active'));
            contentSections.forEach(sec => sec.classList.remove('active'));
            const activeLink = document.querySelector(`.nav-link[href="${hash}"]`);
            const activeSection = document.querySelector(hash);
            if (activeLink) activeLink.classList.add('active');
            if (activeSection) activeSection.classList.add('active');
        }
        navLinks.forEach(link => link.addEventListener('click', () => setTimeout(handleHashChange, 0)));
        window.addEventListener('load', handleHashChange);
        window.addEventListener('hashchange', handleHashChange);

        
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('validasi-modal');
            const closeModalBtn = document.getElementById('close-modal-btn');
            const tableBody = document.querySelector('#validasi .data-table tbody');

            const modalId = document.getElementById('modal-id');
            const modalProduk = document.getElementById('modal-produk');
            const modalPetani = document.getElementById('modal-petani');
            const modalJumlah = document.getElementById('modal-jumlah');
            
            if (tableBody && modal && closeModalBtn && modalId) { 
                tableBody.addEventListener('click', function(event) {
                    const setujuiButton = event.target.closest('.btn-setujui');
                    if (setujuiButton) {
                        event.preventDefault(); 
                        const row = setujuiButton.closest('tr');
                        
                        modalId.value = row.dataset.id;
                        modalProduk.value = row.dataset.produk;
                        modalPetani.value = row.dataset.petani;
                        modalJumlah.value = row.dataset.jumlah + " Kg";
                        
                        modal.classList.add('show');
                    }
                });
            
                function closeModal() {
                    modal.classList.remove('show');
                }

                closeModalBtn.addEventListener('click', closeModal);

                modal.addEventListener('click', function(event) {
                    if (event.target === modal) {
                        closeModal();
                    }
                });
            } else {
                console.error('Gagal menginisialisasi modal validasi. Elemen HTML tidak ditemukan.');
            }
        });
    </script>

</body>
</html>