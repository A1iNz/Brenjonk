<?php
/*
 * =========================================
 * KODE FINAL: KONEKSI.PHP (BERSIH)
 * =========================================
 * - TIDAK ADA 'echo' ATAU 'print' SAMA SEKALI.
 */

// 1. ATUR INFO DATABASE ANDA
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'brenjonk'; // <-- PENTING: Pastikan nama ini SAMA PERSIS

// 2. BUAT KONEKSI
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Aktifkan mode error
$koneksi = new mysqli($db_host, $db_user, $db_pass, $db_name);

// 3. ATUR CHARSET (TANPA 'ECHO')
$koneksi->set_charset("utf8mb4");
