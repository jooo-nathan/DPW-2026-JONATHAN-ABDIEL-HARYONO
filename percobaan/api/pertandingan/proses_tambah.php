<?php
require __DIR__ . '/../includes/koneksi.php';

$game    = trim($_POST['game'] ?? '');
$pemain1 = trim($_POST['pemain1'] ?? '');
$pemain2 = trim($_POST['pemain2'] ?? '');

// Validasi di sisi server
if ($game === '' || $pemain1 === '') {
    header('Location: tambah.php?tipe=error&pesan=' . urlencode('Game dan Player 1 wajib dipilih.'));
    exit;
}
if ($pemain1 === $pemain2) {
    header('Location: tambah.php?tipe=error&pesan=' . urlencode('Player 1 dan Player 2 tidak boleh sama.'));
    exit;
}

// Ada lawan = In-Progress, tidak ada lawan = Waiting
if ($pemain2 === '') {
    $status  = 'Waiting';
    $pemain2 = null;
} else {
    $status = 'In-Progress';
}

$stmt = $pdo->prepare(
    "INSERT INTO pertandingan (game, pemain1, pemain2, status)
     VALUES (:game, :pemain1, :pemain2, :status)"
);
$stmt->execute([
    'game'    => $game,
    'pemain1' => $pemain1,
    'pemain2' => $pemain2,
    'status'  => $status,
]);

header('Location: list.php?tipe=sukses&pesan=' . urlencode('Match berhasil dibuat (' . $status . ').'));
exit;
