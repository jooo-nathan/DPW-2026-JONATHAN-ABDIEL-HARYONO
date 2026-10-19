<?php
// Membungkus output data sebelum dicetak ke HTML, untuk mencegah XSS
// (Cross-Site Scripting). Dipakai di SEMUA tempat yang mencetak data dari
// database / $_GET / $_SESSION.
function e($value)
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}

// Awalan path relatif menuju ROOT proyek, dihitung dari kedalaman folder
// halaman yang sedang dibuka: "" (root), "../" (tim/, auth/, ...), dst.
// Dipakai header.php & auth.php supaya link CSS/JS/menu/redirect benar
// di mana pun proyek ditaruh (php -S, Laragon, virtual host, subfolder).
function base_path()
{
    $root = str_replace('\\', '/', realpath(__DIR__ . '/..'));
    $dir  = str_replace('\\', '/', realpath(dirname($_SERVER['SCRIPT_FILENAME'])));
    $rel  = trim(substr($dir, strlen($root)), '/');
    return $rel === '' ? '' : str_repeat('../', substr_count($rel, '/') + 1);
}
