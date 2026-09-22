<?php
require __DIR__ . '/../includes/koneksi.php';
require __DIR__ . '/../includes/games.php';

$namaTim = trim($_POST['nama_tim'] ?? '');
$game    = trim($_POST['game'] ?? '');

// Validasi di sisi server
if (strlen($namaTim) < 3 || strlen($namaTim) > 30) {
    header('Location: tambah.php?tipe=error&pesan=' . urlencode('Nama tim harus 3-30 karakter.'));
    exit;
}
if (!in_array($game, DAFTAR_GAME, true)) {
    header('Location: tambah.php?tipe=error&pesan=' . urlencode('Pilih game dari daftar.'));
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO tim (nama_tim, game) VALUES (:nama_tim, :game)");
    $stmt->execute([
        'nama_tim' => $namaTim,
        'game'     => $game,
    ]);
} catch (PDOException $e) {
    // Gagal biasanya karena nama tim sudah ada (kolom UNIQUE)
    header('Location: tambah.php?tipe=error&pesan=' . urlencode('Nama tim sudah dipakai, pilih yang lain.'));
    exit;
}

header('Location: ../index.php?tipe=sukses&pesan=' . urlencode('Tim ' . $namaTim . ' berhasil didaftarkan!'));
exit;
