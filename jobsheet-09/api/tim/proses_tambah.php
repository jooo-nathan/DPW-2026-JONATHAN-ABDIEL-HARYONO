<?php
require __DIR__ . '/../includes/koneksi.php';
require __DIR__ . '/../includes/games.php';

$nickname       = strtoupper(trim($_POST['nickname'] ?? ''));
$namaTim        = trim($_POST['nama_tim'] ?? '');
$jumlahAnggota  = trim($_POST['jumlah_anggota'] ?? '');
$game           = trim($_POST['game'] ?? '');

// Validasi di sisi server
if (!preg_match('/^[A-Z0-9]{1,3}$/', $nickname)) {
    header('Location: tambah.php?tipe=error&pesan=' . urlencode('Nickname harus 1-3 karakter huruf/angka.'));
    exit;
}
if (strlen($namaTim) < 3 || strlen($namaTim) > 30) {
    header('Location: tambah.php?tipe=error&pesan=' . urlencode('Nama tim harus 3-30 karakter.'));
    exit;
}
if (!in_array($game, DAFTAR_GAME, true)) {
    header('Location: tambah.php?tipe=error&pesan=' . urlencode('Pilih game dari daftar.'));
    exit;
}
// Jumlah anggota sifatnya opsional, tapi kalau diisi harus angka wajar
if ($jumlahAnggota !== '' && (!ctype_digit($jumlahAnggota) || (int) $jumlahAnggota < 1 || (int) $jumlahAnggota > 20)) {
    header('Location: tambah.php?tipe=error&pesan=' . urlencode('Jumlah anggota harus angka 1-20 (atau kosongkan saja).'));
    exit;
}
$jumlahAnggota = $jumlahAnggota === '' ? null : (int) $jumlahAnggota;

try {
    $stmt = $pdo->prepare(
        "INSERT INTO tim (nickname, nama_tim, jumlah_anggota, game) VALUES (:nickname, :nama_tim, :jumlah_anggota, :game)"
    );
    $stmt->execute([
        'nickname'       => $nickname,
        'nama_tim'       => $namaTim,
        'jumlah_anggota' => $jumlahAnggota,
        'game'           => $game,
    ]);
} catch (PDOException $e) {
    // Gagal biasanya karena nickname ATAU nama tim sudah dipakai untuk game yang sama
    // (constraint UNIQUE(nickname, game) / UNIQUE(nama_tim, game) di database).
    header('Location: tambah.php?tipe=error&pesan=' . urlencode('Nickname atau nama tim sudah dipakai untuk game ini, pilih yang lain.'));
    exit;
}

header('Location: ../index.php?tipe=sukses&pesan=' . urlencode('Tim ' . $nickname . ' berhasil didaftarkan!'));
exit;
