<?php
require_once __DIR__ . '/helper.php';

// Prefix relatif ke root proyek ini, supaya link CSS/JS/menu tetap benar
// di halaman yang berada di dalam subfolder (pemain/, pertandingan/).
$__root = dirname(__DIR__);
$__scriptDir = dirname($_SERVER['SCRIPT_FILENAME']);
$__rel = ltrim(str_replace('\\', '/', substr($__scriptDir, strlen($__root))), '/');
$base = $__rel === '' ? '' : str_repeat('../', substr_count($__rel, '/') + 1);

$menu_aktif = $menu_aktif ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VanguardArena<?php echo isset($page_title) ? ' | ' . $page_title : ''; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600&family=Rajdhani:wght@500;600;700&display=swap">
    <link rel="stylesheet" href="<?php echo $base; ?>assets/css/style.css">
</head>
<body>
    <header>
        <a class="logo" href="<?php echo $base; ?>index.php">
            <span class="logo-mark"></span>VANGUARD<span class="logo-arena">ARENA</span>
        </a>
        <button type="button" id="nav-toggle-btn" class="nav-toggle-label" aria-label="Menu">&#9776;</button>
        <nav>
            <ul>
                <li><a href="<?php echo $base; ?>index.php" <?php echo $menu_aktif === 'home' ? 'class="aktif"' : ''; ?>>Home</a></li>
                <li><a href="<?php echo $base; ?>pertandingan/list.php" <?php echo $menu_aktif === 'lobi' ? 'class="aktif"' : ''; ?>>Lobbies</a></li>
                <li><a href="<?php echo $base; ?>pertandingan/tambah.php" <?php echo $menu_aktif === 'buat' ? 'class="aktif"' : ''; ?>>Create Match</a></li>
                <li><a href="<?php echo $base; ?>leaderboard.php" <?php echo $menu_aktif === 'leaderboard' ? 'class="aktif"' : ''; ?>>Leaderboard</a></li>
                <li><a href="<?php echo $base; ?>pemain/tambah.php" <?php echo $menu_aktif === 'daftar' ? 'class="aktif"' : ''; ?>>Join Arena</a></li>
            </ul>
        </nav>
    </header>

    <main>
