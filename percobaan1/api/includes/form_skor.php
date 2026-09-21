<?php
// Bagian halaman: form Submit Score. Membutuhkan $pdo dan $base dari halaman yang memanggilnya.
$berjalan = $pdo->query(
    "SELECT id, game, pemain1, pemain2 FROM pertandingan WHERE status = 'In-Progress' ORDER BY id DESC"
)->fetchAll(PDO::FETCH_ASSOC);
?>
        <section class="panel">
            <h2>Submit Score</h2>

            <?php if (empty($berjalan)): ?>
                <p class="muted">Belum ada pertandingan berstatus In-Progress. Buat match dan pilih lawan dulu, atau join lobi yang sedang Waiting.</p>
            <?php else: ?>
                <form method="post" action="<?php echo $base; ?>pertandingan/proses_skor.php">
                    <p>
                        <label for="pertandingan_id">Match</label>
                        <select id="pertandingan_id" name="pertandingan_id" required>
                            <?php foreach ($berjalan as $m): ?>
                                <option value="<?php echo (int) $m['id']; ?>">
                                    #<?php echo (int) $m['id']; ?> &middot; <?php echo e($m['game']); ?> &mdash; <?php echo e($m['pemain1']); ?> vs <?php echo e($m['pemain2']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </p>
                    <div class="baris-skor">
                        <p>
                            <label for="skor1">Score Player 1</label>
                            <input type="number" id="skor1" name="skor1" min="0" required>
                        </p>
                        <p>
                            <label for="skor2">Score Player 2</label>
                            <input type="number" id="skor2" name="skor2" min="0" required>
                        </p>
                    </div>
                    <p class="muted">Pemenang +25 MMR, yang kalah -15 MMR (minimal 0). Skor tidak boleh seri.</p>
                    <p><button type="submit" class="btn btn-primary">Submit Score</button></p>
                </form>
            <?php endif; ?>
        </section>
