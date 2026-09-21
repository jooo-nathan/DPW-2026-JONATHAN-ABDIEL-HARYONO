# VanguardArena

*Forge Your Legacy, Dominate the Leaderboard.*
Web matchmaking esports sederhana (PHP + PDO + PostgreSQL), siap di-deploy ke Vercel + Neon.

## Struktur
```
vercel.json                  pengaturan Vercel (runtime PHP + routing)
sql/01_vanguard_arena.sql    membuat tabel pemain & pertandingan + data contoh
api/
  index.php                  Home: hero, 3 kartu statistik, Global Leaderboard (top 10)
  asset.php                  menyajikan CSS/JS di Vercel (tidak perlu diubah)
  includes/                  koneksi.php (koneksi belajar, lokal), koneksi_vercel.php (khusus Vercel), header.php, footer.php
  pemain/                    tambah.php (form), proses_tambah.php (INSERT)
  pertandingan/              list.php (tabel + form skor), tambah.php (form),
                             proses_tambah.php (INSERT), proses_skor.php (UPDATE)
  assets/                    css/style.css, js/app.js
```

## Cara menjalankan
1. Neon > SQL Editor: jalankan seluruh isi `sql/01_vanguard_arena.sql` (menjalankan ulang = data kembali ke awal).
2. Vercel > Settings > Environment Variables: pastikan `DATABASE_URL` berisi connection string Neon, lalu deploy. Kalau variabel ini ada, `koneksi.php` otomatis memakai `koneksi_vercel.php`.
3. Lokal (Laragon): buat database `vanguard_arena`, jalankan file SQL-nya, lalu jalankan `php -S localhost:8000 -t api`. Sesuaikan user/password di `koneksi.php` kalau berbeda.

## Aturan
- Pemain baru: 1000 MMR.
- Create Match tanpa lawan = **Waiting**, dengan lawan = **In-Progress**.
- Submit Score (skor tidak boleh seri): pemenang +25 MMR, yang kalah -15 MMR, match jadi **Completed**.

## Yang dipelajari (sama dengan Jobsheet 8)
| Konsep | Ada di |
|---|---|
| Koneksi PDO (lima variabel + DSN + try/catch) | `includes/koneksi.php` |
| `SELECT` + `foreach` menampilkan tabel | `index.php`, `pertandingan/list.php` |
| `COUNT(*)`, `ORDER BY ... DESC`, `LIMIT` | `index.php` (statistik & leaderboard) |
| `INSERT` dengan prepared statement | `pemain/proses_tambah.php`, `pertandingan/proses_tambah.php` |
| Validasi server + `try/catch` (username UNIQUE) | `pemain/proses_tambah.php` |
| `UPDATE` (satu-satunya bagian di luar Jobsheet 8) | `pertandingan/proses_skor.php` |

## Ide pengembangan (opsional)
Tombol Join untuk lobi Waiting, batas MMR minimal 0, transaksi database di `proses_skor.php`, badge tier (Bronze-Diamond).
