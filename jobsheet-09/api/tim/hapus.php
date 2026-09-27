<?php
require __DIR__ . '/../includes/koneksi.php';

// Sengaja hanya menerima POST (bukan GET) supaya penghapusan tidak bisa
// terpicu tanpa sengaja lewat tautan biasa/crawler (pola Jobsheet 9).
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id = $_POST['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM tim WHERE id = :id");
    $stmt->execute(['id' => $id]);
}

header('Location: list.php?tipe=sukses&pesan=' . urlencode('Tim berhasil dihapus.'));
exit;
