<?php
session_start();
require_once __DIR__ . '/../includes/games.php';

$nickname = trim($_POST['nickname'] ?? '');
$namaTim = trim($_POST['nama_tim'] ?? '');
$jumlah = trim($_POST['jumlah_anggota'] ?? '');
$game = trim($_POST['game'] ?? '');

// Validasi server-side: wajib ada walau sudah divalidasi JS di Jobsheet 5,
// karena validasi client bisa dilewati (nonaktifkan JS / kirim request manual).
$errors = [];
if (!preg_match('/^[A-Za-z0-9]{1,3}$/', $nickname)) {
    $errors[] = "Nickname wajib 1-3 karakter (huruf atau angka).";
}
if (strlen($namaTim) < 3 || strlen($namaTim) > 30) {
    $errors[] = "Nama tim wajib 3-30 karakter.";
}
if ($jumlah !== '' && (!is_numeric($jumlah) || $jumlah < 1 || $jumlah > 20)) {
    $errors[] = "Jumlah anggota harus 1-20 (boleh dikosongkan).";
}
if (!in_array($game, DAFTAR_GAME, true)) {
    $errors[] = "Game tidak valid.";
}

// Nickname + game, juga nama tim + game, tidak boleh kembar
if (empty($errors)) {
    foreach ($_SESSION['tim'] ?? [] as $tim) {
        if ($tim['game'] === $game && strcasecmp($tim['nickname'], $nickname) === 0) {
            $errors[] = "Nickname \"$nickname\" sudah dipakai di game $game.";
            break;
        }
        if ($tim['game'] === $game && strcasecmp($tim['nama_tim'], $namaTim) === 0) {
            $errors[] = "Nama tim \"$namaTim\" sudah dipakai di game $game.";
            break;
        }
    }
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: tambah.php');
    exit;
}

if (!isset($_SESSION['tim'])) {
    $_SESSION['tim'] = [];
}

$_SESSION['tim'][] = [
    'nickname' => $nickname,
    'nama_tim' => $namaTim,
    'jumlah_anggota' => $jumlah === '' ? null : (int) $jumlah,
    'game' => $game,
    'mmr' => 1000,
];

$_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Tim berhasil didaftarkan.'];
header('Location: list.php');
exit;
