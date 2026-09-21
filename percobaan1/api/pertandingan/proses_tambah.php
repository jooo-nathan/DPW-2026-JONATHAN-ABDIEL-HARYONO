<?php
require __DIR__ . '/../includes/koneksi.php';
require __DIR__ . '/../includes/helper.php';

$game    = trim($_POST['game'] ?? '');
$pemain1 = trim($_POST['pemain1'] ?? '');
$pemain2 = trim($_POST['pemain2'] ?? '');

$errors = [];
if (!in_array($game, DAFTAR_GAME, true)) {
    $errors[] = "Pilih game dari daftar.";
}
if ($pemain1 === '') {
    $errors[] = "Player 1 wajib dipilih.";
}
if ($pemain2 !== '' && $pemain2 === $pemain1) {
    $errors[] = "Player 1 dan Player 2 tidak boleh sama.";
}

if (!empty($errors)) {
    flash_redirect('tambah.php', 'error', implode(' ', $errors));
}

// Ada lawan -> In-Progress, tidak ada lawan -> Waiting
$status = $pemain2 === '' ? 'Waiting' : 'In-Progress';

try {
    $stmt = $pdo->prepare(
        "INSERT INTO pertandingan (game, pemain1, pemain2, status)
         VALUES (:game, :pemain1, :pemain2, :status)"
    );
    $stmt->execute([
        'game'    => $game,
        'pemain1' => $pemain1,
        'pemain2' => $pemain2 === '' ? null : $pemain2,
        'status'  => $status,
    ]);
} catch (PDOException $e) {
    flash_redirect('tambah.php', 'error', 'Gagal membuat match. Pastikan pemain yang dipilih terdaftar.');
}

flash_redirect('list.php', 'success', 'Match berhasil dibuat (' . $status . ').');
