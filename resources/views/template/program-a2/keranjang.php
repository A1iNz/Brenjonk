<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Program A2</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* (CSS Anda sama) */
        :root {
            --bg-dark: #1A1A1A; --bg-light-dark: #2A2A2A; --text-light: #F5F5F5;
            --text-medium: #AAAAAA; --accent-green: #30E0A0; --accent-red: #E74C3C;
            --border-color: #3A3A3A; --border-radius-modern: 16px;
        }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-dark); color: var(--text-medium); }
        .container { max-width: 900px; margin: 20px auto; padding: 0 20px; }
        h1 { color: var(--text-light); margin-bottom: 30px; }
        .header-nav { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .logo-desa { height: 60px; width: auto; display: block; }
        .btn {
            display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px;
            border-radius: 50px; text-decoration: none; font-weight: 600;
            cursor: pointer; border: 2px solid transparent; font-size: 0.9rem;
        }
        .btn-primary { background-color: var(--accent-green); color: #1A1A1A; }
        .btn-danger { background-color: var(--accent-red); color: var(--text-light); }
        .btn-secondary { background-color: var(--bg-dark); color: var(--text-light); border-color: var(--border-color); }
        .card-box { background-color: var(--bg-light-dark); border: 1px solid var(--border-color); border-radius: var(--border-radius-modern); margin-bottom: 30px; }
        .card-body { padding: 30px; }
        .data-table { width: 100%; border-collapse: collapse; text-align: left; }
        .data-table th, .data-table td { padding: 15px 20px; border-bottom: 1px solid var(--border-color); }
        .data-table th { background-color: var(--bg-dark); }
        .data-table tfoot td { font-weight: 600; font-size: 1.2rem; color: var(--text-light); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 500; margin-bottom: 8px; }
        .form-group input { width: 100%; padding: 12px; background-color: var(--bg-dark); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-light); font-size: 1rem; }
    </style>
</head>
<body>
    <div class="container">
        <header class="header-nav">
            <a href="index.php"> <img src="images[1].jpg" alt="Logo Desa" class="logo-desa">
            </a>
            <nav>
                <a href="stok.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali Belanja
                </a>
            </nav>
        </header>

        <h1><i class="fas fa-shopping-cart"></i> Keranjang Anda</h1>
        <div class="card-box">
            <div class="card-body" style="padding:0;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Petani</th>
                            <th>Jumlah Beli</th>
                            <th>Harga /Kg</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_keranjang = 0;
                        if (empty($_SESSION['cart'])) {
                            echo "<tr><td colspan='6' style='text-align:center;'>Keranjang Anda kosong.</td></tr>";
                        } else {
                            $ids = implode(',', array_keys($_SESSION['cart']));
                            $sql_cart = "SELECT p.id, p.nama_produk, p.harga_per_kg, u.username 
                                         FROM produk_panen p
                                         JOIN users u ON p.petani_id = u.id
                                         WHERE p.id IN ($ids)";
                            $result_cart = $koneksi->query($sql_cart);
                            
                            if ($result_cart) {
                                while ($row = $result_cart->fetch_assoc()) {
                                    $jumlah_kg = $_SESSION['cart'][$row['id']];
                                    $subtotal = $jumlah_kg * $row['harga_per_kg'];
                                    $total_keranjang += $subtotal;
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nama_produk']); ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><strong><?php echo $jumlah_kg; ?> Kg</strong></td>
                            <td>Rp <?php echo number_format($row['harga_per_kg'], 0, ',', '.'); ?></td>
                            <td>Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                            <td>
                                <form action="cart_handler.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="aksi" value="hapus">
                                    <input type="hidden" name="id_produk" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 0.8rem;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php
                                }
                            }
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" style="text-align: right;">Total Pesanan:</td>
                            <td colspan="2">Rp <?php echo number_format($total_keranjang, 0, ',', '.'); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <?php if (!empty($_SESSION['cart'])): ?>
        <div class="card-box">
            <div class="card-body">
                <h2>Data Pemesan (Konsumen)</h2>
                <form action="cart_handler.php" method="POST">
                    <input type="hidden" name="aksi" value="checkout">
                    <div class="form-group">
                        <label for="nama_konsumen">Nama Konsumen / Restoran</label>
                        <input type="text" id="nama_konsumen" name="nama_konsumen" required>
                    </div>
                    <div class="form-group">
                        <label for="telepon_konsumen">Nomor Telepon</label>
                        <input type="tel" id="telepon_konsumen" name="telepon_konsumen" required>
                    </div>
                    <button type="submit" class="btn btn-primary" style="font-size: 1.1rem; padding: 15px 30px;">
                        <i class="fas fa-check"></i> Buat Pesanan (PO) Sekarang
                    </button>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>