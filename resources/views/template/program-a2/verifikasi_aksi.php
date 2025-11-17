<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Akses dilarang.");
}

include 'koneksi.php';

try {
    $id_produk = 0;
    $status_baru = '';

    // --- AKSI SETUJUI (via POST dari Modal) ---
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['aksi']) && $_POST['aksi'] == 'setujui') {
        
        $id_produk = (int)$_POST['id'];
        $status_baru = 'Tersedia'; // Status diubah, harga tidak.
        
        if ($id_produk <= 0) {
            throw new Exception("ID produk tidak valid.");
        }
        
        $sql_update = "UPDATE produk_panen SET status = ? WHERE id = ?";
        $stmt = $koneksi->prepare($sql_update);
        $stmt->bind_param("si", $status_baru, $id_produk);
    
    } 
    // --- AKSI TOLAK (via GET dari Link) ---
    elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['aksi']) && $_GET['aksi'] == 'tolak') {
        
        $id_produk = (int)$_GET['id'];
        $status_baru = 'Ditolak';

        if ($id_produk <= 0) {
            throw new Exception("ID produk tidak valid.");
        }

        $sql_update = "UPDATE produk_panen SET status = ? WHERE id = ?";
        $stmt = $koneksi->prepare($sql_update);
        $stmt->bind_param("si", $status_baru, $id_produk);
        
    } 
    else {
        throw new Exception("Aksi tidak dikenal.");
    }
    
    // Eksekusi query
    if ($stmt->execute()) {
        $koneksi->commit(); // Paksa simpan
        header("Location: dashboard_kelompoktani.php#validasi&status=sukses");
    } else {
        throw new Exception("Gagal mengeksekusi statement: " . $stmt->error);
    }
    
    $stmt->close();

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

$koneksi->close();
?>