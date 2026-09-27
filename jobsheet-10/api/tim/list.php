<?php
$page_title = "Kelola Tim";
$menu_aktif = 'kelola';
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

// ===== Pagination & pencarian sisi server (pola Jobsheet 9) =====
$perPage = 5;
$page    = max(1, (int) ($_GET['page'] ?? 1));
$offset  = ($page - 1) * $perPage;
$keyword = trim($_GET['q'] ?? '');

if ($keyword !== '') {
    $hitung = $pdo->prepare("SELECT COUNT(*) FROM tim WHERE nickname ILIKE :kw");
    $hitung->execute(['kw' => '%' . $keyword . '%']);
    $totalRows = $hitung->fetchColumn();

    $stmt = $pdo->prepare("SELECT * FROM tim WHERE nickname ILIKE :kw ORDER BY id DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue('kw', '%' . $keyword . '%');
} else {
    $totalRows = $pdo->query("SELECT COUNT(*) FROM tim")->fetchColumn();
    $stmt = $pdo->prepare("SELECT * FROM tim ORDER BY id DESC LIMIT :limit OFFSET :offset");
}
$stmt->bindValue('limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue('offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$daftarTim  = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalPages = max(1, (int) ceil($totalRows / $perPage));
?>
        <section>
            <div class="panel-kepala">
                <h2>Kelola Tim</h2>
                <?php if ($sudahLogin): ?>
                <a class="btn btn-primary btn-kecil" href="tambah.php">Daftarkan Tim</a>
                <?php endif; ?>
            </div>

            <?php if (isset($_GET['pesan'])): ?>
                <p class="flash flash-<?php echo htmlspecialchars($_GET['tipe'] ?? 'sukses'); ?>"><?php echo htmlspecialchars($_GET['pesan']); ?></p>
            <?php endif; ?>

            <form method="get" action="list.php" class="search-box">
                <label for="search-input">Cari Berdasarkan Nickname</label>
                <input type="text" id="search-input" name="q" value="<?php echo htmlspecialchars($keyword); ?>" placeholder="Ketik nickname...">
                <button type="submit" class="btn btn-outline btn-kecil">Cari</button>
            </form>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Nickname</th>
                            <th>Nama Tim</th>
                            <th>Game</th>
                            <th>Anggota</th>
                            <th>MMR</th>
                            <?php if ($sudahLogin): ?><th>Aksi</th><?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($daftarTim)): ?>
                        <tr>
                            <td colspan="<?php echo $sudahLogin ? 6 : 5; ?>">Tidak ada tim yang cocok.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($daftarTim as $t): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($t['nickname']); ?></td>
                                <td><?php echo htmlspecialchars($t['nama_tim']); ?></td>
                                <td><?php echo htmlspecialchars($t['game']); ?></td>
                                <td><?php echo $t['jumlah_anggota'] !== null ? (int) $t['jumlah_anggota'] : '-'; ?></td>
                                <td><?php echo (int) $t['mmr']; ?></td>
                                <?php if ($sudahLogin): ?>
                                <td>
                                    <a class="btn btn-outline btn-kecil" href="edit.php?id=<?php echo $t['id']; ?>">Edit</a>
                                    <form class="form-hapus" method="post" action="hapus.php">
                                        <input type="hidden" name="id" value="<?php echo $t['id']; ?>">
                                        <button type="submit" class="btn btn-kecil btn-bahaya">Hapus</button>
                                    </form>
                                </td>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <nav class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="list.php?page=<?php echo $i; ?><?php echo $keyword !== '' ? '&q=' . urlencode($keyword) : ''; ?>"
                   class="<?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
            </nav>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
