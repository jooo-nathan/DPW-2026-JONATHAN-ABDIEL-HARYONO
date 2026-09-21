<?php
require __DIR__ . '/../includes/koneksi.php';
require __DIR__ . '/../includes/helper.php';

$id      = (int) ($_POST['id'] ?? 0);
$pemain2 = trim($_POST['pemain2'] ?? '');

if ($id <= 0 || $pemain2 === '') {
    flash_redirect('list.php', 'error', 'Pilih lawan terlebih dulu.');
}

try {
    // Hanya lobi Waiting yang bisa di-join, dan lawan tidak boleh sama dengan host
    $stmt = $pdo->prepare(
        "UPDATE pertandingan
         SET pemain2 = :pemain2, status = 'In-Progress'
         WHERE id = :id AND status = 'Waiting' AND pemain1 <> :pemain2"
    );
    $stmt->execute(['pemain2' => $pemain2, 'id' => $id]);
} catch (PDOException $e) {
    flash_redirect('list.php', 'error', 'Gagal join lobi. Pastikan pemain terdaftar.');
}

if ($stmt->rowCount() === 0) {
    flash_redirect('list.php', 'error', 'Lobi tidak bisa di-join (sudah terisi atau lawan sama dengan host).');
}

flash_redirect('list.php', 'success', 'Berhasil join lobi #' . $id . '. Match sekarang In-Progress.');
