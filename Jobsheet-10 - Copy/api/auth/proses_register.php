<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/../includes/koneksi.php';

$nama     = trim($_POST['nama'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

$errors = [];
if ($nama === '') {
    $errors[] = "Nama wajib diisi.";
}
if ($username === '') {
    $errors[] = "Username wajib diisi.";
}
if (strlen($password) < 6) {
    $errors[] = "Password minimal 6 karakter.";
}

if (!empty($errors)) {
    header('Location: register.php?tipe=error&pesan=' . urlencode(implode(' ', $errors)));
    exit;
}

// Dicek manual dulu supaya pesannya ramah, walau kolom username sudah
// UNIQUE di database (yang akan menolaknya juga kalau ini terlewat).
$cek = $pdo->prepare("SELECT id FROM users WHERE username = :username");
$cek->execute(['username' => $username]);
if ($cek->fetch()) {
    header('Location: register.php?tipe=error&pesan=' . urlencode('Username sudah digunakan.'));
    exit;
}

$stmt = $pdo->prepare(
    "INSERT INTO users (nama, username, password, role) VALUES (:nama, :username, :password, 'petugas')"
);
$stmt->execute([
    'nama'     => $nama,
    'username' => $username,
    'password' => password_hash($password, PASSWORD_DEFAULT),
]);

header('Location: login.php?tipe=sukses&pesan=' . urlencode('Registrasi berhasil, silakan login.'));
exit;
