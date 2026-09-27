# VanguardArena

*Forge Your Legacy, Dominate the Leaderboard.*
Web matchmaking esports berbasis tim (PHP + PDO + PostgreSQL), siap di-deploy ke Vercel + Neon.

## Struktur
```
vercel.json                  pengaturan Vercel (runtime PHP + routing)
sql/01_vanguard_arena.sql    membuat tabel tim & pertandingan (data kosong, tanpa contoh)
api/
  index.php                  Home: hero, 3 kartu statistik, Global Leaderboard (top 10), Reset Data
  reset.php                  menghapus seluruh data tim & pertandingan
  asset.php                  menyajikan CSS/JS di Vercel (tidak perlu diubah)
  includes/                  koneksi.php (lokal), koneksi_vercel.php (Vercel), games.php (daftar game),
                              header.php (nav + logo), footer.php
  tim/                       tambah.php (form Daftarkan Tim), proses_tambah.php (INSERT)
  pertandingan/              list.php (tabel + search + Hapus + Submit Score), tambah.php (form),
                              proses_tambah.php (INSERT), proses_hapus.php (DELETE), proses_skor.php (UPDATE)
  assets/                    css/style.css, js/app.js
```

## Cara menjalankan
1. Neon > SQL Editor: jalankan seluruh isi `sql/01_vanguard_arena.sql` (data mulai kosong, isi lewat halaman "Daftarkan Tim" dan "Create Match").
2. Vercel > Settings > Environment Variables: pastikan `DATABASE_URL` ada, lalu deploy.
3. Lokal (Laragon): buat database `vanguard_arena`, jalankan file SQL-nya, jalankan `php -S localhost:8000 -t api`.

## Aturan
- Tim baru: 1000 MMR. Nama tim harus unik.
- Create Match tanpa lawan = **Waiting**, dengan lawan = **In-Progress**.
- Submit Score (skor tidak boleh seri): tim menang +25 MMR, yang kalah -15 MMR, match jadi **Completed**.
- Match apa pun (status apa pun) bisa dihapus lewat tombol Hapus di Matchmaking Hub.
- Reset Data (di Home) menghapus seluruh isi tabel tim dan pertandingan.

## Keputusan desain (untuk dijelaskan ke dosen)
- **Player -> Team.** Tabel `pemain` diganti `tim` (kolom `nama_tim`, bukan `username`). Semua game di `includes/games.php` adalah game beregu (Valorant, Mobile Legends, Dota 2, PUBG Mobile) — game 1v1 seperti EA FC 25 dilepas.
- **Data awal kosong.** File SQL cuma bikin tabel, tanpa `INSERT` data contoh. Yang tetap ada dari awal cuma daftar game (`games.php`), karena itu pilihan tetap, bukan data yang berubah-ubah.
- **Anggota tim tidak disimpan.** Supaya form dan tabel tetap sederhana (cukup `nama_tim` dan `game`), sesuai pola INSERT satu tabel yang sudah dipelajari. Bisa ditambah nanti kalau mau.
- **Tombol "Enter Queue" dihapus.** Dulu tidak benar-benar mengantrekan apa pun, jadi diganti "Daftarkan Tim" yang fungsinya jelas.
- **Search hanya kolom Game**, meniru latihan filter tabel di jobsheet (satu kolom), bukan filter dropdown atau pencarian di banyak kolom sekaligus.
- **Hapus match** pakai `DELETE FROM pertandingan WHERE id = :id`, pola prepared statement yang sama dengan INSERT/UPDATE.
- **Reset Data** memakai `DELETE FROM`, tanpa dialog konfirmasi JavaScript (`confirm()`), karena itu belum ada di jobsheet-jobsheet sebelumnya. Kalau mau ditambah nanti, tinggal bungkus tombolnya dengan `onsubmit="return confirm('yakin?')"`.
- **Nav aktif & logo.** `header.php` mengecek `$menu_aktif` tiap halaman untuk menandai menu yang aktif (CSS `.aktif`), dan logo selalu mengarah ke `index.php`.
