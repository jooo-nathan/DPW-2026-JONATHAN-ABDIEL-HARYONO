<?php
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/koneksi.php';
require __DIR__ . '/includes/csrf.php';

// Hanya terima POST, lalu cek token CSRF sebelum menyentuh database.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

csrf_verify();

// Kosongkan kedua tabel. Urutan penting: pertandingan dihapus dulu
// karena datanya merujuk ke nama tim.
$pdo->exec("DELETE FROM pertandingan");
$pdo->exec("DELETE FROM tim");

header('Location: index.php?tipe=sukses&pesan=' . urlencode('Semua data berhasil direset.'));
exit;
