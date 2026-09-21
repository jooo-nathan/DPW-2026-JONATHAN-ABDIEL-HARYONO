<?php
$page_title = "Home";
$menu_aktif = 'home';
include __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/koneksi.php';

// Statistik ringkas
$totalPemain    = $pdo->query("SELECT COUNT(*) FROM pemain")->fetchColumn();
$sedangBerjalan = $pdo->query("SELECT COUNT(*) FROM pertandingan WHERE status = 'In-Progress'")->fetchColumn();
$juara          = $pdo->query("SELECT username, mmr FROM pemain ORDER BY mmr DESC, username ASC LIMIT 1")->fetch(PDO::FETCH_ASSOC);

// Lobi yang belum selesai + 5 pemain teratas
$lobiAktif = $pdo->query(
    "SELECT * FROM pertandingan WHERE status <> 'Completed' ORDER BY id DESC LIMIT 6"
)->fetchAll(PDO::FETCH_ASSOC);
$topPemain = $pdo->query(
    "SELECT username, game_utama, mmr FROM pemain ORDER BY mmr DESC, username ASC LIMIT 5"
)->fetchAll(PDO::FETCH_ASSOC);
?>
        <?php tampil_flash(); ?>

        <section class="hero">
            <p class="hero-kicker">Competitive Arena</p>
            <h2>Vanguard<span>Arena</span></h2>
            <p class="tagline">Forge Your Legacy, Dominate the Leaderboard.</p>
            <div class="hero-aksi">
                <a class="btn btn-primary btn-besar" href="<?php echo $base; ?>pertandingan/tambah.php">Create Match</a>
                <a class="btn btn-ghost btn-besar" href="<?php echo $base; ?>pertandingan/list.php">Enter Queue</a>
            </div>
        </section>

        <div class="stats">
            <article class="stat">
                <h3>Registered Players</h3>
                <p class="angka"><?php echo (int) $totalPemain; ?></p>
            </article>
            <article class="stat">
                <h3>Live Matches</h3>
                <p class="angka angka-live"><?php echo (int) $sedangBerjalan; ?></p>
            </article>
            <article class="stat">
                <h3>#1 Player</h3>
                <?php if ($juara): ?>
                    <p class="angka angka-nama">&#128081; <?php echo e($juara['username']); ?></p>
                    <p class="stat-kecil"><?php echo (int) $juara['mmr']; ?> MMR</p>
                <?php else: ?>
                    <p class="angka">-</p>
                <?php endif; ?>
            </article>
        </div>

        <section class="panel">
            <div class="panel-kepala">
                <h2>Active Lobbies</h2>
                <a href="<?php echo $base; ?>pertandingan/list.php">Lihat semua lobi &rarr;</a>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Game</th>
                            <th>Match</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($lobiAktif)): ?>
                            <tr><td colspan="3">Belum ada lobi aktif. Klik "Create Match" untuk memulai.</td></tr>
                        <?php else: ?>
                            <?php foreach ($lobiAktif as $m): ?>
                                <tr>
                                    <td><?php echo e($m['game']); ?></td>
                                    <td>
                                        <?php echo e($m['pemain1']); ?> <span class="vs">vs</span>
                                        <?php echo $m['pemain2'] ? e($m['pemain2']) : '<em class="muted">menunggu lawan</em>'; ?>
                                    </td>
                                    <td><?php echo badge_status($m['status']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <?php include __DIR__ . '/includes/form_skor.php'; ?>

        <section class="panel">
            <div class="panel-kepala">
                <h2>Global Leaderboard</h2>
                <a href="<?php echo $base; ?>leaderboard.php">Lihat leaderboard lengkap &rarr;</a>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Player</th>
                            <th>Game</th>
                            <th>Tier</th>
                            <th>MMR</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($topPemain)): ?>
                            <tr><td colspan="5">Belum ada pemain. Daftar lewat menu "Join Arena".</td></tr>
                        <?php else: ?>
                            <?php $no = 1; foreach ($topPemain as $p): ?>
                                <tr class="baris-<?php echo $no <= 3 ? $no : 'biasa'; ?>">
                                    <td><?php echo badge_rank($no); ?></td>
                                    <td class="nama"><?php echo e($p['username']); ?></td>
                                    <td><?php echo e($p['game_utama']); ?></td>
                                    <td><?php echo badge_tier($p['mmr']); ?></td>
                                    <td class="angka-tabel"><?php echo (int) $p['mmr']; ?></td>
                                </tr>
                            <?php $no++; endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
<?php include __DIR__ . '/includes/footer.php'; ?>
