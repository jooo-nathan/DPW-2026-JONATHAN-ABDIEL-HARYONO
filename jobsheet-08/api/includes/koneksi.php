<?php
// Di Vercel: alamat database sudah tersimpan di Environment Variable bernama DATABASE_URL
// (bentuknya: postgres://user:password@host/nama_db). parse_url() memecahnya jadi bagian-bagian.
$dbUrl = getenv('DATABASE_URL');
// Ambil hanya bagian postgres://... (jaga-jaga kalau ikut tersalin kata "psql" atau tanda kutip)
if (!$dbUrl || !preg_match('~postgres(?:ql)?://[^\s\'"]+~', $dbUrl, $cocok)) {
    die("DATABASE_URL belum terbaca atau bentuknya salah. Harus berupa postgresql://user:password@host/nama_db");
}
$url  = parse_url($cocok[0]);
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
    die("Koneksi database gagal (host: $host): " . $e->getMessage());
}
