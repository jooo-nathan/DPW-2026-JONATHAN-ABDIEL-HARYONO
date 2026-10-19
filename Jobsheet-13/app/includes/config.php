<?php
// Jobsheet 13: kredensial database dipisah dari kode (koneksi.php).
// Nilai dibaca dari ENVIRONMENT VARIABLE yang diatur di server; kalau tidak
// ada, dipakai nilai cadangan (default) khusus komputer lokal.
// Jangan mengganti default di bawah dengan password asli lalu meng-commit-nya.
//
// Dukungan tambahan: integrasi Neon di Vercel otomatis membuat DATABASE_URL /
// POSTGRES_URL. Kalau DB_HOST tidak diisi, URL itu dipecah otomatis.
$url = [];
if (!getenv('DB_HOST')) {
    $dsn = getenv('DATABASE_URL') ?: getenv('POSTGRES_URL');
    if ($dsn) {
        $url = parse_url($dsn) ?: [];
    }
}

return [
    'db_host'    => getenv('DB_HOST') ?: ($url['host'] ?? 'localhost'),
    'db_port'    => getenv('DB_PORT') ?: (string) ($url['port'] ?? '5432'),
    'db_name'    => getenv('DB_NAME') ?: (isset($url['path']) ? ltrim($url['path'], '/') : 'vanguard_arena'),
    'db_user'    => getenv('DB_USER') ?: (isset($url['user']) ? urldecode($url['user']) : 'postgres'),
    'db_pass'    => getenv('DB_PASS') ?: (isset($url['pass']) ? urldecode($url['pass']) : 'postgres'),
    // 'prefer' cocok untuk lokal; database online (mis. Neon) butuh 'require'
    'db_sslmode' => getenv('DB_SSLMODE') ?: ($url ? 'require' : 'prefer'),
];
