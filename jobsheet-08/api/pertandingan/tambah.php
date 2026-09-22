<?php
$page_title = "Create Match";
$menu_aktif = 'buat';
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

// Daftar nama tim untuk dropdown
$daftarTim = $pdo->query("SELECT nama_tim, game FROM tim ORDER BY nama_tim")->fetchAll(PDO::FETCH_ASSOC);
?>
        <section>
            <h2>Create Match</h2>

            <?php if (isset($_GET['pesan'])): ?>
                <p class="flash flash-<?php echo htmlspecialchars($_GET['tipe'] ?? 'sukses'); ?>"><?php echo htmlspecialchars($_GET['pesan']); ?></p>
            <?php endif; ?>

            <?php if (empty($daftarTim)): ?>
                <p class="catatan">Belum ada tim terdaftar. <a href="../tim/tambah.php">Daftarkan tim</a> dulu sebelum membuat match.</p>
            <?php else: ?>
                <form method="post" action="proses_tambah.php">
                    <p>
                        <label for="game">Game</label>
                        <select id="game" name="game" required>
                            <option value="">Pilih game...</option>
                            <?php foreach (DAFTAR_GAME as $g): ?>
                                <option><?php echo htmlspecialchars($g); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </p>
                    <p>
                        <label for="tim1">Team 1</label>
                        <select id="tim1" name="tim1" required>
                            <option value="">Pilih tim...</option>
                            <?php foreach ($daftarTim as $t): ?>
                                <option><?php echo htmlspecialchars($t['nama_tim']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </p>
                    <p>
                        <label for="tim2">Team 2 (lawan)</label>
                        <select id="tim2" name="tim2">
                            <option value="">Menunggu lawan</option>
                            <?php foreach ($daftarTim as $t): ?>
                                <option><?php echo htmlspecialchars($t['nama_tim']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </p>
                    <p class="catatan">Tanpa lawan, status match = Waiting. Kalau lawan dipilih, status = In-Progress.</p>
                    <p><button type="submit" class="btn btn-primary">Create Match</button></p>
                </form>
            <?php endif; ?>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
