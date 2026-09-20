<?php
// Di Vercel: alamat database sudah tersimpan di Environment Variable bernama DATABASE_URL
// (bentuknya: postgres://user:password@host/nama_db). parse_url() memecahnya jadi bagian-bagian.
$dbUrl = getenv('DATABASE_URL');
if (!$dbUrl) {
    die("DATABASE_URL belum terbaca. Cek Environment Variables di Vercel lalu redeploy.");
}
$url  = parse_url($dbUrl);
$host = $url['host'];
$port = $url['port'] ?? "5432";
$db   = ltrim($url['path'], '/');
$user = urldecode($url['user']);
$pass = urldecode($url['pass']);

// Kalau dijalankan lokal (Laragon), hapus blok di atas lalu pakai ini:
// $host = "localhost";
// $port = "5432";
// $db   = "simpus_mini";
// $user = "postgres";
// $pass = "postgres";
// (dan hapus juga ";sslmode=require" di DSN bawah)

try {
    // sslmode=require: database online mewajibkan koneksi terenkripsi
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db;sslmode=require", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
