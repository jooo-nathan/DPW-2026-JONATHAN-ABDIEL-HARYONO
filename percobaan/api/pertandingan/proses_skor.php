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

// Tentukan pemenang dan yang kalah
if ($skor1 > $skor2) {
    $menang = $match['pemain1'];
    $kalah  = $match['pemain2'];
} else {
    $menang = $match['pemain2'];
    $kalah  = $match['pemain1'];
}

// ===== UPDATE — satu-satunya perintah SQL di project ini yang belum
// diajarkan di Jobsheet 8 (baru dibahas di Jobsheet 9). Polanya sebenarnya
// mirip sekali dengan INSERT yang sudah kamu kuasai: tetap pakai
// prepare() + placeholder ":nama" + execute([...]) supaya aman dari SQL
// injection, cuma kata kuncinya beda:
//   INSERT INTO tabel (kolom) VALUES (:nilai)        -> nambah baris baru
//   UPDATE tabel SET kolom = :nilai WHERE syarat      -> ubah baris yang sudah ada
// Baris WHERE di UPDATE itu WAJIB ada — tanpa itu, SEMUA baris di tabel
// bakal ikut berubah, bukan cuma satu match/satu pemain yang dimaksud.

// 1) Simpan skor dan tandai match selesai
$stmt = $pdo->prepare("UPDATE pertandingan SET skor1 = :skor1, skor2 = :skor2, status = 'Completed' WHERE id = :id");
$stmt->execute(['skor1' => $skor1, 'skor2' => $skor2, 'id' => $id]);

// 2) Pemenang +25 MMR
$stmt = $pdo->prepare("UPDATE pemain SET mmr = mmr + 25 WHERE username = :username");
$stmt->execute(['username' => $menang]);

// 3) Yang kalah -15 MMR
$stmt = $pdo->prepare("UPDATE pemain SET mmr = mmr - 15 WHERE username = :username");
$stmt->execute(['username' => $kalah]);

header('Location: list.php?tipe=sukses&pesan=' . urlencode($menang . ' menang! +25 MMR untuk ' . $menang . ', -15 MMR untuk ' . $kalah . '.'));
exit;
