<?php
// require MENGEMBALIKAN nilai: file config.php berisi "return [...]",
// jadi array-nya langsung masuk ke variabel $config.
$config = require __DIR__ . '/config.php';

try {
    $pdo = new PDO(
        "pgsql:host={$config['db_host']};port={$config['db_port']};dbname={$config['db_name']};sslmode={$config['db_sslmode']}",
        $config['db_user'],
        $config['db_pass']
    );
    // Error SQL dilempar sebagai exception supaya bisa ditangkap try/catch
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
