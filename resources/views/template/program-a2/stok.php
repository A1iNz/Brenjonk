<?php
session_start();
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Tersedia - Program A2</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* (CSS Anda sama persis, saya hanya mengubah sedikit bagian tombol) */
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
        h1 { color: var(--text-light); margin-bottom: 30px; font-size: 2.5rem; }
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
        .btn-full-link {
            width: 100%; justify-content: center; font-size: 1rem;
            background-color: var(--accent-green); color: #1A1A1A;
        }
        .btn-full-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(48, 224, 160, 0.4);
        }

        .product-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; }
        .product-card {
            background-color: var(--bg-light-dark); border: 1px solid var(--border-color);
            border-radius: var(--border-radius-modern); overflow: hidden;
            display: flex; flex-direction: column; transition: var(--transition);
        }
        .product-card:hover { transform: translateY(-5px); border-color: var(--accent-green); }
        
        /* CSS untuk gambar lokal */
        .product-card-image {
            height: 200px;
            background-color: var(--bg-dark); 
            border-bottom: 1px solid var(--border-color);
        }
        .product-card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Membuat gambar pas tanpa penyet */
            display: block;
        }

        .product-card-content { 
            padding: 25px; flex-grow: 1; display: flex; flex-direction: column;
        }
        .product-card h3 { color: var(--text-light); margin-bottom: 10px; font-size: 1.4rem; }
        .product-card .price {
            font-size: 1.3rem; font-weight: 600;
            color: var(--accent-green); margin-bottom: 15px;
        }
        .product-card .stock { 
            font-size: 0.9rem; color: var(--text-medium); line-height: 1.5;
            margin-bottom: 15px; flex-grow: 1; 
        }
        .product-card .stock strong { color: var(--text-light); }
        .product-card-footer {
            padding: 20px 25px; background-color: var(--bg-dark); 
            border-top: 1px solid var(--border-color);
        }
    </style>
</head>
<body>

    <div class="container">
        <header class="header-nav">
            <a href="index.php">
                <img src="logo_desa.jpg" alt="Logo Desa" class="logo-desa">
            </a>
            <nav>
                <?php $item_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
                <a href="keranjang.php" class="btn btn-primary">
                    <i class="fas fa-shopping-cart"></i> Keranjang (<?php echo $item_count; ?>)
                </a>
                <a href="login.html" class="btn btn-secondary" style="margin-left: 10px;">Login Admin</a>
            </nav>
        </header>

        <h1>Stok Siap Jual (H-3)</h1>
        <div class="product-grid">
            <?php
            // === QUERY DIUBAH: ambil image_url ===
            $sql = "SELECT 
                        nama_produk, 
                        SUM(jumlah_kg) as total_stok, 
                        MIN(harga_per_kg) as harga_terendah,
                        COUNT(id) as jumlah_batch,
                        MAX(image_url) as image_url
                    FROM produk_panen 
                    WHERE status = 'Tersedia' AND harga_per_kg > 0
                    GROUP BY nama_produk
                    ORDER BY nama_produk ASC";
            
            $result = $koneksi->query($sql);
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
            ?>
                <div class="product-card">
                    
                    <div class="product-card-image">
                        <?php if (!empty($row['image_url'])): ?>
                            <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['nama_produk']); ?>">
                        <?php endif; ?>
                    </div>

                    <div class="product-card-content">
                        <h3><?php echo htmlspecialchars($row['nama_produk']); ?></h3>
                        
                        <p class="price">Mulai Rp <?php echo number_format($row['harga_terendah'], 0, ',', '.'); ?>,- / Kg</p>
                        
                        <p class="stock">
                            Total Stok: <strong><?php echo $row['total_stok']; ?> Kg</strong><br>
                            Tersedia dari: <?php echo $row['jumlah_batch']; ?> Petani/Panen
                        </p>
                    </div>
                    <div class="product-card-footer">
                        <a href="detail_produk.php?produk=<?php echo urlencode($row['nama_produk']); ?>" class="btn btn-full-link">
                            <i class="fas fa-search-plus"></i> Lihat Pilihan
                        </a>
                    </div>
                </div>
            <?php
                }
            } else {
                echo "<p style='text-align: center; grid-column: 1 / -1; color: var(--text-light);'>Belum ada stok yang tersedia untuk dipesan.</p>";
            }
            $koneksi->close();
            ?>
        </div>
    </div>

</body>
</html>