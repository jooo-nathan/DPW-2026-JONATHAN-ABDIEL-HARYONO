<?php
require __DIR__ . '/../includes/koneksi.php';
require __DIR__ . '/../includes/helper.php';

$username  = trim($_POST['username'] ?? '');
$gameUtama = trim($_POST['game_utama'] ?? '');

// Validasi di sisi server
$errors = [];
if ($username === '') {
    $errors[] = "Username wajib diisi.";
} elseif (!preg_match('/^[A-Za-z0-9_]{3,20}$/', $username)) {
    $errors[] = "Username 3-20 karakter, hanya huruf, angka, dan garis bawah.";
}
if (!in_array($gameUtama, DAFTAR_GAME, true)) {
    $errors[] = "Pilih game utama dari daftar.";
}

if (!empty($errors)) {
    flash_redirect('tambah.php', 'error', implode(' ', $errors));
}

try {
    $stmt = $pdo->prepare(
        "INSERT INTO pemain (username, game_utama) VALUES (:username, :game_utama)"
    );
    $stmt->execute([
        'username'   => $username,
        'game_utama' => $gameUtama,
    ]);
} catch (PDOException $e) {
    // 23505 = kode error PostgreSQL untuk nilai UNIQUE yang sudah ada
    if ($e->getCode() === '23505') {
        flash_redirect('tambah.php', 'error', 'Username sudah dipakai, pilih yang lain.');
    }
    flash_redirect('tambah.php', 'error', 'Gagal mendaftar, coba lagi.');
}

flash_redirect('../leaderboard.php', 'success', 'Selamat datang di arena, ' . $username . '!');
