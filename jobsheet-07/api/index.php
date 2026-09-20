<?php
$page_title = "Beranda";
include __DIR__ . '/includes/header.php';

$totalBuku = count($_SESSION['buku'] ?? []);
$totalAnggota = count($_SESSION['anggota'] ?? []);
?>
        <section class="hero">
            <h2>Selamat Datang di Sistem Perpustakaan Mini</h2>
            <p>Aplikasi sederhana untuk mengelola data buku dan anggota perpustakaan.</p>
        </section>

        <section>
            <h2>Ringkasan</h2>
            <article>
                <span class="ikon">&#128215;</span>
                <h3>Total Buku</h3>
                <p><?php echo $totalBuku; ?></p>
            </article>
            <article>
                <span class="ikon">&#128101;</span>
                <h3>Total Anggota</h3>
                <p><?php echo $totalAnggota; ?></p>
            </article>
            <article>
                <span class="ikon">&#128228;</span>
                <h3>Sedang Dipinjam</h3>
                <p>0</p>
            </article>
        </section>
        <form class="form-reset" method="post" action="reset.php">
            <button type="submit">Reset Data</button>
        </form>
<?php include __DIR__ . '/includes/footer.php'; ?>
