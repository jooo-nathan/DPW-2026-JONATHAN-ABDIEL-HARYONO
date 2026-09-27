<?php
// Front controller tunggal.
// Semua request (lihat vercel.json) masuk ke sini, lalu diteruskan ke file
// halaman yang sesuai. Ini dipakai supaya di Vercel cuma ada SATU Serverless
// Function terdaftar (paket Hobby dibatasi max 12 function per deployment;
// kalau tiap halaman didaftarkan sebagai function sendiri-sendiri, jumlahnya
// bisa lebih dari itu dan deployment ditolak).

$__path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$__path = '/' . ltrim((string) $__path, '/');

// --- Aset statis (CSS/JS) ---
// File di dalam api/ tidak disajikan sebagai file statis oleh Vercel,
// jadi dibaca & dikirim manual lewat PHP di sini (dulunya di asset.php).
if (preg_match('#^/assets/(.+)$#', $__path, $__m)) {
    $__tipe = ['css' => 'text/css; charset=utf-8', 'js' => 'application/javascript; charset=utf-8'];
    $__root = realpath(__DIR__ . '/assets');
    $__file = realpath($__root . '/' . $__m[1]);
    $__ext  = $__file ? pathinfo($__file, PATHINFO_EXTENSION) : '';

    if (!$__file || strpos($__file, $__root . DIRECTORY_SEPARATOR) !== 0 || !isset($__tipe[$__ext])) {
        http_response_code(404);
        exit('File tidak ditemukan.');
    }

    header('Content-Type: ' . $__tipe[$__ext]);
    header('Cache-Control: public, max-age=3600');
    readfile($__file);
    exit;
}

// --- Peta URL publik -> file halaman fisik ---
$__routes = [
    '/'                               => __DIR__ . '/home.php',
    '/index.php'                      => __DIR__ . '/home.php',
    '/reset.php'                      => __DIR__ . '/reset.php',

    '/auth/login.php'                 => __DIR__ . '/auth/login.php',
    '/auth/logout.php'                => __DIR__ . '/auth/logout.php',
    '/auth/register.php'              => __DIR__ . '/auth/register.php',
    '/auth/proses_login.php'          => __DIR__ . '/auth/proses_login.php',
    '/auth/proses_register.php'       => __DIR__ . '/auth/proses_register.php',

    '/tim/list.php'                   => __DIR__ . '/tim/list.php',
    '/tim/tambah.php'                 => __DIR__ . '/tim/tambah.php',
    '/tim/edit.php'                   => __DIR__ . '/tim/edit.php',
    '/tim/hapus.php'                  => __DIR__ . '/tim/hapus.php',
    '/tim/proses_tambah.php'          => __DIR__ . '/tim/proses_tambah.php',
    '/tim/proses_edit.php'            => __DIR__ . '/tim/proses_edit.php',

    '/pertandingan/list.php'          => __DIR__ . '/pertandingan/list.php',
    '/pertandingan/tambah.php'        => __DIR__ . '/pertandingan/tambah.php',
    '/pertandingan/proses_tambah.php' => __DIR__ . '/pertandingan/proses_tambah.php',
    '/pertandingan/proses_hapus.php'  => __DIR__ . '/pertandingan/proses_hapus.php',
    '/pertandingan/proses_skor.php'   => __DIR__ . '/pertandingan/proses_skor.php',
];

$__target = $__routes[$__path] ?? null;

if ($__target === null) {
    http_response_code(404);
    $page_title = '404';
    $menu_aktif = '';
    include __DIR__ . '/includes/header.php';
    echo '<section><h2>404</h2><p>Halaman tidak ditemukan.</p></section>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

require $__target;
