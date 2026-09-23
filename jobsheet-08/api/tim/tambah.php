<?php
$page_title = "Daftarkan Tim";
$menu_aktif = 'daftar';
include __DIR__ . '/../includes/header.php';
?>
        <section>
            <h2>Daftarkan Tim</h2>

            <?php if (isset($_GET['pesan'])): ?>
                <p class="flash flash-<?php echo htmlspecialchars($_GET['tipe'] ?? 'sukses'); ?>"><?php echo htmlspecialchars($_GET['pesan']); ?></p>
            <?php endif; ?>

            <form method="post" action="proses_tambah.php">
                <p>
                    <label for="nama_tim">Nama Tim (3-30 karakter)</label>
                    <input type="text" id="nama_tim" name="nama_tim" maxlength="30" required>
                </p>
                <p>
                    <label for="game">Game yang Dimainkan</label>
                    <select id="game" name="game" required>
                        <option value="">Pilih game...</option>
                        <?php foreach (DAFTAR_GAME as $g): ?>
                            <option><?php echo htmlspecialchars($g); ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <p class="catatan">Tim baru mulai dengan 1000 MMR.</p>
                <p><button type="submit" class="btn btn-primary">Register</button></p>
            </form>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
