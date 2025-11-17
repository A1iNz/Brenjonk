<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$aksi = $_POST['aksi'] ?? $_GET['aksi'] ?? '';

try {
    // --- AKSI: TAMBAH ---
    if ($aksi == 'tambah' && isset($_POST['id_produk'])) {
        $id_produk = (int)$_POST['id_produk'];
        $jumlah_kg = (int)$_POST['jumlah_kg'];
        
        if ($jumlah_kg <= 0) {
            throw new Exception("Jumlah Kg tidak valid.");
        }
        
        $stmt_cek = $koneksi->prepare("SELECT jumlah_kg FROM produk_panen WHERE id = ? AND status = 'Tersedia'");
        $stmt_cek->bind_param("i", $id_produk);
        $stmt_cek->execute();
        $result_cek = $stmt_cek->get_result();
        
        if ($result_cek->num_rows > 0) {
            $row = $result_cek->fetch_assoc();
            $stok_tersedia = $row['jumlah_kg'];
            $jumlah_di_keranjang = $_SESSION['cart'][$id_produk] ?? 0;
            $total_diminta = $jumlah_di_keranjang + $jumlah_kg;
            
            if ($total_diminta > $stok_tersedia) {
                throw new Exception("Stok tidak mencukupi. Stok tersisa: " . $stok_tersedia . " Kg.");
            }
            $_SESSION['cart'][$id_produk] = $total_diminta; 
        } else {
            throw new Exception("Produk tidak ditemukan atau sudah habis.");
        }
        $stmt_cek->close();
        header("Location: keranjang.php");
        exit;
    }

    // --- AKSI: HAPUS ---
    if ($aksi == 'hapus' && isset($_POST['id_produk'])) {
        $id_produk = (int)$_POST['id_produk'];
        if (isset($_SESSION['cart'][$id_produk])) {
            unset($_SESSION['cart'][$id_produk]);
        }
        header("Location: keranjang.php");
        exit;
    }

    // --- AKSI: CHECKOUT ---
    if ($aksi == 'checkout' && !empty($_SESSION['cart'])) {
        $nama_konsumen = $_POST['nama_konsumen'] ?? 'Konsumen';
        $total_harga_po = 0;
        $items_in_cart = [];
        
        $ids = implode(',', array_keys($_SESSION['cart']));
        $sql_cart = "SELECT id, nama_produk, jumlah_kg, harga_per_kg, status FROM produk_panen WHERE id IN ($ids)";
        $result_cart = $koneksi->query($sql_cart);
        
        if (!$result_cart || $result_cart->num_rows == 0) {
            throw new Exception("Produk di keranjang tidak valid.");
        }
        
        while ($row = $result_cart->fetch_assoc()) {
            $jumlah_diminta = $_SESSION['cart'][$row['id']];
            if ($row['status'] !== 'Tersedia' || $jumlah_diminta > $row['jumlah_kg']) {
                throw new Exception("Stok untuk '" . $row['nama_produk'] . "' tidak mencukupi.");
            }
            $subtotal = $jumlah_diminta * $row['harga_per_kg'];
            $total_harga_po += $subtotal;
            $items_in_cart[] = [
                'id' => $row['id'],
                'jumlah_kg_dibeli' => $jumlah_diminta,
                'stok_awal' => $row['jumlah_kg'],
                'harga_subtotal' => $subtotal
            ];
        }

        $koneksi->begin_transaction();

        $status_po = 'Diproses';
        $tgl_pesanan = date('Y-m-d H:i:s');
        $sql_pesanan = "INSERT INTO pesanan (nama_konsumen, tgl_pesanan, total_harga, status_po) VALUES (?, ?, ?, ?)";
        $stmt_pesanan = $koneksi->prepare($sql_pesanan);
        $stmt_pesanan->bind_param("ssis", $nama_konsumen, $tgl_pesanan, $total_harga_po, $status_po);
        $stmt_pesanan->execute();
        $id_pesanan_baru = $koneksi->insert_id;
        $stmt_pesanan->close();

        $sql_detail = "INSERT INTO detail_pesanan (id_pesanan, id_produk, jumlah_kg, harga_subtotal) VALUES (?, ?, ?, ?)";
        $stmt_detail = $koneksi->prepare($sql_detail);
        $sql_update_produk = "UPDATE produk_panen SET jumlah_kg = ?, status = ? WHERE id = ?";
        $stmt_update_produk = $koneksi->prepare($sql_update_produk);
        
        foreach ($items_in_cart as $item) {
            $stmt_detail->bind_param("iiid", $id_pesanan_baru, $item['id'], $item['jumlah_kg_dibeli'], $item['harga_subtotal']);
            $stmt_detail->execute();
            
            $stok_baru = $item['stok_awal'] - $item['jumlah_kg_dibeli'];
            $status_baru = ($stok_baru <= 0) ? 'Terjual' : 'Tersedia';
            
            $stmt_update_produk->bind_param("isi", $stok_baru, $status_baru, $item['id']);
            $stmt_update_produk->execute();
        }
        
        $stmt_detail->close();
        $stmt_update_produk->close();
        $koneksi->commit();
        unset($_SESSION['cart']);
        
        // === INI PERBAIKANNYA ===
        header("Location: stok.php?status=sukses"); // Arahkan ke stok.php
        exit;
    }
    
} catch (Exception $e) {
    $koneksi->rollback();
    die("Error: " . $e->getMessage() . "<br><a href='keranjang.php'>Kembali ke Keranjang</a>");
}

$koneksi->close();
?>