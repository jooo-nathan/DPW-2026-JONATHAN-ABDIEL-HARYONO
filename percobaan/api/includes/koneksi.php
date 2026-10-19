<?php
// Di Vercel, alamat database disimpan sebagai Environment Variable (DATABASE_URL),
// jadi kita pakai file koneksi khusus. Di komputer sendiri bagian ini dilewati.
if (getenv('DATABASE_URL')) {
    require __DIR__ . '/koneksi_vercel.php';
    return;
}

// ===== Koneksi lokal (Laragon) =====
$host = "localhost";
$port = "5432";
$db   = "simpus_mini";
$user = "postgres";
$pass = "postgres";

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
