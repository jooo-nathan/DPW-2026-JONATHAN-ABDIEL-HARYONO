<?php
$page_title = "Join Arena";
include __DIR__ . '/../includes/header.php';
?>
        <section>
            <h2>Join Arena</h2>

            <?php if (isset($_GET['pesan'])): ?>
                <p class="flash flash-<?php echo htmlspecialchars($_GET['tipe'] ?? 'sukses'); ?>"><?php echo htmlspecialchars($_GET['pesan']); ?></p>
            <?php endif; ?>

            <form method="post" action="proses_tambah.php">
                <p>
                    <label for="username">Username (3-20 karakter)</label>
                    <input type="text" id="username" name="username" maxlength="20" required>
                </p>
                <p>
                    <label for="game_utama">Game Utama</label>
                    <select id="game_utama" name="game_utama" required>
                        <option value="">Pilih game...</option>
                        <option>Valorant</option>
                        <option>Mobile Legends</option>
                        <option>Dota 2</option>
                        <option>EA FC 25</option>
                    </select>
                </p>
                <p class="catatan">Pemain baru mulai dengan 1000 MMR.</p>
                <p><button type="submit" class="btn btn-primary">Register</button></p>
            </form>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
