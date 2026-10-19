<?php
require __DIR__ . '/../includes/auth.php';
$page_title = "Create Match";
$menu_aktif = 'buat';
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';
require __DIR__ . '/../includes/games.php';

// Alur 2 langkah tanpa JavaScript:
// 1) Pilih game dulu (lewat method="get", supaya jadi ?game=... di URL).
// 2) Setelah game dipilih, dropdown Team 1/Team 2 HANYA menampilkan tim
//    yang bermain di game itu -- jadi dua tim yang berbeda game memang
//    tidak akan pernah bisa dipilih sekaligus (bukan cuma divalidasi belakangan).
$gameDipilih = trim($_GET['game'] ?? '');
$gameValid   = in_array($gameDipilih, DAFTAR_GAME, true);

if ($gameValid) {
    $stmt = $pdo->prepare("SELECT nickname, nama_tim FROM tim WHERE game = :game ORDER BY nickname");
    $stmt->execute(['game' => $gameDipilih]);
    $daftarTim = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
        <section>
            <h2>Create Match</h2>

            <?php if (isset($_GET['pesan'])): ?>
                <p class="flash flash-<?php echo e($_GET['tipe'] ?? 'sukses'); ?>"><?php echo e($_GET['pesan']); ?></p>
            <?php endif; ?>

            <?php if (!$gameValid): ?>
                <p class="catatan">Pilih game dulu, supaya cuma tim yang main game itu yang muncul untuk dipilih.</p>
                <form method="get" action="tambah.php">
                    <p>
                        <label for="game">Game</label>
                        <select id="game" name="game" required>
                            <option value="">Pilih game...</option>
                            <?php foreach (DAFTAR_GAME as $g): ?>
                                <option><?php echo e($g); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </p>
                    <p><button type="submit" class="btn btn-primary">Lanjut</button></p>
                </form>
            <?php elseif (empty($daftarTim)): ?>
                <p class="catatan">Belum ada tim yang terdaftar untuk <strong><?php echo e($gameDipilih); ?></strong>. <a href="../tim/tambah.php">Daftarkan tim</a> dulu, atau <a href="tambah.php">pilih game lain</a>.</p>
            <?php else: ?>
                <p class="catatan">Game: <strong><?php echo e($gameDipilih); ?></strong> &middot; <a href="tambah.php">Ganti game</a></p>
                <form method="post" action="proses_tambah.php">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="game" value="<?php echo e($gameDipilih); ?>">
                    <p>
                        <label for="tim1">Team 1</label>
                        <select id="tim1" name="tim1" required>
                            <option value="">Pilih tim...</option>
                            <?php foreach ($daftarTim as $t): ?>
                                <option value="<?php echo e($t['nickname']); ?>"><?php echo e($t['nickname'] . ' — ' . $t['nama_tim']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </p>
                    <p>
                        <label for="tim2">Team 2 (lawan)</label>
                        <select id="tim2" name="tim2">
                            <option value="">Menunggu lawan</option>
                            <?php foreach ($daftarTim as $t): ?>
                                <option value="<?php echo e($t['nickname']); ?>"><?php echo e($t['nickname'] . ' — ' . $t['nama_tim']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </p>
                    <p class="catatan">Tanpa lawan, status match = Waiting. Kalau lawan dipilih, status = In-Progress.</p>
                    <p><button type="submit" class="btn btn-primary">Create Match</button></p>
                </form>
            <?php endif; ?>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
