<?php
require __DIR__ . '/includes/koneksi.php';

$data = json_decode(file_get_contents(__DIR__ . '/data/buku.json'), true);
$stmt = $pdo->prepare(
    "INSERT INTO buku (judul, pengarang, tahun, isbn, stok, kategori)
     VALUES (:judul, :pengarang, :tahun, :isbn, :stok, :kategori)"
);
foreach ($data as $b) {
    $stmt->execute([
        'judul'     => $b['judul'],
        'pengarang' => $b['pengarang'],
        'tahun'     => $b['tahun'],
        'isbn'      => $b['isbn'] ?? '',
        'stok'      => $b['stok'] ?? 0,
        'kategori'  => $b['kategori'] ?? '',
    ]);
}
echo count($data) . " buku berhasil dimigrasi.";