<?php
$page_title = "Leaderboard";
$menu_aktif = 'leaderboard';
include __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/koneksi.php';

$daftarPemain = $pdo->query(
    "SELECT username, game_utama, mmr FROM pemain ORDER BY mmr DESC, username ASC LIMIT 50"
)->fetchAll(PDO::FETCH_ASSOC);
?>
        <section class="panel">
            <h2>Global Leaderboard</h2>

            <?php tampil_flash(); ?>

            <div class="search-box">
                <label for="search-input">Cari Player</label>
                <input type="text" id="search-input" placeholder="Ketik username...">
            </div>

            <div class="table-responsive">
                <table class="filterable">
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
                        <?php if (empty($daftarPemain)): ?>
                            <tr><td colspan="5">Belum ada pemain. Daftar lewat menu "Join Arena".</td></tr>
                        <?php else: ?>
                            <?php $no = 1; foreach ($daftarPemain as $p): ?>
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
