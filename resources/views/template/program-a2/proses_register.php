<?php
// (Semua kode di atas sama)
header('Content-Type: application/json');
session_start();
include 'koneksi.php';
$response = ['success' => false, 'message' => 'Terjadi kesalahan.'];

try {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $response['message'] = 'Username dan Password wajib diisi.';
        echo json_encode($response);
        exit;
    }

    // --- PERUBAHAN DI SINI ---
    // 4. Tentukan Role DAN Blokir Nama Terlarang
    $role_to_insert = '';
    $username_lower = strtolower($username);

    if ($username_lower === 'admin' || $username_lower === 'superadmin') {
        $response['message'] = 'Username "' . htmlspecialchars($username) . '" tidak diizinkan.';
        echo json_encode($response);
        exit;
    } else {
        $role_to_insert = 'petani';
    }
    // --- AKHIR PERUBAHAN ---

    // 5. Hash password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // 6. Siapkan query SQL
    $sql = "INSERT INTO users (username, password_plain, password_hash, role) 
            VALUES (?, ?, ?, ?)";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("ssss", $username, $password, $password_hash, $role_to_insert);

    // 7. Eksekusi query
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Registrasi berhasil!';
    } else {
        if ($koneksi->errno == 1062) { 
            $response['message'] = 'Username "' . htmlspecialchars($username) . '" sudah dipakai.';
        } else {
            $response['message'] = 'Gagal mendaftar: ' . $stmt->error;
        }
    }
    $stmt->close();
    $koneksi->close();

} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
exit;
?>