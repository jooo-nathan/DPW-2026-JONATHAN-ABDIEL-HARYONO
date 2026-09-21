<?php
$page_title = "Create Match";
$menu_aktif = 'buat';
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$daftarPemain = $pdo->query("SELECT username FROM pemain ORDER BY username")->fetchAll(PDO::FETCH_COLUMN);
?>
        <section class="panel">
            <h2>Create Match</h2>

            <?php tampil_flash(); ?>

            <?php if (empty($daftarPemain)): ?>
                <p class="muted">Belum ada pemain terdaftar. <a href="../pemain/tambah.php">Join Arena</a> dulu.</p>
            <?php else: ?>
                <form id="form-tambah" method="post" action="proses_tambah.php">
                    <p>
                        <label for="game">Game</label>
                        <select id="game" name="game" required>
                            <option value="">Pilih game...</option>
                            <?php foreach (DAFTAR_GAME as $game): ?>
                                <option value="<?php echo e($game); ?>"><?php echo e($game); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </p>
                    <p>
                        <label for="pemain1">Player 1 (host)</label>
                        <select id="pemain1" name="pemain1" required>
                            <option value="">Pilih pemain...</option>
                            <?php foreach ($daftarPemain as $u): ?>
                                <option value="<?php echo e($u); ?>"><?php echo e($u); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </p>
                    <p>
                        <label for="pemain2">Player 2 (lawan)</label>
                        <select id="pemain2" name="pemain2">
                            <option value="">Menunggu lawan (Waiting)</option>
                            <?php foreach ($daftarPemain as $u): ?>
                                <option value="<?php echo e($u); ?>"><?php echo e($u); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="petunjuk">Kosongkan untuk membuka lobi. Kalau lawan dipilih, match langsung In-Progress.</span>
                    </p>
                    <p><button type="submit" class="btn btn-primary">Create Match</button></p>
                </form>
            <?php endif; ?>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
