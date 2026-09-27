<?php
require __DIR__ . '/includes/koneksi.php';

// Kosongkan kedua tabel. Urutan penting: pertandingan dihapus dulu
// karena datanya merujuk ke nama tim.
$pdo->exec("DELETE FROM pertandingan");
$pdo->exec("DELETE FROM tim");

header('Location: index.php?tipe=sukses&pesan=' . urlencode('Semua data berhasil direset.'));
exit;
