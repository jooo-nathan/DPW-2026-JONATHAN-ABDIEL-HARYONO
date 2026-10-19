<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$page_title = "Registrasi Petugas";
include __DIR__ . '/../includes/header.php';
?>
        <section>
            <h2>Registrasi Petugas</h2>

            <?php if (isset($_GET['pesan'])): ?>
                <p class="flash flash-<?php echo e($_GET['tipe'] ?? 'sukses'); ?>"><?php echo e($_GET['pesan']); ?></p>
            <?php endif; ?>

            <form method="post" action="proses_register.php">
                <?php echo csrf_field(); ?>
                <p>
                    <label for="nama">Nama</label>
                    <input type="text" id="nama" name="nama" required>
                </p>
                <p>
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </p>
                <p>
                    <label for="password">Password (minimal 6 karakter)</label>
                    <input type="password" id="password" name="password" minlength="6" required>
                </p>
                <p><button type="submit" class="btn btn-primary">Daftar</button></p>
            </form>
            <p class="catatan">Sudah punya akun? <a href="login.php">Login di sini</a>.</p>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
