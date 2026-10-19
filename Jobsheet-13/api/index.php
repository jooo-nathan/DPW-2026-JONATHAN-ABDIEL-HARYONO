<?php
// Front controller untuk Vercel: SEMUA request masuk lewat file ini
// (lihat "routes" di vercel.json), lalu diteruskan ke file di folder app/.
// Satu entry point = satu serverless function, dan file CSS/JS ikut
// disajikan dari sini, jadi tampilan tidak lagi "polos tanpa CSS".

$appDir = realpath(__DIR__ . '/../app');

// Path yang diminta (tanpa query string). Utamakan parameter __r dari vercel.json.
$route = $_GET['__r'] ?? null;
if ($route === null) {
    $route = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
}
unset($_GET['__r']);
$route = trim(rawurldecode((string) $route), '/');
if ($route === '') {
    $route = 'index.php';
}

// Header keamanan dasar
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');

// ---- 1) File statis: /assets/css/style.css, /assets/js/app.js ----
if (preg_match('#^assets/(css|js)/[A-Za-z0-9_.-]+\.(css|js)$#', $route, $m)) {
    $file = realpath($appDir . '/' . $route);
    if ($file && strpos($file, $appDir . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR) === 0) {
        $tipe = ['css' => 'text/css; charset=utf-8', 'js' => 'application/javascript; charset=utf-8'];
        header('Content-Type: ' . $tipe[$m[2]]);
        header('Cache-Control: public, max-age=3600');
        readfile($file);
        exit;
    }
}

// ---- 2) Halaman PHP: hanya yang ada di daftar putih ----
// Pola: index.php | reset.php | {auth|tim|pertandingan}/nama_file.php
// Folder includes/ sengaja TIDAK bisa diakses langsung dari URL.
if (preg_match('#^((auth|tim|pertandingan)/)?[a-z_]+\.php$#', $route)) {
    $file = realpath($appDir . '/' . $route);
    if ($file && strpos($file, $appDir . DIRECTORY_SEPARATOR) === 0 && is_file($file)) {
        // Dipakai base_path() untuk menghitung awalan path relatif (../)
        define('APP_ROUTE', $route);
        chdir(dirname($file));
        try {
            require $file;
        } catch (Throwable $e) {
            error_log('[Vanguard Arena] ' . $e);
            if (!headers_sent()) {
                http_response_code(500);
            }
            echo 'Terjadi kesalahan pada server.';
            if (getenv('APP_DEBUG')) {
                echo '<pre>' . htmlspecialchars((string) $e, ENT_QUOTES, 'UTF-8') . '</pre>';
            }
        }
        exit;
    }
}

// ---- 3) Tidak ditemukan ----
http_response_code(404);
header('Content-Type: text/html; charset=utf-8');
echo '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title>404</title></head><body style="font-family:sans-serif;padding:2rem">'
   . '<h1>404 &mdash; Halaman tidak ditemukan</h1><p><a href="/">Kembali ke Home</a></p></body></html>';
