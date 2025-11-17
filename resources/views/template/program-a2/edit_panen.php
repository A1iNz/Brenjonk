<?php
session_start();
include 'koneksi.php'; 
header('Content-Type: application/json');

// Buat fungsi balasan
function kirimBalasan($status, $pesan) {
    echo json_encode(['success' => $status, 'message' => $pesan]);
    exit;
}

// Cek login (Sesuai session Anda)
if (!isset($_SESSION['user_id'])) {
    kirimBalasan(false, 'Akses ditolak. Silakan login.');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    kirimBalasan(false, 'Metode permintaan tidak valid.');
}

// (DIRUBAH) Ambil 'id' dari form, bukan 'id_panen'
$id = $_POST['id'] ?? null; 
$produk = $_POST['produk'] ?? null;
$jumlah = $_POST['jumlah'] ?? null;
$tanggal = $_POST['tanggal_panen'] ?? null;
$id_petani = $_SESSION['user_id']; // Sesuai session Anda

if (empty($id) || empty($produk) || empty($jumlah) || empty($tanggal)) {
    kirimBalasan(false, 'Semua data wajib diisi.');
}

try {
    // (DIRUBAH) Query menggunakan 'id' dan 'petani_id'
    $sql = "UPDATE produk_panen 
            SET nama_produk = ?, jumlah_kg = ?, tgl_panen = ? 
            WHERE id = ? AND petani_id = ?"; // id_panen -> id

    $stmt = $koneksi->prepare($sql);
    // Tipe data: s(string) d(double/int) s(string) i(int) i(int)
    $stmt->bind_param("sdsii", $produk, $jumlah, $tanggal, $id, $id_petani);

    if ($stmt->execute()) {
        kirimBalasan(true, 'Data berhasil diperbarui.');
    } else {
        kirimBalasan(false, 'Gagal memperbarui data: ' . $stmt->error);
    }
    $stmt->close();
    $koneksi->close();

} catch (Exception $e) {
    kirimBalasan(false, 'Error: ' . $e->getMessage());
}
?>