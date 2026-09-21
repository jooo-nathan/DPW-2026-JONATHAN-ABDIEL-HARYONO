<?php
$page_title = "Home";
include __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/koneksi.php';

// Tiga angka untuk kartu statistik (SELECT + COUNT + ORDER BY + LIMIT, sama seperti biasa)
$totalPemain    = $pdo->query("SELECT COUNT(*) FROM pemain")->fetchColumn();
$sedangBerjalan = $pdo->query("SELECT COUNT(*) FROM pertandingan WHERE status = 'In-Progress'")->fetchColumn();
$juara          = $pdo->query("SELECT * FROM pemain ORDER BY mmr DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);

// 10 pemain dengan MMR tertinggi
$daftarPemain = $pdo->query("SELECT * FROM pemain ORDER BY mmr DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
?>
        <?php
        // Catatan soal pesan flash ("Selamat datang..." dst): di jobsheet lokal kamu,
        // pesan begini disimpan lewat $_SESSION lalu dibaca ulang di halaman tujuan.
        // Di Vercel itu TIDAK bisa diandalkan, karena tiap request (klik/submit form)
        // bisa "dilayani" oleh komputer server yang berbeda-beda (ciri khas serverless) —
        // sedangkan $_SESSION bawaan PHP disimpan sebagai file di satu komputer server
        // tertentu. Solusinya: pesannya dititipkan langsung di alamat URL
        // (?pesan=...&tipe=...) saat redirect, supaya sampai ke halaman tujuan
        // apa pun servernya. htmlspecialchars() di sini mencegah teks di URL
        // "disuntik" jadi kode HTML berbahaya.
        if (isset($_GET['pesan'])): ?>
            <p class="flash flash-<?php echo htmlspecialchars($_GET['tipe'] ?? 'sukses'); ?>"><?php echo htmlspecialchars($_GET['pesan']); ?></p>
        <?php endif; ?>

        <section class="hero">
            <p class="hero-kicker">Competitive Arena</p>
            <h2>Vanguard<span>Arena</span></h2>
            <p class="tagline">Forge Your Legacy, Dominate the Leaderboard.</p>
            <a class="btn btn-primary" href="/api/pertandingan/tambah.php">Create Match</a>
            <a class="btn btn-outline" href="/api/pertandingan/list.php">Enter Queue</a>
        </section>

        <div class="stats">
            <article>
                <h3>Registered Players</h3>
                <p><?php echo $totalPemain; ?></p>
            </article>
            <article>
                <h3>Live Matches</h3>
                <p class="angka-merah"><?php echo $sedangBerjalan; ?></p>
            </article>
            <article>
                <h3>#1 Player</h3>
                <p class="angka-kecil"><?php echo $juara ? htmlspecialchars($juara['username']) : '-'; ?></p>
            </article>
        </div>

        <section id="leaderboard">
            <h2>Global Leaderboard</h2>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Player</th>
                            <th>Game</th>
                            <th>MMR</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($daftarPemain)): ?>
                        <tr>
                            <td colspan="4">Belum ada pemain. Daftar lewat menu "Join Arena".</td>
                        </tr>
                        <?php else: ?>
                            <?php $no = 1; foreach ($daftarPemain as $pemain): ?>
                            <tr class="peringkat-<?php echo $no; ?>">
                                <td>
                                    <?php
                                    if ($no === 1) {
                                        echo '&#128081;';
                                    } elseif ($no === 2) {
                                        echo '&#129352;';
                                    } elseif ($no === 3) {
                                        echo '&#129353;';
                                    } else {
                                        echo $no;
                                    }
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($pemain['username']); ?></td>
                                <td><?php echo htmlspecialchars($pemain['game_utama']); ?></td>
                                <td><?php echo $pemain['mmr']; ?></td>
                            </tr>
                            <?php $no++; endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
<?php include __DIR__ . '/includes/footer.php'; ?>
