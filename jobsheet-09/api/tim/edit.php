<?php
$page_title = "Edit Tim";
$menu_aktif = 'kelola';
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: list.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM tim WHERE id = :id");
$stmt->execute(['id' => $id]);
$tim = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tim) {
    header('Location: list.php');
    exit;
}
?>
        <section>
            <h2>Edit Tim</h2>

            <?php if (isset($_GET['pesan'])): ?>
                <p class="flash flash-<?php echo htmlspecialchars($_GET['tipe'] ?? 'sukses'); ?>"><?php echo htmlspecialchars($_GET['pesan']); ?></p>
            <?php endif; ?>

            <form method="post" action="proses_edit.php">
                <input type="hidden" name="id" value="<?php echo $tim['id']; ?>">
                <p>
                    <label for="nickname">Nickname Tim (1-3 karakter)</label>
                    <input type="text" id="nickname" name="nickname" maxlength="3" value="<?php echo htmlspecialchars($tim['nickname']); ?>" required>
                </p>
                <p>
                    <label for="nama_tim">Nama Tim Lengkap (3-30 karakter)</label>
                    <input type="text" id="nama_tim" name="nama_tim" maxlength="30" value="<?php echo htmlspecialchars($tim['nama_tim']); ?>" required>
                </p>
                <p>
                    <label for="jumlah_anggota">Jumlah Anggota (opsional)</label>
                    <input type="number" id="jumlah_anggota" name="jumlah_anggota" min="1" max="20" value="<?php echo htmlspecialchars((string) $tim['jumlah_anggota']); ?>">
                </p>
                <p>
                    <label for="game">Game yang Dimainkan</label>
                    <select id="game" name="game" required>
                        <?php foreach (DAFTAR_GAME as $g): ?>
                            <option <?php echo $tim['game'] === $g ? 'selected' : ''; ?>><?php echo htmlspecialchars($g); ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <p class="catatan">MMR saat ini: <strong><?php echo (int) $tim['mmr']; ?></strong> (tidak diubah lewat form ini &mdash; MMR hanya berubah lewat Submit Score).</p>
                <p><button type="submit" class="btn btn-primary">Simpan Perubahan</button></p>
            </form>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
