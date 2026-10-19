<?php
// require MENGEMBALIKAN nilai: file config.php berisi "return [...]",
// jadi array-nya langsung masuk ke variabel $config.
$config = require __DIR__ . '/config.php';

// Khusus Neon: library PostgreSQL yang dipakai Vercel belum mengirim nama
// "endpoint" lewat SNI secara otomatis, jadi Neon tidak tahu database mana
// yang dituju kalau tidak dibantu. Endpoint ID = bagian pertama host
// (tanpa akhiran "-pooler"), dikirim lewat parameter "options".
$opsi = '';
if (strpos($config['db_host'], 'neon.tech') !== false) {
    $endpoint = str_replace('-pooler', '', explode('.', $config['db_host'])[0]);
    $opsi = ";options='endpoint=$endpoint'";
}

try {
    $pdo = new PDO(
        "pgsql:host={$config['db_host']};port={$config['db_port']};dbname={$config['db_name']};sslmode={$config['db_sslmode']}{$opsi}",
        $config['db_user'],
        $config['db_pass']
    );
    // Error SQL dilempar sebagai exception supaya bisa ditangkap try/catch
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
