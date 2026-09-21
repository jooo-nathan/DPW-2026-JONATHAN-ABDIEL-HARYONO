<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VanguardArena<?php echo isset($page_title) ? ' | ' . $page_title : ''; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600&family=Rajdhani:wght@600;700&display=swap">
    <!--
        Path CSS/JS & menu di bawah semua diawali garis miring ("/assets/...",
        "/api/...") alias "path absolut". Ini selalu benar dari halaman mana pun,
        karena situs Vercel-mu selalu hidup persis di root domain
        (namasitus.vercel.app/...) — beda dari server lokal biasa yang kadang
        proyeknya ada di dalam subfolder, sehingga di jobsheet kamu perlu
        dihitung dulu ($base). Di sini perhitungan itu tidak perlu sama sekali.
    -->
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <header>
        <h1>VANGUARD<span>ARENA</span></h1>
        <button type="button" id="nav-toggle-btn" class="nav-toggle-label" aria-label="Menu">&#9776;</button>
        <nav>
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="/api/pertandingan/list.php">Matchmaking Hub</a></li>
                <li><a href="/api/pertandingan/tambah.php">Create Match</a></li>
                <li><a href="/#leaderboard">Leaderboard</a></li>
                <li><a href="/api/pemain/tambah.php">Join Arena</a></li>
            </ul>
        </nav>
    </header>

    <main>
