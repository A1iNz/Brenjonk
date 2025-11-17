<?php
// SELALU di baris paling atas (baris 1)
session_start();

// Halaman ini HANYA untuk role 'superadmin'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
    die("Akses dilarang.");
}

include 'koneksi.php';
$redirect_url = "superadmin_panel.php";

try {
    // --- AKSI: TAMBAH USER ---
    if (isset($_POST['aksi']) && $_POST['aksi'] == 'tambah') {
        $username = $_POST['username'] ?? '';
        $nama_petani = $_POST['nama_petani'] ?? ''; // Ambil nama petani
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'petani';
        
        if (empty($username) || empty($nama_petani) || empty($password) || empty($role)) {
            throw new Exception("Semua field wajib diisi.");
        }
        
        if ($role !== 'petani' && $role !== 'admin') {
            throw new Exception("Role tidak valid.");
        }
        
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Query disesuaikan dengan database Anda
        $sql = "INSERT INTO users (username, nama_petani, password_plain, password_hash, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("sssss", $username, $nama_petani, $password, $password_hash, $role);
        
        if ($stmt->execute()) {
            $message = "User " . htmlspecialchars($username) . " berhasil ditambahkan.";
        } else {
            throw new Exception("Gagal menambah user: " . $stmt->error);
        }
        $stmt->close();
    } 
    
    // --- AKSI: EDIT USER ---
    elseif (isset($_POST['aksi']) && $_POST['aksi'] == 'edit') {
        $id = $_POST['id'] ?? 0;
        $nama_petani = $_POST['nama_petani'] ?? ''; // Ambil nama petani
        $role = $_POST['role'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($id) || empty($nama_petani) || empty($role)) {
            throw new Exception("ID, Nama, atau Role tidak boleh kosong.");
        }
        
        if ($role !== 'petani' && $role !== 'admin') {
            throw new Exception("Role tidak valid.");
        }
        
        if (!empty($password)) {
            // Update nama, role, DAN password
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET nama_petani = ?, role = ?, password_plain = ?, password_hash = ? WHERE id = ?";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("ssssi", $nama_petani, $role, $password, $password_hash, $id);
        } else {
            // Update nama dan role SAJA
            $sql = "UPDATE users SET nama_petani = ?, role = ? WHERE id = ?";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("ssi", $nama_petani, $role, $id);
        }
        
        if ($stmt->execute()) {
            $message = "User berhasil diupdate.";
        } else {
            throw new Exception("Gagal mengupdate user: " . $stmt->error);
        }
        $stmt->close();
    }
    
    // --- AKSI: HAPUS USER ---
    elseif (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
        $id = $_GET['id'] ?? 0;
        
        if (empty($id)) {
            throw new Exception("ID user tidak boleh kosong.");
        }
        
        if ($id == $_SESSION['user_id']) {
            throw new Exception("Anda tidak bisa menghapus akun Anda sendiri.");
        }
        
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $message = "User berhasil dihapus.";
        } else {
            throw new Exception("Gagal menghapus user: " . $stmt->error);
        }
        $stmt->close();
    }
    
    else {
        throw new Exception("Aksi tidak dikenal.");
    }
    
    $koneksi->close();
    header("Location: $redirect_url?message=" . urlencode($message));
    exit;
    
} catch (Exception $e) {
    $koneksi->close();
    $error_message = $e->getMessage();
    header("Location: $redirect_url?message=" . urlencode("Error: " . $error_message));
    exit;
}
?>