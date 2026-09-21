<?php
require __DIR__ . '/../includes/koneksi.php';

$username  = trim($_POST['username'] ?? '');
$gameUtama = trim($_POST['game_utama'] ?? '');

// Validasi di sisi server
if (strlen($username) < 3 || strlen($username) > 20) {
    header('Location: tambah.php?tipe=error&pesan=' . urlencode('Username harus 3-20 karakter.'));
    exit;
}
if ($gameUtama === '') {
    header('Location: tambah.php?tipe=error&pesan=' . urlencode('Game utama wajib dipilih.'));
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO pemain (username, game_utama) VALUES (:username, :game_utama)");
    $stmt->execute([
        'username'   => $username,
        'game_utama' => $gameUtama,
    ]);
} catch (PDOException $e) {
    // Gagal biasanya karena username sudah ada (kolom UNIQUE)
    header('Location: tambah.php?tipe=error&pesan=' . urlencode('Username sudah dipakai, pilih yang lain.'));
    exit;
}

header('Location: ../index.php?tipe=sukses&pesan=' . urlencode('Selamat datang di arena, ' . $username . '!'));
exit;
