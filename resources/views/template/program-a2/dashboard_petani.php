<?php
// SELALU di baris paling atas
session_start();

// Halaman ini HANYA untuk role 'petani' atau 'admin'
if (!isset($_SESSION['user_id']) || 
    ($_SESSION['role'] !== 'petani' && $_SESSION['role'] !== 'admin')) {
    header("Location: login.html"); // Jika bukan petani ATAU admin, tendang
    exit;
}

// --- BLOK STATISTIK DAN KONEKSI ---
include 'koneksi.php'; 
$id_petani_login = $_SESSION['user_id'];
$total_produk = 0;
$total_menunggu = 0;
$total_disetujui = 0; 

try {
    // Query 1: Total Produk Terdaftar (Semua status)
    $sql_total = "SELECT COUNT(id) as total FROM produk_panen WHERE petani_id = ?";
    $stmt_total = $koneksi->prepare($sql_total);
    $stmt_total->bind_param("i", $id_petani_login);
    $stmt_total->execute();
    $total_produk = $stmt_total->get_result()->fetch_assoc()['total'] ?? 0;
    $stmt_total->close();

    // Query 2: Total Menunggu Verifikasi
    $sql_menunggu = "SELECT COUNT(id) as total FROM produk_panen WHERE petani_id = ? AND status = 'Menunggu'";
    $stmt_menunggu = $koneksi->prepare($sql_menunggu);
    $stmt_menunggu->bind_param("i", $id_petani_login);
    $stmt_menunggu->execute();
    $total_menunggu = $stmt_menunggu->get_result()->fetch_assoc()['total'] ?? 0;
    $stmt_menunggu->close();

    // Query 3: Total Produk Disetujui (Tersedia + Terjual)
    $sql_disetujui = "SELECT COUNT(id) as total FROM produk_panen WHERE petani_id = ? AND status IN ('Tersedia', 'Terjual')";
    $stmt_disetujui = $koneksi->prepare($sql_disetujui);
    $stmt_disetujui->bind_param("i", $id_petani_login);
    $stmt_disetujui->execute();
    $total_disetujui = $stmt_disetujui->get_result()->fetch_assoc()['total'] ?? 0;
    $stmt_disetujui->close();

} catch (Exception $e) {
    // Biarkan 0 jika error
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petani - Program A2</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <style>
        /* (SEMUA CSS SAMA PERSIS SEPERTI SEBELUMNYA) */
        :root {
            --bg-dark: #1A1A1A; --bg-light-dark: #2A2A2A; --text-light: #F5F5F5;
            --text-medium: #AAAAAA; --accent-green: #30E0A0; --accent-red: #E74C3C;
            --accent-yellow: #f1c40f; --accent-blue: #3498DB; --border-color: #3A3A3A;
            --border-radius-modern: 16px; --transition: all 0.3s ease-in-out;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; line-height: 1.6; background-color: var(--bg-dark); color: var(--text-medium); }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        .dashboard-header { background-color: var(--bg-light-dark); border-bottom: 1px solid var(--border-color); padding: 20px; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 1.8rem; font-weight: 700; color: var(--text-light); text-decoration: none; }
        .logo .highlight { color: var(--accent-green); }
        .user-nav { display: flex; align-items: center; gap: 15px; }
        .user-welcome { font-size: 0.95rem; color: var(--text-light); margin-right: 10px; }
        .user-welcome span { font-weight: 600; color: var(--accent-green); }
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 12px 30px; border-radius: 50px; text-decoration: none; font-weight: 600; cursor: pointer; transition: var(--transition); border: 2px solid transparent; font-size: 1rem; }
        .btn-primary { background-color: var(--accent-green); color: #1A1A1A; }
        .btn-primary:hover { transform: translateY(-3px); box-shadow: 0 0 20px rgba(48, 224, 160, 0.4); }
        .btn-danger { background-color: transparent; color: var(--accent-red); border: 2px solid var(--accent-red); padding: 8px 20px; }
        .btn-danger:hover { background-color: var(--accent-red); color: var(--text-light); transform: translateY(-2px); }
        .btn-icon { background: none; border: none; cursor: pointer; color: var(--text-medium); font-size: 1.1rem; padding: 5px; margin-right: 10px; transition: var(--transition); }
        .btn-icon.edit:hover { color: var(--accent-green); }
        .btn-icon.delete:hover { color: var(--accent-red); }
        .btn-icon:disabled { color: #555; cursor: not-allowed; opacity: 0.5; }
        main { padding: 40px 20px; }
        .main-title { font-size: 2.5rem; margin-bottom: 30px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .stat-card { background-color: var(--bg-light-dark); border-radius: var(--border-radius-modern); border: 1px solid var(--border-color); padding: 25px; display: flex; align-items: center; gap: 20px; }
        .stat-card i { font-size: 2.5rem; color: var(--accent-green); }
        .stat-card-info h4 { font-size: 1.1rem; color: var(--text-medium); font-weight: 500; margin-bottom: 5px; }
        .stat-card-info p { font-size: 1.8rem; font-weight: 700; color: var(--text-light); }
        .dashboard-layout { display: grid; grid-template-columns: 1fr 2fr; gap: 30px; align-items: flex-start; }
        .card-box { background-color: var(--bg-light-dark); border-radius: var(--border-radius-modern); border: 1px solid var(--border-color); padding: 30px; }
        .card-box h3 { font-size: 1.5rem; margin-bottom: 25px; padding-bottom: 10px; border-bottom: 1px solid var(--border-color); }
        .form-group { margin-bottom: 20px; text-align: left; }
        .form-group label { display: block; font-size: 0.9em; font-weight: 500; color: var(--text-medium); margin-bottom: 8px; }
        .form-group input, .form-group select { width: 100%; padding: 14px; background-color: var(--bg-dark); border: 1px solid var(--border-color); border-radius: 10px; color: var(--text-light); font-size: 1rem; transition: var(--transition); font-family: 'Inter', sans-serif; }
        .form-group input:focus, .form-group select:focus { outline: none; border-color: var(--accent-green); box-shadow: 0 0 10px rgba(48, 224, 160, 0.2); }
        .btn-full { width: 100%; margin-top: 10px; }
        .data-table-wrapper { overflow-x: auto; }
        .data-table { width: 100%; border-collapse: collapse; text-align: left; }
        .data-table th { background-color: var(--bg-dark); color: var(--text-light); padding: 15px; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; }
        .data-table td { padding: 15px; border-bottom: 1px solid var(--border-color); }
        .status { padding: 5px 12px; border-radius: 20px; font-weight: 600; font-size: 0.85rem; display: inline-block; }
        .status-pending { background-color: rgba(241, 196, 15, 0.1); color: var(--accent-yellow); }
        .status-verified { background-color: rgba(48, 224, 160, 0.1); color: var(--accent-green); }
        .status-sold { background-color: rgba(170, 170, 170, 0.1); color: var(--text-medium); }
        .status-rejected { background-color: rgba(231, 76, 60, 0.1); color: var(--accent-red); }
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.7); z-index: 1000; display: none; justify-content: center; align-items: center; backdrop-filter: blur(5px); }
        .modal-box { background-color: var(--bg-light-dark); border-radius: var(--border-radius-modern); border: 1px solid var(--border-color); padding: 30px; width: 90%; max-width: 500px; z-index: 1001; transform: scale(0.9); opacity: 0; transition: all 0.2s ease-in-out; }
        .modal-overlay.show .modal-box { transform: scale(1); opacity: 1; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; padding-bottom: 10px; border-bottom: 1px solid var(--border-color); }
        .modal-header h3 { font-size: 1.5rem; margin-bottom: 0; padding-bottom: 0; border-bottom: none; color: var(--text-light); }
        .close-btn { font-size: 1.8rem; color: var(--text-medium); cursor: pointer; background: none; border: none; transition: var(--transition); }
        .close-btn:hover { color: var(--text-light); }
        @media (max-width: 992px) { .dashboard-layout { grid-template-columns: 1fr; } }
        @media (max-width: 768px) { .stats-grid { grid-template-columns: 1fr; } .user-welcome { display: none; } .main-title { font-size: 2rem; } .user-nav { gap: 10px; } }
    </style>
</head>
<body>

    <header class="dashboard-header">
        <a href="dashboard_petani.php" class="logo">Program<span class="highlight">A2</span></a>
        <nav class="user-nav">
            <div class="user-welcome">
                Selamat datang, <span><?php echo htmlspecialchars($_SESSION['nama_petani']); ?></span>
            </div>
            
            <a href="logout.php" class="btn btn-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </nav>
    </header>

    <main class="container">
        <h1 class="main-title">Dashboard Petani</h1>

        <section class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-box-open"></i>
                <div class="stat-card-info">
                    <h4>Produk Terdaftar</h4>
                    <p><?php echo $total_produk; ?></p> </div>
            </div>
            <div class="stat-card">
                <i class="fas fa-hourglass-half"></i>
                <div class="stat-card-info">
                    <h4>Menunggu Verifikasi</h4>
                    <p><?php echo $total_menunggu; ?></p> </div>
            </div>
            <div class="stat-card">
                <i class="fas fa-check-circle"></i>
                <div class="stat-card-info">
                    <h4>Produk Disetujui</h4>
                    <p><?php echo $total_disetujui; ?></p> </div>
            </div>
        </section>

        <section class="dashboard-layout">
            
            <aside class="card-box">
                <h3><i class="fas fa-plus-circle"></i> Lapor Rencana Panen</h3>
                
                <form id="form-tambah-panen">
                <div class="form-group">
                        <label for="produk">Nama Produk</label>
                        <input type="text" id="produk" name="produk" placeholder="Mis: Tomat Cherry, Cabai Rawit" required>
                    </div>
                    <div class="form-group">
                        <label for="jumlah">Estimasi Jumlah (Kg)</label>
                        <input type="number" id="jumlah" name="jumlah" placeholder="Dalam Kg" required>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_panen">Estimasi Tanggal Panen</label>
                        <input type="date" id="tanggal_panen" name="tanggal_panen" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-full">
                        <i class="fas fa-plus"></i> Tambahkan Laporan
                    </button>
                </form>
            </aside>

            <section class="card-box">
                <h3><i class="fas fa-list-alt"></i> Riwayat Laporan Panen</h3>
                
                <div class="data-table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Estimasi (Kg)</th>
                                <th>Tgl. Panen</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $sql = "SELECT id, nama_produk, jumlah_kg, tgl_panen, status 
                                    FROM produk_panen 
                                    WHERE petani_id = ? 
                                    ORDER BY tgl_panen DESC";
                            
                            $stmt = $koneksi->prepare($sql);
                            if ($stmt === false) {
                                echo "<tr><td colspan='5'>Error preparing statement: " . htmlspecialchars($koneksi->error) . "</td></tr>";
                            } else {
                                $stmt->bind_param("i", $id_petani_login);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                if ($result->num_rows === 0) {
                                    echo "<tr><td colspan='5' style='text-align: center;'>Anda belum menambahkan laporan panen.</td></tr>";
                                } else {
                                    while ($row = $result->fetch_assoc()) {
                                        $tanggal_formatted = date('d M Y', strtotime($row['tgl_panen']));
                                        $status_text = htmlspecialchars($row['status']);
                                        $status_class = '';
                                        $is_disabled = '';

                                        switch (strtolower($status_text)) {
                                            case 'tersedia':
                                                $status_class = 'status-verified';
                                                $status_text = 'Disetujui'; 
                                                $is_disabled = 'disabled';
                                                break;
                                            case 'terjual':
                                                $status_class = 'status-sold'; 
                                                $is_disabled = 'disabled'; 
                                                break;
                                            case 'ditolak':
                                                $status_class = 'status-rejected'; 
                                                $is_disabled = ''; 
                                                break;
                                            case 'menunggu':
                                            default:
                                                $status_class = 'status-pending'; 
                                                break;
                                        }
                                ?>
                                <tr data-id="<?php echo $row['id']; ?>" data-tanggal="<?php echo $row['tgl_panen']; ?>">
                                    <td><?php echo htmlspecialchars($row['nama_produk']); ?></td>
                                    <td><?php echo htmlspecialchars($row['jumlah_kg']); ?> Kg</td>
                                    <td><?php echo $tanggal_formatted; ?></td>
                                    <td><span class="status <?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                                    <td class="action-buttons">
                                        <button class="btn-icon edit" title="Edit" <?php echo $is_disabled; ?>><i class="fas fa-edit"></i></button>
                                        <button class="btn-icon delete" title="Hapus" <?php echo $is_disabled; ?>><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            <?php
                                    } 
                                } 
                                $stmt->close();
                            } 
                            $koneksi->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </section>
    </main>

    <div id="edit-modal-overlay" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-header">
                <h3><i class="fas fa-edit"></i> Edit Data Panen</h3>
                <button id="close-modal-btn" class="close-btn">&times;</button>
            </div>
            
            <form id="form-edit-panen">
                <input type="hidden" id="edit_id" name="id">
                
                <div class="form-group">
                    <label for="edit_produk">Nama Produk</label>
                    <input type="text" id="edit_produk" name="produk" required>
                </div>
                <div class="form-group">
                    <label for="edit_jumlah">Jumlah (Kg)</label>
                    <input type="number" id="edit_jumlah" name="jumlah" required>
                </div>
                <div class="form-group">
                    <label for="edit_tanggal_panen">Tanggal Perkiraan Panen</label>
                    <input type="date" id="edit_tanggal_panen" name="tanggal_panen" required>
                </div>
                <button type="submit" class="btn btn-primary btn-full">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // === Pastikan ID ini 'form-tambah-panen' ===
        const formPanen = document.getElementById('form-tambah-panen');
        const tableBody = document.querySelector('.data-table tbody');
        
        const editModal = document.getElementById('edit-modal-overlay');
        const formEdit = document.getElementById('form-edit-panen');
        const closeModalBtn = document.getElementById('close-modal-btn');
        const editIdInput = document.getElementById('edit_id'); 
        const editProdukInput = document.getElementById('edit_produk');
        const editJumlahInput = document.getElementById('edit_jumlah');
        const editTanggalInput = document.getElementById('edit_tanggal_panen');

        // === 1. FUNGSI TAMBAH DATA (FORM KIRI) ===
        // Cek jika formPanen tidak null (ditemukan)
        if (formPanen) {
            formPanen.addEventListener('submit', function(event) {
                event.preventDefault(); // Ini akan mencegah halaman refresh
                const formData = new FormData(this);
                const submitButton = this.querySelector('button[type="submit"]');
                const originalButtonHtml = submitButton.innerHTML;
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

                fetch('tambah_panen.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Data berhasil ditambahkan! Halaman akan dimuat ulang.');
                        window.location.reload(); 
                    } else {
                        alert('Gagal: ' + (data.message || 'Terjadi kesalahan.'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan koneksi. Silakan coba lagi.');
                })
                .finally(() => {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonHtml;
                });
            });
        } else {
            console.error("Error: Form dengan ID 'form-tambah-panen' tidak ditemukan.");
        }

        // === 2. FUNGSI UNTUK TOMBOL DI TABEL (EDIT & HAPUS) ===
        tableBody.addEventListener('click', function(event) {
            
            // --- Logika Tombol HAPUS ---
            const deleteButton = event.target.closest('.delete');
            if (deleteButton && !deleteButton.disabled) {
                event.preventDefault();
                if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) { return; }
                const rowToDelete = deleteButton.closest('tr');
                const id = rowToDelete.dataset.id; 
                if (!id) { alert('Gagal mendapatkan ID data.'); return; }

                rowToDelete.style.opacity = '0.5';
                deleteButton.disabled = true;
                deleteButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                fetch('hapus_panen.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id }) 
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Data berhasil dihapus! Halaman akan dimuat ulang.');
                        window.location.reload();
                    } else {
                        alert('Gagal menghapus: ' + (data.message || 'Data tidak ditemukan.'));
                        rowToDelete.style.opacity = '1';
                        deleteButton.disabled = false;
                        deleteButton.innerHTML = '<i class="fas fa-trash"></i>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan koneksi saat menghapus.');
                    rowToDelete.style.opacity = '1';
                    deleteButton.disabled = false;
                    deleteButton.innerHTML = '<i class="fas fa-trash"></i>';
                });
            }

            // --- Logika Tombol EDIT ---
            const editButton = event.target.closest('.edit');
            if (editButton && !editButton.disabled) {
                event.preventDefault();
                const row = editButton.closest('tr');
                editIdInput.value = row.dataset.id;
                editProdukInput.value = row.cells[0].textContent;
                editJumlahInput.value = row.cells[1].textContent.replace(' Kg', '').trim();
                editTanggalInput.value = row.dataset.tanggal;
                editModal.style.display = 'flex';
                setTimeout(() => editModal.classList.add('show'), 10);
            }
        });

        // === 3. FUNGSI UNTUK MODAL EDIT ===
        function closeModal() {
            editModal.classList.remove('show');
            setTimeout(() => (editModal.style.display = 'none'), 200);
        }

        closeModalBtn.addEventListener('click', closeModal);

        formEdit.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalButtonHtml = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            
            fetch('edit_panen.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Data berhasil diperbarui! Halaman akan dimuat ulang.');
                    window.location.reload();
                } else {
                    alert('Gagal memperbarui: ' + (data.message || 'Terjadi kesalahan.'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan koneksi saat memperbarui.');
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonHtml;
            });
        });

        editModal.addEventListener('click', function(event) {
            if (event.target === this) {
                closeModal();
            }
        });
    });
    </script>
</body>
</html>