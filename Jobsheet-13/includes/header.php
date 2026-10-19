<?php
require_once __DIR__ . '/games.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/csrf.php';

// Awalan path relatif ke root proyek (lihat base_path() di helpers.php)
$base = base_path();

// $menu_aktif diisi tiap halaman, dipakai untuk menandai menu yang sedang aktif
$menu_aktif = $menu_aktif ?? '';

// Status login (Jobsheet 10)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$sudahLogin = isset($_SESSION['user_id']);

// Penanda menu aktif -> atribut aria-current (dipakai CSS untuk menyorot menu)
function menu_cur($kunci, $aktif)
{
    return $kunci === $aktif ? ' aria-current="page"' : '';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vanguard Arena<?php echo isset($page_title) ? ' | ' . e($page_title) : ''; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&amp;family=Oxanium:wght@500;600;700&amp;display=swap">
    <link rel="stylesheet" href="<?php echo $base; ?>assets/css/style.css">
</head>
<body>
    <!-- SIDEBAR: logo, menu bergrup, dan status login di bagian bawah -->
    <aside>
        <header>
            <h1><a href="<?php echo $base; ?>index.php"><span>Vanguard</span> <span>Arena</span></a></h1>
            <p>Forge Your Legacy, Dominate the Leaderboard.</p>
        </header>
        <button type="button" id="nav-toggle-btn" class="nav-toggle-label" aria-label="Menu" aria-expanded="false">&#9776;</button>
        <nav aria-label="Menu utama">
            <ul>
                <li>
                    <strong>Arena</strong>
                    <ul>
                        <li><a href="<?php echo $base; ?>index.php"<?php echo menu_cur('home', $menu_aktif); ?>>Home</a></li>
                        <li><a href="<?php echo $base; ?>index.php#leaderboard">Leaderboard</a></li>
                        <li><a href="<?php echo $base; ?>pertandingan/list.php"<?php echo menu_cur('hub', $menu_aktif); ?>>Matchmaking Hub</a></li>
                        <?php if ($sudahLogin): ?>
                        <li><a href="<?php echo $base; ?>pertandingan/tambah.php"<?php echo menu_cur('buat', $menu_aktif); ?>>Create Match</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
                <li>
                    <strong>Tim</strong>
                    <ul>
                        <li><a href="<?php echo $base; ?>tim/list.php"<?php echo menu_cur('kelola', $menu_aktif); ?>>Kelola Tim</a></li>
                        <?php if ($sudahLogin): ?>
                        <li><a href="<?php echo $base; ?>tim/tambah.php"<?php echo menu_cur('daftar', $menu_aktif); ?>>Daftarkan Tim</a></li>
                        <li><a href="<?php echo $base; ?>tim/riwayat.php"<?php echo menu_cur('riwayat', $menu_aktif); ?>>Riwayat MMR</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
            </ul>
            <div class="auth-status">
                <?php if ($sudahLogin): ?>
                    <p>Masuk sebagai <strong><?php echo e($_SESSION['nama']); ?></strong></p>
                    <a href="<?php echo $base; ?>auth/logout.php" class="btn btn-outline btn-kecil">Logout</a>
                <?php else: ?>
                    <p>Anda melihat sebagai tamu.</p>
                    <a href="<?php echo $base; ?>auth/login.php" class="btn btn-primary btn-kecil">Login petugas</a>
                <?php endif; ?>
            </div>
        </nav>
    </aside>

    <main>
