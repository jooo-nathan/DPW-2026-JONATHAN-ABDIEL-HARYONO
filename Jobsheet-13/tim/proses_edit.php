<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/koneksi.php';
require __DIR__ . '/../includes/csrf.php';

csrf_verify();
require __DIR__ . '/../includes/games.php';

$id             = $_POST['id'] ?? null;
$nickname       = strtoupper(trim($_POST['nickname'] ?? ''));
$namaTim        = trim($_POST['nama_tim'] ?? '');
$jumlahAnggota  = trim($_POST['jumlah_anggota'] ?? '');
$game           = trim($_POST['game'] ?? '');

if (!$id) {
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
} catch (PDOException $e) {
    header('Location: edit.php?id=' . urlencode($id) . '&tipe=error&pesan=' . urlencode('Nickname atau nama tim sudah dipakai untuk game ini.'));
    exit;
}

header('Location: list.php?tipe=sukses&pesan=' . urlencode('Tim berhasil diperbarui.'));
exit;
