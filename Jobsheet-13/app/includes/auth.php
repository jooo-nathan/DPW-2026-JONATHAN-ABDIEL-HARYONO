<?php
// Guard clause: di-require di baris PALING ATAS setiap halaman yang
// butuh login -- sebelum header.php mengeluarkan output apa pun, supaya
// header('Location: ...') masih bisa dipanggil (belum ada HTML terkirim).
require_once __DIR__ . '/helpers.php';

require_once __DIR__ . '/session.php';
mulai_session();

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . base_path() . 'auth/login.php');
    exit;
}
