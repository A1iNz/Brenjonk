<?php
/*
 * File: db_config.php
 * Konfigurasi koneksi database
 */

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); // Ganti jika username Laragon/XAMPP Anda berbeda
define('DB_PASSWORD', ''); // Ganti jika password Laragon/XAMPP Anda berbeda
define('DB_NAME', 'program_a2'); // Ganti dengan nama database Anda

// Coba koneksi ke database
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Cek koneksi
if($mysqli === false){
    die("ERROR: Tidak bisa terhubung ke database. " . $mysqli->connect_error);
}
?>