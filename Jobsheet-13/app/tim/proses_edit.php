<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/koneksi.php';
require __DIR__ . '/../includes/csrf.php';

csrf_verify();
require __DIR__ . '/../includes/games.php';

$id             = (int) ($_POST['id'] ?? 0);
$nickname       = strtoupper(trim($_POST['nickname'] ?? ''));
$namaTim        = trim($_POST['nama_tim'] ?? '');
$jumlahAnggota  = trim($_POST['jumlah_anggota'] ?? '');
$game           = trim($_POST['game'] ?? '');

if ($id < 1) {
    header('Location: list.php');
    exit;
}

// Validasi identik dengan tim/proses_tambah.php -- aturan yang sama berlaku
// baik saat mendaftar tim baru maupun mengubah data tim yang sudah ada.
if (!preg_match('/^[A-Z0-9]{1,3}$/', $nickname)) {
    header('Location: edit.php?id=' . urlencode($id) . '&tipe=error&pesan=' . urlencode('Nickname harus 1-3 karakter huruf/angka.'));
    exit;
}
if (strlen($namaTim) < 3 || strlen($namaTim) > 30) {
    header('Location: edit.php?id=' . urlencode($id) . '&tipe=error&pesan=' . urlencode('Nama tim harus 3-30 karakter.'));
    exit;
}
if (!in_array($game, DAFTAR_GAME, true)) {
    header('Location: edit.php?id=' . urlencode($id) . '&tipe=error&pesan=' . urlencode('Pilih game dari daftar.'));
    exit;
}
if ($jumlahAnggota !== '' && (!ctype_digit($jumlahAnggota) || (int) $jumlahAnggota < 1 || (int) $jumlahAnggota > 20)) {
    header('Location: edit.php?id=' . urlencode($id) . '&tipe=error&pesan=' . urlencode('Jumlah anggota harus angka 1-20 (atau kosongkan saja).'));
    exit;
}
$jumlahAnggota = $jumlahAnggota === '' ? null : (int) $jumlahAnggota;

try {
    $pdo->beginTransaction();

    $lama = $pdo->prepare("SELECT nickname, game FROM tim WHERE id = :id FOR UPDATE");
    $lama->execute(['id' => $id]);
    $timLama = $lama->fetch(PDO::FETCH_ASSOC);
    if (!$timLama) {
        $pdo->rollBack();
        header('Location: list.php');
        exit;
    }

    // Match menyimpan NICKNAME tim (bukan id). Kalau nickname/game berubah,
    // referensi di tabel pertandingan harus ikut diperbarui supaya tidak yatim.
    $nickBerubah = $timLama['nickname'] !== $nickname;
    $gameBerubah = $timLama['game'] !== $game;

    if ($gameBerubah) {
        $adaMatch = $pdo->prepare("SELECT COUNT(*) FROM pertandingan WHERE game = :game AND (tim1 = :n1 OR tim2 = :n2)");
        $adaMatch->execute(['game' => $timLama['game'], 'n1' => $timLama['nickname'], 'n2' => $timLama['nickname']]);
        if ($adaMatch->fetchColumn() > 0) {
            $pdo->rollBack();
            header('Location: edit.php?id=' . $id . '&tipe=error&pesan=' . urlencode('Game tidak bisa diganti karena tim ini sudah punya pertandingan.'));
            exit;
        }
    }

    $stmt = $pdo->prepare(
        "UPDATE tim SET nickname = :nickname, nama_tim = :nama_tim, jumlah_anggota = :jumlah_anggota, game = :game
         WHERE id = :id"
    );
    $stmt->execute([
        'nickname'       => $nickname,
        'nama_tim'       => $namaTim,
        'jumlah_anggota' => $jumlahAnggota,
        'game'           => $game,
        'id'             => $id,
    ]);

    if ($nickBerubah && !$gameBerubah) {
        foreach (['tim1', 'tim2'] as $kolom) {
            $upd = $pdo->prepare("UPDATE pertandingan SET $kolom = :baru WHERE game = :game AND $kolom = :lama");
            $upd->execute(['baru' => $nickname, 'game' => $game, 'lama' => $timLama['nickname']]);
        }
    }

    $pdo->commit();
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    header('Location: edit.php?id=' . urlencode($id) . '&tipe=error&pesan=' . urlencode('Nickname atau nama tim sudah dipakai untuk game ini.'));
    exit;
}

header('Location: list.php?tipe=sukses&pesan=' . urlencode('Tim berhasil diperbarui.'));
exit;
