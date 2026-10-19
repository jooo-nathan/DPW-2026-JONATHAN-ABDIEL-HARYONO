<?php
$page_title = "Home";
$menu_aktif = 'home';
include __DIR__ . '/includes/header.php';

// Semua data masih di $_SESSION (sementara, hilang saat sesi berakhir)
$daftarTim = $_SESSION['tim'] ?? [];
$daftarMatch = $_SESSION['pertandingan'] ?? [];

$totalTim = count($daftarTim);
$live = array_filter($daftarMatch, function ($m) { return $m['status'] === 'In-Progress'; });
$waiting = array_filter($daftarMatch, function ($m) { return $m['status'] === 'Waiting'; });

// Leaderboard: urutkan tim dari MMR tertinggi (usort = urut dengan fungsi pembanding)
usort($daftarTim, function ($a, $b) { return $b['mmr'] <=> $a['mmr']; });
$top = array_slice($daftarTim, 0, 5);
?>
        <header id="hero">
            <h2>Selamat Datang di Vanguard Arena</h2>
            <p>Platform matchmaking esports berbasis tim. Daftarkan tim, buat pertandingan, dan rebut puncak Global Leaderboard.</p>
            <p>
                <a href="<?php echo $base; ?>pertandingan/tambah.php" class="btn">Create Match</a>
                <a href="<?php echo $base; ?>tim/tambah.php" class="btn sekunder">Daftarkan Tim</a>
            </p>
            <div id="live-now">
                <h3>Sedang berlangsung</h3>
                <?php if (empty($live)): ?>
                    <p>Belum ada match yang berjalan.</p>
                <?php else: ?>
                    <ul>
                        <?php foreach (array_slice($live, 0, 3) as $m): ?>
                        <li><span><?php echo $m['game']; ?></span> <strong><?php echo $m['tim1']; ?> vs <?php echo $m['tim2']; ?></strong></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </header>

        <section id="ringkasan">
            <h2>Ringkasan</h2>
            <article>
                <h3>Tim Terdaftar</h3>
                <p><?php echo $totalTim; ?></p>
            </article>
            <article>
                <h3>Match Live</h3>
                <p><?php echo count($live); ?></p>
            </article>
            <article>
                <h3>Match Waiting</h3>
                <p><?php echo count($waiting); ?></p>
            </article>
            <article>
                <h3>Peringkat 1</h3>
                <p><?php echo $top ? $top[0]['nickname'] : '-'; ?></p>
            </article>
        </section>

        <section id="leaderboard">
            <h2>Global Leaderboard</h2>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Nickname</th>
                            <th>Nama Tim</th>
                            <th>Game</th>
                            <th>MMR</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($top)): ?>
                        <tr>
                            <td colspan="5">Belum ada tim. Daftarkan lewat menu "Daftarkan Tim".</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($top as $i => $t): ?>
                            <tr>
                                <td><?php echo $i + 1; ?></td>
                                <td><?php echo $t['nickname']; ?></td>
                                <td><?php echo $t['nama_tim']; ?></td>
                                <td><?php echo $t['game']; ?></td>
                                <td><?php echo $t['mmr']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
<?php include __DIR__ . '/includes/footer.php'; ?>
