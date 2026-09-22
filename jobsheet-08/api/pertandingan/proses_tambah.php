<?php
require __DIR__ . '/../includes/koneksi.php';
require __DIR__ . '/../includes/games.php';

$game = trim($_POST['game'] ?? '');
$tim1 = trim($_POST['tim1'] ?? '');
$tim2 = trim($_POST['tim2'] ?? '');

// Validasi di sisi server
if (!in_array($game, DAFTAR_GAME, true) || $tim1 === '') {
    header('Location: tambah.php?tipe=error&pesan=' . urlencode('Game dan Team 1 wajib dipilih.'));
    exit;
}
if ($tim1 === $tim2) {
    header('Location: tambah.php?tipe=error&pesan=' . urlencode('Team 1 dan Team 2 tidak boleh sama.'));
    exit;
}

// Ada lawan = In-Progress, tidak ada lawan = Waiting
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
