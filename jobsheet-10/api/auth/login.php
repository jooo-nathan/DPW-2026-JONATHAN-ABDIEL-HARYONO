<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$page_title = "Login";
include __DIR__ . '/../includes/header.php';
?>
        <section>
            <h2>Login Petugas</h2>

            <?php if (isset($_GET['pesan'])): ?>
                <p class="flash flash-<?php echo htmlspecialchars($_GET['tipe'] ?? 'sukses'); ?>"><?php echo htmlspecialchars($_GET['pesan']); ?></p>
            <?php endif; ?>

            <form method="post" action="proses_login.php">
                <p>
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </p>
                <p>
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </p>
                <p><button type="submit" class="btn btn-primary">Login</button></p>
            </form>
            <p class="catatan">Belum punya akun petugas? <a href="register.php">Daftar di sini</a>.</p>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
