# VanguardArena

*Forge Your Legacy, Dominate the Leaderboard.*
Web matchmaking esports sederhana (PHP + PDO + PostgreSQL), deploy ke Vercel + Neon.

Ini versi yang **isinya sama persis** dengan versi awal (data pemain & pertandingan tersimpan sungguhan di database Neon, siap online), tapi kodenya **dipangkas** — dua dari empat bagian paling rumit di versi awal sudah dihilangkan total, bukan cuma disamarkan.

## Apa yang berubah dari versi awal

| Bagian | Versi awal | Versi ini |
|---|---|---|
| CSS/JS | Lewat `api/asset.php` (workaround karena file di dalam folder `api/` tidak disajikan langsung oleh Vercel) | Folder `assets/` dipindah ke **luar** folder `api/` → otomatis disajikan sebagai file statis biasa oleh Vercel, **tanpa kode PHP tambahan** |
| Link menu & CSS/JS di `header.php` | Dihitung otomatis lewat rumus `$base` (biar tetap benar walau halaman ada di dalam subfolder) | Ditulis langsung sebagai path absolut (`/assets/...`, `/api/...`) — bisa begitu karena project di Vercel **selalu** hidup di root domain, jadi rumus `$base` tidak dibutuhkan sama sekali |
| `vercel.json` | Ada aturan routing custom (regex) untuk menyamarkan folder `api/` dan mengarahkan asset | Cuma 2 baris inti: bilang ke Vercel "file `.php` di `api/` jalankan pakai PHP" + "kalau orang buka `/`, tampilkan `api/index.php`" |

**Yang tidak bisa dihilangkan** (konsekuensi langsung dari pakai Neon + Vercel, bukan pilihan gaya coding):
- `includes/koneksi.php` tetap perlu kode tambahan untuk memberi tahu Neon "server mana persis" yang dituju (dijelaskan lewat komentar di dalam filenya).
- `vercel.json` tetap perlu bilang ke Vercel bahwa file `.php` dijalankan pakai runtime PHP komunitas (`vercel-php`), karena PHP bukan bahasa yang didukung Vercel secara bawaan.
- Flash message (`?pesan=...` di URL, bukan `$_SESSION`) — dijelaskan lengkap di komentar `api/index.php`, intinya: server serverless (Vercel) bisa "ganti-ganti" komputer di setiap request, jadi `$_SESSION` berbasis file tidak selalu bisa diandalkan.

## Struktur
```
vercel.json                  hanya 2 baris: runtime PHP + rewrite "/" -> api/index.php
sql/01_vanguard_arena.sql    bikin tabel pemain & pertandingan + data contoh
assets/                      css/style.css, js/app.js — file statis biasa, di LUAR folder api/
api/
  index.php                  Home: hero, 3 kartu statistik, Global Leaderboard (top 10)
  includes/                  koneksi.php, header.php, footer.php
  pemain/                    tambah.php (form), proses_tambah.php (INSERT)
  pertandingan/              list.php (tabel + form skor), tambah.php (form),
                              proses_tambah.php (INSERT), proses_skor.php (UPDATE)
```

## Cara deploy
1. **Neon** (neon.tech): buat project baru, catat *connection string*-nya.
2. Neon → **SQL Editor**: jalankan seluruh isi `sql/01_vanguard_arena.sql` (menjalankan ulang = data kembali ke awal).
3. Upload project ini ke GitHub, lalu **import ke Vercel**.
4. Vercel → **Settings → Environment Variables** → tambahkan `DATABASE_URL` = connection string dari langkah 1.
5. Deploy. Selesai.

Setelah deploy, semua halaman bisa diakses lewat:
- `/` → Home
- `/api/pemain/tambah.php` → Join Arena
- `/api/pertandingan/list.php` → Matchmaking Hub
- `/api/pertandingan/tambah.php` → Create Match

## Aturan
- Pemain baru: 1000 MMR.
- Create Match tanpa lawan = **Waiting**, dengan lawan = **In-Progress**.
- Submit Score (skor tidak boleh seri): pemenang +25 MMR, yang kalah -15 MMR, match jadi **Completed**.

## Yang dipelajari (sesuai Jobsheet 6-8)
| Konsep | Ada di |
|---|---|
| Koneksi PDO | `includes/koneksi.php` |
| `SELECT` + `foreach` menampilkan tabel | `index.php`, `pertandingan/list.php` |
| `COUNT(*)`, `ORDER BY ... DESC`, `LIMIT` | `index.php` (statistik & leaderboard) |
| `INSERT` dengan prepared statement | `pemain/proses_tambah.php`, `pertandingan/proses_tambah.php` |
| Validasi server + `try/catch` (username UNIQUE) | `pemain/proses_tambah.php` — ini "latihan tambahan" opsional di Jobsheet 8 §7.4, bukan wajib, tapi dipakai di sini karena memang praktik yang baik |
| `UPDATE` | `pertandingan/proses_skor.php` — **satu-satunya bagian di luar Jobsheet 8** (baru dibahas di Jobsheet 9), dijelaskan lengkap lewat komentar di dalam filenya karena tanpa ini fitur MMR tidak bisa jalan |

## Kalau ditanya dosen/reviewer "kenapa flash message beda dari jobsheet lokal?"
Jawaban singkat yang bisa kamu pakai: *"Di server lokal, `$_SESSION` aman dipakai karena selalu satu komputer yang sama yang melayani semua request. Di Vercel (serverless), tiap request bisa dilayani mesin yang berbeda, jadi saya titipkan pesannya lewat parameter URL saat redirect supaya tetap sampai ke halaman berikutnya, apa pun mesin yang melayani."*
