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
                    <label for="nickname">Nickname Tim (1-3 karakter, cth: RRQ)</label>
                    <input type="text" id="nickname" name="nickname" maxlength="3" required>
                </p>
                <p>
                    <label for="nama_tim">Nama Tim Lengkap (3-30 karakter)</label>
                    <input type="text" id="nama_tim" name="nama_tim" maxlength="30" required>
                </p>
                <p>
                    <label for="jumlah_anggota">Jumlah Anggota (opsional)</label>
                    <input type="number" id="jumlah_anggota" name="jumlah_anggota" min="1" max="20">
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
                <p class="catatan">Tim baru mulai dengan 1000 MMR. Nickname + game inilah yang harus unik &mdash; nickname atau nama tim yang sama boleh dipakai lagi asal game-nya berbeda.</p>
                <p><button type="submit" class="btn btn-primary">Register</button></p>
            </form>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
