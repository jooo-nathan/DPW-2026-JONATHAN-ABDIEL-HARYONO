<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/koneksi.php';
require __DIR__ . '/../includes/games.php';

$game = trim($_POST['game'] ?? '');
$tim1 = trim($_POST['tim1'] ?? '');
$tim2 = trim($_POST['tim2'] ?? '');

if (!in_array($game, DAFTAR_GAME, true) || $tim1 === '') {
    header('Location: tambah.php?tipe=error&pesan=' . urlencode('Game dan Team 1 wajib dipilih.'));
    exit;
}
if ($tim1 === $tim2) {
    header('Location: tambah.php?game=' . urlencode($game) . '&tipe=error&pesan=' . urlencode('Team 1 dan Team 2 tidak boleh sama.'));
    exit;
}

// Pertahanan kedua (selain dropdown yang sudah difilter per game di tambah.php):
// pastikan tim1 (dan tim2, kalau diisi) BENAR terdaftar untuk game ini.
// Ini juga otomatis menegakkan aturan "match cuma boleh sesama game" --
// kalau ternyata tim2 terdaftar untuk game lain, ia tidak akan ketemu di sini.
$cekTim = $pdo->prepare("SELECT COUNT(*) FROM tim WHERE nickname = :nickname AND game = :game");
$cekTim->execute(['nickname' => $tim1, 'game' => $game]);
if ($cekTim->fetchColumn() == 0) {
    header('Location: tambah.php?tipe=error&pesan=' . urlencode('Team 1 tidak valid untuk game ini.'));
    exit;
}
if ($tim2 !== '') {
    $cekTim->execute(['nickname' => $tim2, 'game' => $game]);
    if ($cekTim->fetchColumn() == 0) {
        header('Location: tambah.php?game=' . urlencode($game) . '&tipe=error&pesan=' . urlencode('Team 2 tidak valid untuk game ini.'));
        exit;
    }
}

if ($tim2 === '') {
    $status = 'Waiting';
    $tim2   = null;
} else {
    $status = 'In-Progress';
}

$stmt = $pdo->prepare(
    "INSERT INTO pertandingan (game, tim1, tim2, status)
     VALUES (:game, :tim1, :tim2, :status)"
);
$stmt->execute([
    'game'   => $game,
    'tim1'   => $tim1,
    'tim2'   => $tim2,
    'status' => $status,
]);

header('Location: list.php?tipe=sukses&pesan=' . urlencode('Match berhasil dibuat (' . $status . ').'));
exit;
