<?php
$page_title = "Kelola Tim";
$menu_aktif = 'kelola';
include __DIR__ . '/../includes/header.php';

// Flash message: dibaca sekali lalu dihapus supaya tidak muncul lagi saat refresh
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
$daftarTim = $_SESSION['tim'] ?? [];
?>
        <header>
            <h2>Kelola Tim</h2>
            <p>Semua tim yang terdaftar beserta game yang dimainkan dan MMR-nya.</p>
            <a href="tambah.php" class="btn">Daftarkan Tim</a>
        </header>

        <section aria-label="Daftar tim">
            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
            <?php endif; ?>

            <div class="toolbar">
                <div class="search-box">
                    <label for="search-input">Cari nickname tim</label>
                    <input type="text" id="search-input" placeholder="Ketik nickname...">
                </div>
            </div>
            <p id="filter-counter" data-label="tim"></p>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Nickname</th>
                            <th>Nama Tim</th>
                            <th>Game</th>
                            <th>Anggota</th>
                            <th>MMR</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($daftarTim)): ?>
                        <tr>
                            <td colspan="6">Belum ada tim. Silakan tambah lewat menu "Daftarkan Tim".</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($daftarTim as $tim): ?>
                            <tr>
                                <td><?php echo $tim['nickname']; ?></td>
                                <td><?php echo $tim['nama_tim']; ?></td>
                                <td><?php echo $tim['game']; ?></td>
                                <td><?php echo $tim['jumlah_anggota'] ?? '-'; ?></td>
                                <td><?php echo $tim['mmr']; ?></td>
                                <td>
                                    <button type="button" class="btn-edit">Edit</button>
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
