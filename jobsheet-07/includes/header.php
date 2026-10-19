<?php
session_start();

// Prefix relatif ke root proyek ini (bukan root domain), dihitung dari
// kedalaman folder halaman yang sedang dibuka: "" di root, "../" di
// tim/ atau pertandingan/. Jadi proyek jalan benar lewat `php -S` maupun
// lewat subfolder (mis. Laragon).
$__jobsheetRoot = dirname(__DIR__);
$__scriptDir = dirname($_SERVER['SCRIPT_FILENAME']);
$__rel = ltrim(str_replace('\\', '/', substr($__scriptDir, strlen($__jobsheetRoot))), '/');
$base = $__rel === '' ? '' : str_repeat('../', substr_count($__rel, '/') + 1);

// $menu_aktif diisi tiap halaman -> menandai menu yang sedang dibuka
$menu_aktif = $menu_aktif ?? '';
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
    <title>Vanguard Arena<?php echo isset($page_title) ? ' | ' . $page_title : ''; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&amp;family=Oxanium:wght@500;600;700&amp;display=swap">
    <link rel="stylesheet" href="<?php echo $base; ?>assets/css/style.css">
</head>
<body>
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
                        <li><a href="<?php echo $base; ?>pertandingan/tambah.php"<?php echo menu_cur('buat', $menu_aktif); ?>>Create Match</a></li>
                    </ul>
                </li>
                <li>
                    <strong>Tim</strong>
                    <ul>
                        <li><a href="<?php echo $base; ?>tim/list.php"<?php echo menu_cur('kelola', $menu_aktif); ?>>Kelola Tim</a></li>
                        <li><a href="<?php echo $base; ?>tim/tambah.php"<?php echo menu_cur('daftar', $menu_aktif); ?>>Daftarkan Tim</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </aside>

    <main>
