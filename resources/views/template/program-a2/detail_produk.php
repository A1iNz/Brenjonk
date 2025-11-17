<?php
session_start();
include 'koneksi.php';

if (!isset($_GET['produk'])) {
    die("Produk tidak ditemukan.");
}
$nama_produk_dicari = $_GET['produk'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk: <?php echo htmlspecialchars($nama_produk_dicari); ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* (CSS Anda sama) */
        :root {
            --bg-dark: #1A1A1A; --bg-light-dark: #2A2A2A; --text-light: #F5F5F5;
            --text-medium: #AAAAAA; --accent-green: #30E0A0; --accent-red: #E74C3C;
            --accent-blue: #3498DB; --border-color: #3A3A3A; --border-radius-modern: 16px;
            --transition: all 0.3s ease-in-out;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-dark); color: var(--text-medium); line-height: 1.6; }
        .container { max-width: 1200px; margin: 20px auto; padding: 0 20px; }
        .header-nav { 
            display: flex; justify-content: space-between; align-items: center; 
            margin-bottom: 40px; background-color: var(--bg-light-dark); 
            padding: 20px 30px; border-radius: var(--border-radius-modern);
            border: 1px solid var(--border-color);
        }
        .logo-desa { height: 60px; width: auto; display: block; }
        h1 { color: var(--text-light); margin-bottom: 10px; font-size: 2.5rem; }
        h2 { color: var(--text-medium); margin-bottom: 30px; font-weight: 400; }
        .btn {
            display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px;
            border-radius: 50px; text-decoration: none; font-weight: 600;
            cursor: pointer; border: 2px solid transparent; font-size: 0.9rem;
            transition: var(--transition);
        }
        .btn-primary { background-color: var(--accent-green); color: #1A1A1A; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(48, 224, 160, 0.4); }
        .btn-secondary { background-color: var(--bg-dark); color: var(--text-light); border-color: var(--border-color); }
        .btn-secondary:hover { background-color: #333; border-color: #555; }
        .card-box { background-color: var(--bg-light-dark); border: 1px solid var(--border-color); border-radius: var(--border-radius-modern); overflow: hidden; }
        .data-table-wrapper { overflow-x: auto; }
        .data-table { width: 100%; border-collapse: collapse; text-align: left; }
        .data-table th { background-color: var(--bg-dark); color: var(--text-light); padding: 15px 20px; font-size: 0.9rem; text-transform: uppercase; }
        .data-table td { padding: 15px 20px; border-bottom: 1px solid var(--border-color); vertical-align: middle; }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table .price { color: var(--accent-green); font-weight: 600; }
        .data-table .petani { color: var(--text-light); font-weight: 500; }
        .kg-input-form { display: flex; gap: 10px; align-items: center; }
        .kg-input {
            width: 80px; padding: 10px; background-color: var(--bg-dark);
            border: 1px solid var(--border-color); color: var(--text-light);
            border-radius: 8px; font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="container">
        <header class="header-nav">
            <a href="index.php"> <img src="images[1].jpg" alt="Logo Desa" class="logo-desa">
            </a>
            <nav>
                <a href="stok.php" class="btn btn-secondary" style="margin-right: 10px;">
                    <i class="fas fa-arrow-left"></i> Kembali ke Etalase
                </a>
                <?php $item_count = isset($_SESSION['cart']) ? count(array_keys($_SESSION['cart'])) : 0; ?>
                <a href="keranjang.php" class="btn btn-primary">
                    <i class="fas fa-shopping-cart"></i> Keranjang (<?php echo $item_count; ?>)
                </a>
            </nav>
        </header>

        <h1><?php echo htmlspecialchars($nama_produk_dicari); ?></h1>
        <h2>Pilih batch yang tersedia untuk dibeli:</h2>
        
        <div class="card-box">
            <div class="data-table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Petani</th>
                            <th>Stok Tersedia</th>
                            <th>Harga per Kg</th>
                            <th>Tgl. Panen</th>
                            <th>Jumlah Beli (Kg)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // (Kode PHP Anda sama persis)
                        $sql_batches = $koneksi->prepare(
                            "SELECT p.id, p.jumlah_kg, p.harga_per_kg, p.tgl_panen, u.username 
                             FROM produk_panen p
                             JOIN users u ON p.petani_id = u.id
                             WHERE p.status = 'Tersedia' AND p.nama_produk = ?
                             ORDER BY p.harga_per_kg ASC"
                        );
                        $sql_batches->bind_param("s", $nama_produk_dicari);
                        $sql_batches->execute();
                        $result_batches = $sql_batches->get_result();
                        
                        if ($result_batches->num_rows > 0) {
                            while ($row = $result_batches->fetch_assoc()) {
                        ?>
                        <tr>
                            <td class="petani"><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['jumlah_kg']); ?> Kg</td>
                            <td class="price">Rp <?php echo number_format($row['harga_per_kg'], 0, ',', '.'); ?></td>
                            <td><?php echo date('d M Y', strtotime($row['tgl_panen'])); ?></td>
                            <td>
                                <form action="cart_handler.php" method="POST" class="kg-input-form">
                                    <input type="hidden" name="aksi" value="tambah">
                                    <input type="hidden" name="id_produk" value="<?php echo $row['id']; ?>">
                                    <input type="number" name="jumlah_kg" class="kg-input" 
                                           placeholder="Kg" min="1" max="<?php echo $row['jumlah_kg']; ?>" required>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Tambah
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='5' style='text-align:center;'>Stok untuk produk ini sudah habis.</td></tr>";
                        }
                        $sql_batches->close();
                        $koneksi->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>