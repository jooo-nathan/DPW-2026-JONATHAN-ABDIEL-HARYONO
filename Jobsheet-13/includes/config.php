<?php
// Jobsheet 13: kredensial database dipisah dari kode (koneksi.php).
// Nilai dibaca dari ENVIRONMENT VARIABLE yang diatur di server; kalau tidak
// ada, dipakai nilai cadangan (default) khusus komputer lokal.
// Jangan mengganti default di bawah dengan password asli lalu meng-commit-nya.
//
// Operator ?: (Elvis) -> pakai getenv() bila terisi, kalau tidak pakai nilai kanan.
// getenv() mengembalikan false bila variabelnya tidak ada.
return [
    'db_host'    => getenv('DB_HOST') ?: 'localhost',
    'db_port'    => getenv('DB_PORT') ?: '5432',
    'db_name'    => getenv('DB_NAME') ?: 'vanguard_arena',
    'db_user'    => getenv('DB_USER') ?: 'postgres',
    'db_pass'    => getenv('DB_PASS') ?: 'postgres',
    // 'prefer' cocok untuk lokal; database online (mis. Neon) butuh 'require'
    'db_sslmode' => getenv('DB_SSLMODE') ?: 'prefer',
];
