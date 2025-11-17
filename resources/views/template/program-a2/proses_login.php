<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
session_start();

include 'koneksi.php';

$response = [
    'success' => false,
    'message' => 'Terjadi kesalahan yang tidak diketahui.',
    'redirect_url' => null 
];

try {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $response['message'] = 'Username dan Password wajib diisi.';
        echo json_encode($response);
        exit;
    }

    // Ambil 'nama_petani' yang asli dari database
    $sql = "SELECT id, password_hash, role, username, nama_petani 
            FROM users 
            WHERE username = ?";
            
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password_hash'])) {
            // Password BENAR!
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; 
            
            // === INI PERBAIKANNYA ===
            // Jika nama_petani KOSONG (null), gunakan username sebagai gantinya.
            $_SESSION['nama_petani'] = $user['nama_petani'] ?? $user['username']; 

            $response['success'] = true;
            $response['message'] = 'Login berhasil!';
            
            if ($user['role'] === 'superadmin') {
                $response['redirect_url'] = 'superadmin_panel.php';
            } elseif ($user['role'] === 'admin') {
                $response['redirect_url'] = 'dashboard_kelompoktani.php';
            } else {
                $response['redirect_url'] = 'dashboard_petani.php';
            }

        } else {
            $response['message'] = 'Password yang Anda masukkan salah.';
        }
    } else {
        $response['message'] = 'Username tidak ditemukan.';
    }
    
    $stmt->close();
    $koneksi->close();

} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
exit;
?>