<?php
require __DIR__ . '/../includes/koneksi.php';

$id    = (int) ($_POST['pertandingan_id'] ?? 0);
$skor1 = (int) ($_POST['skor1'] ?? 0);
$skor2 = (int) ($_POST['skor2'] ?? 0);

// Validasi di sisi server
if ($id === 0) {
    header('Location: list.php?tipe=error&pesan=' . urlencode('Pilih match terlebih dulu.'));
    exit;
}
if ($skor1 < 0 || $skor2 < 0) {
    header('Location: list.php?tipe=error&pesan=' . urlencode('Skor tidak boleh negatif.'));
    exit;
}
if ($skor1 === $skor2) {
    header('Location: list.php?tipe=error&pesan=' . urlencode('Skor tidak boleh seri.'));
    exit;
}

// Ambil match yang masih In-Progress
$stmt = $pdo->prepare("SELECT * FROM pertandingan WHERE id = :id AND status = 'In-Progress'");
$stmt->execute(['id' => $id]);
$match = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$match) {
    header('Location: list.php?tipe=error&pesan=' . urlencode('Match tidak ditemukan atau sudah selesai.'));
    exit;
}

// Tentukan tim menang dan kalah
if ($skor1 > $skor2) {
    $menang = $match['tim1'];
    $kalah  = $match['tim2'];
} else {
    $menang = $match['tim2'];
    $kalah  = $match['tim1'];
}

// 1) Simpan skor dan tandai match selesai
$stmt = $pdo->prepare("UPDATE pertandingan SET skor1 = :skor1, skor2 = :skor2, status = 'Completed' WHERE id = :id");
$stmt->execute(['skor1' => $skor1, 'skor2' => $skor2, 'id' => $id]);

// 2) Tim menang +25 MMR
$stmt = $pdo->prepare("UPDATE tim SET mmr = mmr + 25 WHERE nama_tim = :nama_tim");
$stmt->execute(['nama_tim' => $menang]);

// 3) Tim kalah -15 MMR
$stmt = $pdo->prepare("UPDATE tim SET mmr = mmr - 15 WHERE nama_tim = :nama_tim");
$stmt->execute(['nama_tim' => $kalah]);

header('Location: list.php?tipe=sukses&pesan=' . urlencode($menang . ' menang! +25 MMR untuk ' . $menang . ', -15 MMR untuk ' . $kalah . '.'));
exit;
