<?php
/*
 * =========================================
 * KODE: TAMBAH_PANEN.PHP
 * =========================================
 * - Menerima data panen dari form dashboard petani
 * - Menyimpannya ke database
 * - (DIPERBARUI) Mengembalikan ID baru agar JavaScript bisa menggunakannya
 */

// 1. TENTUKAN HEADER SEBAGAI JSON (WAJIB)
header('Content-Type: application/json');

// 2. MULAI SESSION (Untuk mendapatkan ID petani yang login)
session_start();

// 3. BUAT FUNGSI BALASAN
function kirimBalasan($status, $pesan, $data = []) {
    $response = ['success' => $status, 'message' => $pesan];
    if (!empty($data)) {
        $response = array_merge($response, $data);
    }
    echo json_encode($response);
    exit; // Wajib
}

// 4. GUNAKAN 'TRY...CATCH' UNTUK MENANGKAP SEMUA ERROR
try {
    // 5. SERTAKAN FILE KONEKSI
    require 'koneksi.php'; // Pastikan ini memanggil file koneksi.php yang BERSIH

    // 6. CEK APAKAH USER SUDAH LOGIN
    // (PENTING: Pastikan semua file Anda KONSISTEN menggunakan 'user_id')
    if (!isset($_SESSION['user_id'])) {
        kirimBalasan(false, 'Akses ditolak. Anda harus login terlebih dahulu.');
    }
    
    // 7. CEK KONEKSI
    if (!isset($koneksi) || (isset($koneksiError))) {
        kirimBalasan(false, 'Gagal terhubung ke database.');
    }

    // 8. AMBIL DATA DARI POST
    $produk = $_POST['produk'] ?? '';
    $jumlah = $_POST['jumlah'] ?? '';
    $tanggal = $_POST['tanggal_panen'] ?? '';
    $petani_id = $_SESSION['user_id']; // Ambil ID dari session

    // 9. VALIDASI INPUT
    if (empty($produk) || empty($jumlah) || empty($tanggal)) {
        kirimBalasan(false, 'Semua data wajib diisi.');
    }

    // 10. MASUKKAN DATA KE TABEL 'produk_panen'
    // Status default adalah 'Menunggu'
    // (PENTING: Pastikan nama tabel & kolom ini benar)
    $sql_insert = "INSERT INTO produk_panen (petani_id, nama_produk, jumlah_kg, tgl_panen, status) 
                   VALUES (?, ?, ?, ?, 'Menunggu')";
    
    $stmt_insert = $koneksi->prepare($sql_insert);
    
    // "isds" = integer, string, double/integer, string (date)
    $stmt_insert->bind_param("isds", $petani_id, $produk, $jumlah, $tanggal);

    // 11. EKSEKUSI DAN KIRIM BALASAN
    if ($stmt_insert->execute()) {
        
        // --- INI BARIS BARU YANG SAYA TAMBAHKAN ---
        // Ambil ID dari data yang baru saja dimasukkan
        $new_id = $koneksi->insert_id; 
        
        // --- INI BARIS YANG SAYA MODIFIKASI ---
        // Kirim balasan sukses BESERTA ID BARU
        kirimBalasan(true, 'Data berhasil ditambahkan.', ['new_id' => $new_id]);

    } else {
        kirimBalasan(false, 'Gagal menyimpan data ke database.');
    }

    $stmt_insert->close();
    $koneksi->close();

} catch (mysqli_sql_exception $e) {
    kirimBalasan(false, "DATABASE ERROR: " . $e->getMessage());
} catch (Exception $e) {
    kirimBalasan(false, "PHP ERROR: " . $e->getMessage());
}
?>