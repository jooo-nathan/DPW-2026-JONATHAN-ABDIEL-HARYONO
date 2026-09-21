<?php
$page_title = "Lobbies";
$menu_aktif = 'lobi';
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$daftarMatch  = $pdo->query("SELECT * FROM pertandingan ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$daftarPemain = $pdo->query("SELECT username FROM pemain ORDER BY username")->fetchAll(PDO::FETCH_COLUMN);
?>
        <section class="panel">
            <div class="panel-kepala">
                <h2>Matchmaking Lobbies</h2>
                <a class="btn btn-primary btn-kecil" href="tambah.php">Create Match</a>
            </div>

            <?php tampil_flash(); ?>

            <div class="search-box">
                <label for="search-input">Cari Match</label>
                <input type="text" id="search-input" placeholder="Ketik game, username, atau status...">
            </div>

            <div class="table-responsive">
                <table class="filterable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Game</th>
                            <th>Match</th>
                            <th>Score</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($daftarMatch)): ?>
                            <tr><td colspan="6">Belum ada pertandingan. Klik "Create Match" untuk membuat yang pertama.</td></tr>
                        <?php else: ?>
                            <?php foreach ($daftarMatch as $m): ?>
                                <tr>
                                    <td><?php echo (int) $m['id']; ?></td>
                                    <td><?php echo e($m['game']); ?></td>
                                    <td>
                                        <?php echo e($m['pemain1']); ?> <span class="vs">vs</span>
                                        <?php echo $m['pemain2'] ? e($m['pemain2']) : '<em class="muted">menunggu lawan</em>'; ?>
                                    </td>
                                    <td class="angka-tabel">
                                        <?php echo $m['status'] === 'Completed' ? (int) $m['skor1'] . ' - ' . (int) $m['skor2'] : '-'; ?>
                                    </td>
                                    <td><?php echo badge_status($m['status']); ?></td>
                                    <td>
                                        <?php if ($m['status'] === 'Waiting'): ?>
                                            <form class="form-join" method="post" action="proses_join.php">
                                                <input type="hidden" name="id" value="<?php echo (int) $m['id']; ?>">
                                                <select name="pemain2" required aria-label="Pilih lawan">
                                                    <option value="">Pilih lawan...</option>
                                                    <?php foreach ($daftarPemain as $u): ?>
                                                        <?php if ($u === $m['pemain1']) continue; ?>
                                                        <option value="<?php echo e($u); ?>"><?php echo e($u); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <button type="submit" class="btn btn-ghost btn-kecil">Join</button>
                                            </form>
                                        <?php else: ?>
                                            <span class="muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <?php include __DIR__ . '/../includes/form_skor.php'; ?>
<?php include __DIR__ . '/../includes/footer.php'; ?>
