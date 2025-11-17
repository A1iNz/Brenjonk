<?php

// --- KETIK PASSWORD BARU ANDA DI SINI ---
$password_baru_saya = "123";
// -------------------------------------

// Kode ini akan menghitung hash yang aman untuk Anda
$hash = password_hash($password_baru_saya, PASSWORD_DEFAULT);

// Tampilkan hash-nya di layar
echo "Hash baru Anda adalah: <br><br>";

// 'echo' ini PENTING untuk di-copy
echo $hash;

?>