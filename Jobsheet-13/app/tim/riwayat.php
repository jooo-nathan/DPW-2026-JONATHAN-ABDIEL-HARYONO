<?php
require __DIR__ . '/../includes/auth.php';
$page_title = "Riwayat MMR";
$menu_aktif = 'riwayat';
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$timId = (int) ($_GET['tim_id'] ?? 0);
$daftarTim = $pdo->query("SELECT id, nickname, nama_tim, game FROM tim ORDER BY game, nickname")->fetchAll(PDO::FETCH_ASSOC);

$riwayat = [];
$timTerpilih = null;

if ($timId > 0) {
    $stmtT = $pdo->prepare("SELECT * FROM tim WHERE id = :id");
    $stmtT->execute(['id' => $timId]);
    $timTerpilih = $stmtT->fetch(PDO::FETCH_ASSOC);

    if ($timTerpilih) {
        // JOIN riwayat_mmr + pertandingan + (lawan lewat nickname & game yang sama)
        $stmt = $pdo->prepare(
            "SELECT r.dicatat_pada, r.hasil, r.perubahan, r.mmr_sesudah,
                    p.skor1, p.skor2, p.tim1, p.tim2
             FROM riwayat_mmr r
             JOIN pertandingan p ON p.id = r.pertandingan_id
             WHERE r.tim_id = :id
             ORDER BY r.dicatat_pada DESC, r.id DESC"
        );
        $stmt->execute(['id' => $timId]);
        $riwayat = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
        <section>
            <h2>Riwayat MMR</h2>

            <form method="get" action="riwayat.php" class="search-box">
                <span>
                    <label for="tim_id">Pilih Tim</label><br>
                    <select id="tim_id" name="tim_id">
                        <option value="">-- Pilih Tim --</option>
                        <?php foreach ($daftarTim as $t): ?>
                        <option value="<?php echo (int) $t['id']; ?>" <?php echo $timId === (int) $t['id'] ? 'selected' : ''; ?>>
                            <?php echo e($t['nickname'] . ' — ' . $t['nama_tim'] . ' (' . $t['game'] . ')'); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </span>
                <button type="submit" class="btn btn-primary">Tampilkan</button>
            </form>

            <?php if ($timTerpilih): ?>
            <h3>Riwayat &mdash; <?php echo e($timTerpilih['nickname']); ?> (MMR sekarang: <?php echo (int) $timTerpilih['mmr']; ?>)</h3>
            <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Match</th>
                        <th>Skor</th>
                        <th>Hasil</th>
                        <th>Perubahan</th>
                        <th>MMR Sesudah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($riwayat)): ?>
                    <tr>
                        <td colspan="6">Belum ada pertandingan selesai untuk tim ini.</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($riwayat as $r): ?>
                        <tr>
                            <td><?php echo e($r['dicatat_pada']); ?></td>
                            <td><?php echo e($r['tim1'] . ' vs ' . $r['tim2']); ?></td>
                            <td><?php echo (int) $r['skor1'] . ' - ' . (int) $r['skor2']; ?></td>
                            <td><?php echo e($r['hasil']); ?></td>
                            <td><?php echo ((int) $r['perubahan'] > 0 ? '+' : '') . (int) $r['perubahan']; ?></td>
                            <td><?php echo (int) $r['mmr_sesudah']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>
            <?php endif; ?>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
