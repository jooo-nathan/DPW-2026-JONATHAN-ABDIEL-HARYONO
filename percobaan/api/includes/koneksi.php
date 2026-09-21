<?php
// ===== Bagian 1: baca alamat database dari Environment Variable =====
// Sama seperti koneksi.php biasa (host, port, dbname, user, pass) — bedanya
// di Vercel kelima info itu tidak ditulis manual di kode, tapi digabung jadi
// satu "alamat lengkap" bernama DATABASE_URL yang disimpan di Vercel
// (Settings > Environment Variables), supaya password tidak ikut ter-upload
// ke GitHub. parse_url() memecah alamat itu jadi 5 bagian yang sama seperti
// biasa kamu tulis manual.
$dbUrl = getenv('DATABASE_URL');
if (!$dbUrl) {
    die("DATABASE_URL belum diatur di Vercel > Settings > Environment Variables.");
}

$url  = parse_url($dbUrl);
$host = $url['host'];
$port = $url['port'] ?? "5432";
$db   = ltrim($url['path'], '/');   // path-nya "/namadb", buang garis miring depannya
$user = urldecode($url['user']);
$pass = urldecode($url['pass']);

// ===== Bagian 2: khusus penyedia Neon (boleh dilewati, tidak perlu dihafal) =====
// Neon punya banyak "server" di balik satu alamat yang sama, jadi dia perlu
// tahu server yang mana persis yang kamu maksud. Cara memberitahunya adalah
// lewat parameter "options=endpoint=..." di bawah ini. Ini murni kebutuhan
// teknis dari Neon, bukan sesuatu yang perlu kamu pahami detailnya —
// anggap saja baris ini seperti nomor rumah tambahan di alamat surat.
$opsiTambahan = "";
if (strpos($host, "neon.tech") !== false) {
    $namaServer = str_replace("-pooler", "", explode(".", $host)[0]);
    $opsiTambahan = ";options='endpoint=$namaServer'";
}

// ===== Bagian 3: koneksi PDO — persis seperti yang sudah kamu pelajari =====
try {
    // sslmode=require: database online (bukan di komputer sendiri) mewajibkan
    // koneksi terenkripsi, beda dari koneksi ke database lokal yang tidak perlu ini.
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db;sslmode=require$opsiTambahan", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal (host: $host): " . $e->getMessage());
}
