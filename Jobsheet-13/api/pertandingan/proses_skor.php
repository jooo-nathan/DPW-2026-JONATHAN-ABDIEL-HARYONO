<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/koneksi.php';
require __DIR__ . '/../includes/csrf.php';

csrf_verify();

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

// Semua langkah di bawah (skor, MMR menang, MMR kalah, catatan riwayat) harus
// berhasil BERSAMAAN atau batal semuanya -> dibungkus satu transaction.
try {
    $pdo->beginTransaction();

    // Kunci baris match (FOR UPDATE) supaya dua petugas yang submit skor
    // pada match yang sama tidak bisa menambah MMR dua kali.
    $stmt = $pdo->prepare("SELECT * FROM pertandingan WHERE id = :id AND status = 'In-Progress' FOR UPDATE");
    $stmt->execute(['id' => $id]);
    $match = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$match) {
        throw new Exception('Match tidak ditemukan atau sudah selesai.');
    }

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

    // 2) & 3) Ubah MMR, sekaligus ambil id tim + MMR barunya (RETURNING)
    //    untuk dicatat ke riwayat_mmr. Filter game dipertahankan (Jobsheet 8).
    $updMmr = $pdo->prepare(
        "UPDATE tim SET mmr = mmr + :delta WHERE nickname = :nickname AND game = :game RETURNING id, mmr"
    );
    $catat = $pdo->prepare(
        "INSERT INTO riwayat_mmr (tim_id, pertandingan_id, hasil, perubahan, mmr_sesudah)
         VALUES (:tim_id, :match_id, :hasil, :perubahan, :mmr_sesudah)"
    );

    foreach ([[$menang, 25, 'Menang'], [$kalah, -15, 'Kalah']] as [$nick, $delta, $hasil]) {
        $updMmr->execute(['delta' => $delta, 'nickname' => $nick, 'game' => $match['game']]);
        $tim = $updMmr->fetch(PDO::FETCH_ASSOC);
        if (!$tim) {
            throw new Exception('Tim ' . $nick . ' tidak ditemukan.');
        }
        $catat->execute([
            'tim_id' => $tim['id'], 'match_id' => $id, 'hasil' => $hasil,
            'perubahan' => $delta, 'mmr_sesudah' => $tim['mmr'],
        ]);
    }

    $pdo->commit();
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    header('Location: list.php?tipe=error&pesan=' . urlencode($e->getMessage()));
    exit;
}

header('Location: list.php?tipe=sukses&pesan=' . urlencode($menang . ' menang! +25 MMR untuk ' . $menang . ', -15 MMR untuk ' . $kalah . '.'));
exit;
