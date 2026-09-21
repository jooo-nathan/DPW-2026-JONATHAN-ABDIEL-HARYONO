<?php
$page_title = "Create Match";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

// Daftar username untuk dropdown
$daftarPemain = $pdo->query("SELECT username FROM pemain ORDER BY username")->fetchAll(PDO::FETCH_ASSOC);
?>
        <section>
            <h2>Create Match</h2>

            <?php if (isset($_GET['pesan'])): ?>
                <p class="flash flash-<?php echo htmlspecialchars($_GET['tipe'] ?? 'sukses'); ?>"><?php echo htmlspecialchars($_GET['pesan']); ?></p>
            <?php endif; ?>

            <form method="post" action="proses_tambah.php">
                <p>
                    <label for="game">Game</label>
                    <select id="game" name="game" required>
                        <option value="">Pilih game...</option>
                        <option>Valorant</option>
                        <option>Mobile Legends</option>
                        <option>Dota 2</option>
                        <option>EA FC 25</option>
                    </select>
                </p>
                <p>
                    <label for="pemain1">Player 1</label>
                    <select id="pemain1" name="pemain1" required>
                        <option value="">Pilih pemain...</option>
                        <?php foreach ($daftarPemain as $p): ?>
                            <option><?php echo htmlspecialchars($p['username']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <p>
                    <label for="pemain2">Player 2 (lawan)</label>
                    <select id="pemain2" name="pemain2">
                        <option value="">Menunggu lawan</option>
                        <?php foreach ($daftarPemain as $p): ?>
                            <option><?php echo htmlspecialchars($p['username']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <p class="catatan">Tanpa lawan, status match = Waiting. Kalau lawan dipilih, status = In-Progress.</p>
                <p><button type="submit" class="btn btn-primary">Create Match</button></p>
            </form>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
