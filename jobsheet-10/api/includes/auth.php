<?php
// Guard clause: di-require di baris PALING ATAS setiap halaman yang
// butuh login -- sebelum header.php mengeluarkan output apa pun, supaya
// header('Location: ...') di sini masih bisa dipanggil (belum ada HTML
// yang terkirim ke browser sama sekali).
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    // Path relatif dihitung sendiri (logikanya sama seperti $base di
    // header.php) supaya redirect ini benar baik dipanggil dari file di
    // root api/ (reset.php) maupun dari dalam subfolder (tim/, pertandingan/).
    $__root = dirname(__DIR__);
    $__scriptDir = dirname($_SERVER['SCRIPT_FILENAME']);
    $__rel = ltrim(str_replace('\\', '/', substr($__scriptDir, strlen($__root))), '/');
    $__base = $__rel === '' ? '' : str_repeat('../', substr_count($__rel, '/') + 1);
    header('Location: ' . $__base . 'auth/login.php');
    exit;
}
