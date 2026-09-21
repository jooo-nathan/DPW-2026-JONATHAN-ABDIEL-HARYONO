<?php
$page_title = "Matchmaking Hub";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

// Semua match, dan khusus yang sedang berjalan (untuk form Submit Score)
$daftarMatch = $pdo->query("SELECT * FROM pertandingan ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$berjalan    = $pdo->query("SELECT * FROM pertandingan WHERE status = 'In-Progress' ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
        <section>
            <h2>Active Lobbies</h2>

            <?php if (isset($_GET['pesan'])): ?>
                <p class="flash flash-<?php echo htmlspecialchars($_GET['tipe'] ?? 'sukses'); ?>"><?php echo htmlspecialchars($_GET['pesan']); ?></p>
            <?php endif; ?>

            <div class="search-box">
                <label for="search-input">Cari Match</label>
                <input type="text" id="search-input" placeholder="Ketik game, username, atau status...">
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Game</th>
                            <th>Player 1</th>
                            <th>Player 2</th>
                            <th>Score</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($daftarMatch)): ?>
                        <tr>
                            <td colspan="5">Belum ada match. Klik "Create Match" untuk membuat yang pertama.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($daftarMatch as $m): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($m['game']); ?></td>
                                <td><?php echo htmlspecialchars($m['pemain1']); ?></td>
                                <td><?php echo $m['pemain2'] ? htmlspecialchars($m['pemain2']) : '-'; ?></td>
                                <td><?php echo $m['status'] === 'Completed' ? $m['skor1'] . ' - ' . $m['skor2'] : '-'; ?></td>
                                <td><span class="badge status-<?php echo strtolower($m['status']); ?>"><?php echo $m['status']; ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section>
            <h2>Submit Score</h2>

            <?php if (empty($berjalan)): ?>
                <p class="catatan">Belum ada match berstatus In-Progress.</p>
            <?php else: ?>
                <form method="post" action="proses_skor.php">
                    <p>
                        <label for="pertandingan_id">Match</label>
                        <select id="pertandingan_id" name="pertandingan_id" required>
                            <?php foreach ($berjalan as $m): ?>
                                <option value="<?php echo $m['id']; ?>">
                                    <?php echo htmlspecialchars($m['game'] . ': ' . $m['pemain1'] . ' vs ' . $m['pemain2']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </p>
                    <p>
                        <label for="skor1">Skor Player 1</label>
                        <input type="number" id="skor1" name="skor1" min="0" required>
                    </p>
                    <p>
                        <label for="skor2">Skor Player 2</label>
                        <input type="number" id="skor2" name="skor2" min="0" required>
                    </p>
                    <p class="catatan">Pemenang +25 MMR, yang kalah -15 MMR. Skor tidak boleh seri.</p>
                    <p><button type="submit" class="btn btn-primary">Submit Score</button></p>
                </form>
            <?php endif; ?>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
