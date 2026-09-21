<?php
$page_title = "Home";
include __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/koneksi.php';

// Tiga angka untuk kartu statistik
$totalPemain    = $pdo->query("SELECT COUNT(*) FROM pemain")->fetchColumn();
$sedangBerjalan = $pdo->query("SELECT COUNT(*) FROM pertandingan WHERE status = 'In-Progress'")->fetchColumn();
$juara          = $pdo->query("SELECT * FROM pemain ORDER BY mmr DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);

// 10 pemain dengan MMR tertinggi
$daftarPemain = $pdo->query("SELECT * FROM pemain ORDER BY mmr DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
?>
        <?php if (isset($_GET['pesan'])): ?>
            <p class="flash flash-<?php echo htmlspecialchars($_GET['tipe'] ?? 'sukses'); ?>"><?php echo htmlspecialchars($_GET['pesan']); ?></p>
        <?php endif; ?>

        <section class="hero">
            <p class="hero-kicker">Competitive Arena</p>
            <h2>Vanguard<span>Arena</span></h2>
            <p class="tagline">Forge Your Legacy, Dominate the Leaderboard.</p>
            <a class="btn btn-primary" href="<?php echo $base; ?>pertandingan/tambah.php">Create Match</a>
            <a class="btn btn-outline" href="<?php echo $base; ?>pertandingan/list.php">Enter Queue</a>
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
                                    // Ikon khusus untuk peringkat 1, 2, dan 3
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
