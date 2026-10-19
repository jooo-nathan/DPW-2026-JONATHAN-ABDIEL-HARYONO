<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/koneksi.php';
require __DIR__ . '/../includes/csrf.php';

// Sengaja hanya menerima POST (bukan GET) supaya penghapusan tidak bisa
// terpicu tanpa sengaja lewat tautan biasa/crawler (pola Jobsheet 9).
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

csrf_verify();

$id = (int) ($_POST['id'] ?? 0);
if ($id > 0) {
    // Jangan hapus tim yang masih punya match belum selesai (Waiting / In-Progress),
    // karena match itu merujuk ke nickname tim dan skornya tidak bisa disubmit lagi.
    $cek = $pdo->prepare(
        "SELECT COUNT(*) FROM pertandingan p JOIN tim t ON t.game = p.game
         WHERE t.id = :id AND p.status <> 'Completed' AND (p.tim1 = t.nickname OR p.tim2 = t.nickname)"
    );
    $cek->execute(['id' => $id]);
    if ($cek->fetchColumn() > 0) {
        header('Location: list.php?tipe=error&pesan=' . urlencode('Tim masih punya match yang belum selesai. Hapus atau selesaikan match-nya dulu.'));
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM tim WHERE id = :id");
    $stmt->execute(['id' => $id]);
}

header('Location: list.php?tipe=sukses&pesan=' . urlencode('Tim berhasil dihapus.'));
exit;
