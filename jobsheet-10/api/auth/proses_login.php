<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/../includes/koneksi.php';

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nama']    = $user['nama'];
    $_SESSION['role']    = $user['role'];
    header('Location: ../index.php');
    exit;
}

// Pesan digabung (tidak dibedakan "username salah" vs "password salah")
// supaya tidak membantu orang menebak-nebak username yang valid.
header('Location: login.php?tipe=error&pesan=' . urlencode('Username atau password salah.'));
exit;
