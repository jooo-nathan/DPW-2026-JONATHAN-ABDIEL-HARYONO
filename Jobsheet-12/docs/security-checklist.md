# Checklist Keamanan — Jobsheet 11 (VanguardArena)

Audit menyeluruh terhadap kode Jobsheet 7-10, dengan bukti before/after.

| # | Kerentanan | Ditemukan di | Sebelum | Sesudah (perbaikan) |
|---|---|---|---|---|
| 1 | SQL Injection | Semua query di `tim/`, `pertandingan/`, `auth/`, `home.php`, `reset.php` | Sejak Jobsheet 8 sudah memakai prepared statement PDO (`:parameter`) | **Diaudit ulang, sudah aman** — tidak ada query yang menyisipkan `$_POST`/`$_GET` langsung ke string SQL. Diuji username `' OR '1'='1` di form login → tidak bisa bypass. |
| 2 | XSS | `home.php`, `tim/list.php`, `tim/edit.php`, `tim/tambah.php`, `pertandingan/list.php`, `pertandingan/tambah.php`, `auth/*.php`, `includes/header.php` (nama petugas) | Output memakai `htmlspecialchars()` polos (tanpa `ENT_QUOTES`, tanpa `UTF-8`), beberapa nilai (`status`, `id`, `mmr`, skor) dicetak mentah | Semua diganti fungsi `e()` (`includes/helpers.php`, `ENT_QUOTES` + `UTF-8`); nilai angka di-cast `(int)`. Diuji simpan nama tim `<script>alert(1)</script>` → tampil sebagai teks, tidak dieksekusi. |
| 3 | CSRF | Semua form POST: Daftarkan Tim, Edit/Hapus Tim, Create Match, Submit Score, Hapus Match, Reset Data, Login, Register | Form POST tidak punya token — bisa dipicu dari situs lain (terutama Reset Data & Hapus) | `includes/csrf.php` (`csrf_field()` + `csrf_verify()`), token di `$_SESSION['csrf_token']`, diverifikasi di semua `proses_*.php`, `hapus.php`, `reset.php`. `reset.php` kini hanya menerima POST. |
| 4 | Validasi & Sanitasi Input | `proses_*.php` (tim, pertandingan) | Validasi wajib-isi, panjang nickname, skor tidak seri, cek game sama sudah ada sejak Jobsheet 7-9 | **Diaudit ulang, dipertahankan**; ditambah cast `(int)` pada `id`/skor yang dicetak ke HTML. |
| 5 | Session Fixation | `auth/proses_login.php` | Session ID tidak diperbarui setelah login | `session_regenerate_id(true)` dipanggil tepat setelah `password_verify()` berhasil. |

## Catatan Implementasi
- `includes/auth.php` (guard login) selalu dijalankan **sebelum** `csrf_verify()` di halaman proses, jadi yang belum login langsung diarahkan ke Login.
- `csrf_verify()` mengembalikan HTTP 403 dan `die()` bila token tidak ada/tidak cocok.
- Form pencarian (`method="get"`) sengaja **tidak** diberi token karena hanya membaca data.
- Catatan Vercel dari Jobsheet 10 tetap berlaku: session berbasis file tidak stabil di serverless, jalankan demo di Laragon.

## Cara Menguji
1. **CSRF** (setelah login): `curl -X POST http://localhost:8000/tim/proses_tambah.php -d "nickname=x"` → 403 "token CSRF tidak valid".
2. **XSS**: daftarkan tim dengan Nama Tim `<script>alert(1)</script>` → buka Kelola Tim, tampil sebagai teks, tanpa pop-up.
3. **Urutan guard**: POST ke `/pertandingan/proses_hapus.php` tanpa login → diarahkan ke Login.
4. **SQL Injection**: login dengan username `' OR '1'='1` → tetap "Username atau password salah."
5. **Session fixation**: bandingkan cookie `PHPSESSID` sebelum dan sesudah login → nilainya berubah.
