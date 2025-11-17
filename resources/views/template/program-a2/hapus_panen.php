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

// Ambil data JSON yang dikirim oleh JavaScript
$data = json_decode(file_get_contents('php://input'), true);

$id = $data['id'] ?? null; // Nama 'id' ini sudah benar
$id_petani = $_SESSION['user_id']; // Sesuai session Anda

if (empty($id) || !is_numeric($id)) {
    kirimBalasan(false, 'ID Produk tidak valid.');
}

try {
    // (DIRUBAH) Query menggunakan 'id'
    $sql = "DELETE FROM produk_panen WHERE id = ? AND petani_id = ?"; // id_panen -> id

    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("ii", $id, $id_petani);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            kirimBalasan(true, 'Data berhasil dihapus.');
        } else {
            kirimBalasan(false, 'Data tidak ditemukan atau Anda tidak memiliki izin.');
        }
    } else {
        kirimBalasan(false, 'Gagal menghapus data: ' . $stmt->error);
    }
    $stmt->close();
    $koneksi->close();

} catch (Exception $e) {
    kirimBalasan(false, 'Error: ' . $e->getMessage());
}
?>