<?php
require __DIR__ . '/../includes/koneksi.php';
require __DIR__ . '/../includes/helper.php';

$id    = (int) ($_POST['pertandingan_id'] ?? 0);
$skor1 = trim($_POST['skor1'] ?? '');
$skor2 = trim($_POST['skor2'] ?? '');

// Validasi di sisi server
$errors = [];
if ($id <= 0) {
    $errors[] = "Pilih pertandingan.";
}
if (!ctype_digit($skor1) || !ctype_digit($skor2)) {
    $errors[] = "Skor harus berupa angka 0 atau lebih.";
} elseif ((int) $skor1 === (int) $skor2) {
    $errors[] = "Skor tidak boleh seri.";
}

if (!empty($errors)) {
    flash_redirect('list.php', 'error', implode(' ', $errors));
}

// Ambil pertandingan yang masih In-Progress
$stmt = $pdo->prepare("SELECT * FROM pertandingan WHERE id = :id AND status = 'In-Progress'");
$stmt->execute(['id' => $id]);
$match = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$match) {
    flash_redirect('list.php', 'error', 'Pertandingan tidak ditemukan atau sudah selesai.');
}

$skor1 = (int) $skor1;
$skor2 = (int) $skor2;
$menang = $skor1 > $skor2 ? $match['pemain1'] : $match['pemain2'];
$kalah  = $skor1 > $skor2 ? $match['pemain2'] : $match['pemain1'];

// Tiga UPDATE dibungkus transaksi: semuanya berhasil, atau tidak ada yang berubah
try {
    $pdo->beginTransaction();

    $pdo->prepare(
        "UPDATE pertandingan SET skor1 = :skor1, skor2 = :skor2, status = 'Completed' WHERE id = :id"
    )->execute(['skor1' => $skor1, 'skor2' => $skor2, 'id' => $id]);

    $pdo->prepare("UPDATE pemain SET mmr = mmr + 25 WHERE username = :username")
        ->execute(['username' => $menang]);

    // GREATEST supaya MMR tidak pernah di bawah 0
    $pdo->prepare("UPDATE pemain SET mmr = GREATEST(mmr - 15, 0) WHERE username = :username")
        ->execute(['username' => $kalah]);

    $pdo->commit();
} catch (PDOException $e) {
    $pdo->rollBack();
    flash_redirect('list.php', 'error', 'Gagal menyimpan skor, coba lagi.');
}

flash_redirect('list.php', 'success', $menang . ' menang! +25 MMR untuk ' . $menang . ', -15 MMR untuk ' . $kalah . '.');
