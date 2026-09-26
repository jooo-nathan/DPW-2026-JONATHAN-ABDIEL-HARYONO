<?php
// Menyajikan file CSS/JS dari folder api/assets/ lewat PHP,
// karena di Vercel file di dalam api/ tidak disajikan sebagai file statis.
$tipe = ['css' => 'text/css; charset=utf-8', 'js' => 'application/javascript; charset=utf-8'];

$root = realpath(__DIR__ . '/assets');
$file = realpath($root . '/' . ($_GET['f'] ?? ''));
$ext  = $file ? pathinfo($file, PATHINFO_EXTENSION) : '';

// Hanya boleh file css/js yang benar-benar ada di dalam api/assets/
if (!$file || strpos($file, $root . DIRECTORY_SEPARATOR) !== 0 || !isset($tipe[$ext])) {
    http_response_code(404);
    exit('File tidak ditemukan.');
}

header('Content-Type: ' . $tipe[$ext]);
header('Cache-Control: public, max-age=3600');
readfile($file);
