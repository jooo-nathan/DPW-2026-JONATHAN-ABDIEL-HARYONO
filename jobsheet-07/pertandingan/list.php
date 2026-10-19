<?php
$page_title = "Matchmaking Hub";
$menu_aktif = 'hub';
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
$daftarMatch = $_SESSION['pertandingan'] ?? [];
?>
        <header>
            <h2>Matchmaking Hub</h2>
            <p>Semua lobby: menunggu lawan (Waiting), sedang main (In-Progress), dan sudah selesai (Completed).</p>
            <a href="tambah.php" class="btn">Create Match</a>
        </header>

        <section aria-label="Daftar match">
            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
            <?php endif; ?>

            <div class="toolbar">
                <div class="search-box">
                    <label for="search-input">Cari game</label>
                    <input type="text" id="search-input" placeholder="Ketik nama game...">
                </div>
            </div>
            <p id="filter-counter" data-label="match"></p>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Game</th>
                            <th>Team 1</th>
                            <th>Team 2</th>
                            <th>Skor</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($daftarMatch)): ?>
                        <tr>
                            <td colspan="6">Belum ada match. Klik "Create Match" untuk membuat yang pertama.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($daftarMatch as $m): ?>
                            <tr>
                                <td><?php echo $m['game']; ?></td>
                                <td><?php echo $m['tim1']; ?></td>
                                <td><?php echo $m['tim2']; ?></td>
                                <td><?php echo $m['skor']; ?></td>
                                <td><span class="badge status-<?php echo strtolower($m['status']); ?>"><?php echo $m['status']; ?></span></td>
                                <td>
                                    <button type="button" class="btn-hapus">Hapus</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
