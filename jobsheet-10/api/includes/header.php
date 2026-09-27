<?php
require_once __DIR__ . '/games.php';

// Prefix relatif ke root proyek ini, supaya link CSS/JS/menu tetap benar
// di halaman yang berada di dalam subfolder (tim/, pertandingan/).
$__root = dirname(__DIR__);
$__scriptDir = dirname($_SERVER['SCRIPT_FILENAME']);
$__rel = ltrim(str_replace('\\', '/', substr($__scriptDir, strlen($__root))), '/');
$base = $__rel === '' ? '' : str_repeat('../', substr_count($__rel, '/') + 1);

// $menu_aktif diisi tiap halaman, dipakai untuk menandai menu yang sedang aktif
$menu_aktif = $menu_aktif ?? '';

// Status login (Jobsheet 10). session_status() diperiksa dulu supaya aman
// dipanggil berkali-kali -- auth.php di halaman terkunci sudah memanggil
// session_start() lebih dulu sebelum header.php ikut di-include.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$sudahLogin = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VanguardArena<?php echo isset($page_title) ? ' | ' . $page_title : ''; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600&family=Rajdhani:wght@600;700&display=swap">
    <link rel="stylesheet" href="<?php echo $base; ?>assets/css/style.css">
</head>
<body>
    <header>
        <a class="logo" href="<?php echo $base; ?>index.php">VANGUARD<span>ARENA</span></a>
        <button type="button" id="nav-toggle-btn" class="nav-toggle-label" aria-label="Menu">&#9776;</button>
        <nav>
            <ul>
                <li><a href="<?php echo $base; ?>index.php" <?php echo $menu_aktif === 'home' ? 'class="aktif"' : ''; ?>>Home</a></li>
                <li><a href="<?php echo $base; ?>pertandingan/list.php" <?php echo $menu_aktif === 'hub' ? 'class="aktif"' : ''; ?>>Matchmaking Hub</a></li>
                <li><a href="<?php echo $base; ?>tim/list.php" <?php echo $menu_aktif === 'kelola' ? 'class="aktif"' : ''; ?>>Kelola Tim</a></li>
                <li><a href="<?php echo $base; ?>index.php#leaderboard">Leaderboard</a></li>
                <?php if ($sudahLogin): ?>
                <li><a href="<?php echo $base; ?>pertandingan/tambah.php" <?php echo $menu_aktif === 'buat' ? 'class="aktif"' : ''; ?>>Create Match</a></li>
                <li><a href="<?php echo $base; ?>tim/tambah.php" <?php echo $menu_aktif === 'daftar' ? 'class="aktif"' : ''; ?>>Daftarkan Tim</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <div class="auth-status">
            <?php if ($sudahLogin): ?>
                <span><?php echo htmlspecialchars($_SESSION['nama']); ?></span>
                <a href="<?php echo $base; ?>auth/logout.php">Logout</a>
            <?php else: ?>
                <a href="<?php echo $base; ?>auth/login.php">Login</a>
            <?php endif; ?>
        </div>
    </header>

    <main>
