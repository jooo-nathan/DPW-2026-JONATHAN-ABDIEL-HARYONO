<?php
$page_title = "Daftarkan Tim";
$menu_aktif = 'daftar';
include __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/games.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
        <header>
            <h2>Daftarkan Tim</h2>
            <p>Tim baru langsung mulai dari 1000 MMR.</p>
        </header>

        <section aria-label="Form pendaftaran tim">
            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
            <?php endif; ?>

            <form id="form-tambah" class="form-validasi" method="post" action="proses_tambah.php" novalidate>
                <fieldset>
                    <legend>Identitas tim</legend>
                    <p>
                        <label for="nickname">Nickname (1-3 karakter, contoh: RRQ)</label><br>
                        <input type="text" id="nickname" name="nickname" maxlength="3" required>
                    </p>
                    <p>
                        <label for="nama_tim">Nama tim lengkap</label><br>
                        <input type="text" id="nama_tim" name="nama_tim" maxlength="30" required>
                    </p>
                </fieldset>
                <fieldset>
                    <legend>Game dan anggota</legend>
                    <p>
                        <label for="game">Game yang dimainkan</label><br>
                        <select id="game" name="game" required>
                            <option value="">Pilih game...</option>
                            <?php foreach (DAFTAR_GAME as $g): ?>
                                <option value="<?php echo $g; ?>"><?php echo $g; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </p>
                    <p>
                        <label for="jumlah_anggota">Jumlah anggota (opsional)</label><br>
                        <input type="number" id="jumlah_anggota" name="jumlah_anggota" min="1" max="20">
                    </p>
                </fieldset>
                <p><small>Nickname dan nama tim boleh sama dengan tim lain, asal game-nya berbeda.</small></p>
                <p>
                    <button type="submit">Daftarkan tim</button>
                </p>
            </form>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
