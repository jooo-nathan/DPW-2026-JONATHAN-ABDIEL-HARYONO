<?php
require __DIR__ . '/../includes/koneksi.php';

$id = (int) ($_POST['id'] ?? 0);

$stmt = $pdo->prepare("DELETE FROM pertandingan WHERE id = :id");
$stmt->execute(['id' => $id]);

header('Location: list.php?tipe=sukses&pesan=' . urlencode('Match berhasil dihapus.'));
exit;
