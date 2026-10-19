<?php
$page_title = "Create Match";
$menu_aktif = 'buat';
include __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/games.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
$daftarTim = $_SESSION['tim'] ?? [];
?>
        <header>
            <h2>Create Match</h2>
            <p>Pilih game, lalu tentukan tim yang bertanding.</p>
        </header>

        <section aria-label="Form pembuatan match">
            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
            <?php endif; ?>

            <?php if (empty($daftarTim)): ?>
                <p>Belum ada tim yang terdaftar. <a href="../tim/tambah.php">Daftarkan tim</a> dulu sebelum membuat match.</p>
            <?php else: ?>
            <form id="form-tambah" class="form-validasi" method="post" action="proses_tambah.php" novalidate>
                <fieldset>
                    <legend>Game</legend>
                    <p>
                        <label for="game">Game yang dimainkan</label><br>
                        <select id="game" name="game" required>
                            <option value="">Pilih game...</option>
                            <?php foreach (DAFTAR_GAME as $g): ?>
                                <option value="<?php echo $g; ?>"><?php echo $g; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </p>
                </fieldset>
                <fieldset>
                    <legend>Peserta</legend>
                    <?php foreach (['tim1' => 'Team 1', 'tim2' => 'Team 2 (opsional)'] as $nama => $label): ?>
                    <p>
                        <label for="<?php echo $nama; ?>"><?php echo $label; ?></label><br>
                        <select id="<?php echo $nama; ?>" name="<?php echo $nama; ?>"<?php echo $nama === 'tim1' ? ' required' : ''; ?>>
                            <option value=""><?php echo $nama === 'tim1' ? 'Pilih tim...' : 'Tanpa lawan (status Waiting)'; ?></option>
                            <?php foreach (DAFTAR_GAME as $g): ?>
                                <optgroup label="<?php echo $g; ?>">
                                    <?php foreach ($daftarTim as $t): ?>
                                        <?php if ($t['game'] === $g): ?>
                                        <option value="<?php echo $t['nickname']; ?>"><?php echo $t['nickname'] . ' - ' . $t['nama_tim']; ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        </select>
                    </p>
                    <?php endforeach; ?>
                </fieldset>
                <p><small>Tanpa lawan = Waiting, dengan lawan = In-Progress. Kedua tim harus bermain game yang sama.</small></p>
                <p>
                    <button type="submit">Buat match</button>
                </p>
            </form>
            <?php endif; ?>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
