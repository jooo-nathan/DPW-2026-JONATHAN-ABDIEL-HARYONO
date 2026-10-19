# Jobsheet 7 — PHP Dasar & Form Handling (Vanguard Arena)

Sub-CPMK: Mengimplementasikan dasar PHP & pengolahan form.

## Perubahan dari Jobsheet 6
- Semua halaman `.html` diubah menjadi `.php`.
- `includes/header.php` dan `includes/footer.php` dipakai lewat `include`, jadi sidebar dan footer tidak lagi disalin ke tiap halaman. `header.php` juga memanggil `session_start()` dan menghitung `$base` (path relatif sesuai kedalaman folder), sehingga proyek jalan lewat `php -S` maupun lewat subfolder (Laragon).
- `tim/tambah.php` dan `pertandingan/tambah.php`: form kini `method="post"` ke `proses_tambah.php` masing-masing.
- `tim/proses_tambah.php` dan `pertandingan/proses_tambah.php`: memvalidasi `$_POST` di **server** (terpisah dari validasi JS Jobsheet 5, tetap bekerja walau JS dimatikan), menyimpan sementara ke `$_SESSION['tim']` / `$_SESSION['pertandingan']`, lalu redirect ke `list.php`.
- `tim/list.php` dan `pertandingan/list.php`: tabel dirender dari `$_SESSION` lewat `foreach` (menggantikan fetch/JSON Jobsheet 6).
- Flash message sukses/gagal lewat `$_SESSION['flash']`: dibaca sekali lalu di-`unset`.
- Dihapus: `assets/js/tim.js`, `pertandingan.js`, dan folder `data/` karena rendering pindah ke server.

## Aturan validasi server
| Form | Aturan |
|---|---|
| Daftarkan Tim | Nickname 1-3 huruf/angka; nama tim 3-30 karakter; jumlah anggota 1-20 (opsional); game harus salah satu dari 4 game; nickname + game dan nama tim + game tidak boleh kembar |
| Create Match | Game valid; Team 1 wajib dan harus tim dari game itu; Team 2 opsional, tidak boleh sama dengan Team 1, dan harus dari game yang sama |

Tanpa Team 2 status match **Waiting**, dengan Team 2 status **In-Progress**.

## Cara menjalankan
Dari dalam folder `jobsheet-07/`:
```bash
php -S localhost:8000
```
Buka `http://localhost:8000/index.php`. Atau lewat Laragon (virtual host / subfolder).

## Catatan
- Data di `$_SESSION` hilang saat sesi browser berakhir; ini jembatan sementara. Jobsheet berikutnya memindahkan penyimpanan ke PostgreSQL.
- Coba nonaktifkan JavaScript lalu kirim form kosong: validasi server tetap menolak dan flash error muncul.
- Tombol Hapus masih sebatas tampilan (baris hilang, tetapi muncul lagi saat refresh).
- Output belum di-escape (`htmlspecialchars`); pencegahan XSS dibahas di jobsheet keamanan.
