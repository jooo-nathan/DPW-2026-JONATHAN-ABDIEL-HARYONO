<?php
// Koneksi PDO ke PostgreSQL. Aman di-require berkali-kali: koneksi dibuat
// sekali per request, lalu dipakai ulang (juga oleh penyimpanan session).
if (!function_exists('db_connect')) {
    function db_connect()
    {
        static $pdo = null;
        if ($pdo !== null) {
            return $pdo;
        }

        // require MENGEMBALIKAN nilai: config.php berisi "return [...]"
        $config = require __DIR__ . '/config.php';

        // Khusus Neon: library PostgreSQL yang dipakai Vercel belum mengirim
        // nama "endpoint" lewat SNI secara otomatis, jadi dikirim lewat
        // parameter "options". Endpoint ID = bagian pertama host tanpa "-pooler".
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
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Detail error (host, user) hanya ditulis ke log, tidak ditampilkan ke pengunjung.
            error_log('[Vanguard Arena] Koneksi database gagal: ' . $e->getMessage());
            http_response_code(500);
            die('Koneksi database gagal.' . (getenv('APP_DEBUG') ? ' ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') : ''));
        }
        return $pdo;
    }
}

$pdo = db_connect();
