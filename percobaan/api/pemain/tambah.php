<?php
$page_title = "Join Arena";
$menu_aktif = 'daftar';
include __DIR__ . '/../includes/header.php';
?>
        <section class="panel">
            <h2>Join Arena</h2>

            <?php tampil_flash(); ?>

            <form id="form-tambah" method="post" action="proses_tambah.php">
                <p>
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" maxlength="20" required>
                    <span class="petunjuk">3-20 karakter: huruf, angka, atau garis bawah (_).</span>
                </p>
                <p>
                    <label for="game_utama">Game Utama</label>
                    <select id="game_utama" name="game_utama" required>
                        <option value="">Pilih game...</option>
                        <?php foreach (DAFTAR_GAME as $game): ?>
                            <option value="<?php echo e($game); ?>"><?php echo e($game); ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <p class="muted">Setiap pemain baru mulai dengan 1000 MMR (tier Silver).</p>
                <p><button type="submit" class="btn btn-primary">Register</button></p>
            </form>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
