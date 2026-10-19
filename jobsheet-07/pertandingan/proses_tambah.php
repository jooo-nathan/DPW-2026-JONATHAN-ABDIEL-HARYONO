<?php
session_start();
require_once __DIR__ . '/../includes/games.php';

$game = trim($_POST['game'] ?? '');
$tim1 = trim($_POST['tim1'] ?? '');
$tim2 = trim($_POST['tim2'] ?? '');

// Mencari tim berdasarkan nickname + game di $_SESSION['tim']
function tim_ada($nickname, $game)
{
    foreach ($_SESSION['tim'] ?? [] as $t) {
        if ($t['nickname'] === $nickname && $t['game'] === $game) {
            return true;
        }
    }
    return false;
}

$errors = [];
if (!in_array($game, DAFTAR_GAME, true)) {
    $errors[] = "Game tidak valid.";
} else {
    if ($tim1 === '' || !tim_ada($tim1, $game)) {
        $errors[] = "Team 1 harus dipilih dari tim yang bermain $game.";
    }
    if ($tim2 !== '') {
        if ($tim2 === $tim1) {
            $errors[] = "Team 2 tidak boleh sama dengan Team 1.";
        } elseif (!tim_ada($tim2, $game)) {
            $errors[] = "Team 2 harus dipilih dari tim yang bermain $game.";
        }
    }
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: tambah.php');
    exit;
}

if (!isset($_SESSION['pertandingan'])) {
    $_SESSION['pertandingan'] = [];
}

// Tanpa lawan = Waiting, dengan lawan = In-Progress
$_SESSION['pertandingan'][] = [
    'game' => $game,
    'tim1' => $tim1,
    'tim2' => $tim2 === '' ? '-' : $tim2,
    'skor' => '-',
    'status' => $tim2 === '' ? 'Waiting' : 'In-Progress',
];

$_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Match berhasil dibuat.'];
header('Location: list.php');
exit;
