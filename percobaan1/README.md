# VanguardArena

*Forge Your Legacy, Dominate the Leaderboard.*
Web matchmaking esports sederhana: daftar pemain, lobi pertandingan, submit skor, dan leaderboard MMR.
Dibuat dengan PHP + PDO + PostgreSQL, siap di-deploy ke Vercel (runtime `vercel-php`) dengan database Neon.

## Struktur
```
vercel.json                  routing + runtime PHP untuk Vercel
sql/01_vanguard_arena.sql    tabel pemain & pertandingan + data contoh
api/
  index.php                  beranda: hero, statistik, Active Lobbies, Submit Score, Top 5
  leaderboard.php            leaderboard lengkap (top 50) + pencarian
  asset.php                  menyajikan CSS/JS di Vercel
  includes/                  koneksi.php, helper.php, header.php, footer.php, form_skor.php
  pemain/                    tambah.php, proses_tambah.php  (Join Arena)
  pertandingan/              list.php, tambah.php, proses_tambah.php, proses_join.php, proses_skor.php
  assets/                    css/style.css, js/app.js
```

## Cara menjalankan
1. **Database (Neon):** buka SQL Editor, jalankan seluruh isi `sql/01_vanguard_arena.sql`.
2. **Vercel:** Settings > Environment Variables > tambah `DATABASE_URL` (connection string Neon, diawali `postgresql://`), lalu deploy ulang.
3. **Lokal (opsional):** buka `api/includes/koneksi.php`, ganti blok `DATABASE_URL` dengan variabel lokal (petunjuknya ada di komentar), lalu jalankan `php -S localhost:8000 -t api`.

## Aturan permainan
- Pemain baru mulai dengan **1000 MMR** (tier Silver).
- Create Match tanpa lawan = **Waiting**; ada lawan atau di-Join = **In-Progress**.
- Submit Score: skor tidak boleh seri. Pemenang **+25 MMR**, yang kalah **-15 MMR** (minimal 0). Match jadi **Completed**.
- Tier: Bronze (<900), Silver (900+), Gold (1100+), Platinum (1300+), Diamond (1500+).

## Konsep yang dipakai
- **PDO + prepared statement** (`:nama`) untuk semua query: SELECT, INSERT, UPDATE.
- **Validasi server-side** dan `try/catch` (username UNIQUE, kode error `23505`).
- **Transaksi** (`beginTransaction`, `commit`, `rollBack`) di `proses_skor.php`: tiga UPDATE berhasil semua atau tidak sama sekali.
- **`ORDER BY mmr DESC`** untuk leaderboard, `COUNT(*)` untuk statistik.
- Pesan sukses/error dibawa lewat alamat (`?status=...&pesan=...`), bukan session, supaya jalan di Vercel.
