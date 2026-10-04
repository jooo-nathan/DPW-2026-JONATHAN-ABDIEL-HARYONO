# Changelog — VanguardArena

## Tahap 1: Perubahan pada fitur Tim (mengikuti diskusi poin 1-4)
- **Kolom baru di tabel `tim`:** `nickname` (VARCHAR(3), tag pendek ala tim esports) dan `jumlah_anggota` (INT, opsional, sekadar data tambahan).
- **Keunikan per game:** `UNIQUE(nickname, game)` dan `UNIQUE(nama_tim, game)` menggantikan `UNIQUE(nama_tim)` tunggal — nickname/nama tim boleh dipakai ulang asal game-nya beda.
- **Create Match jadi 2 langkah** (`?game=...` lewat GET dulu, baru dropdown tim muncul): dropdown Team 1/Team 2 otomatis hanya berisi tim dari game yang dipilih, jadi dua tim beda game tidak akan pernah bisa dipilih bersamaan. `proses_tambah.php` menambahkan pengecekan ulang di server (jaga-jaga kalau ada yang mengirim data manual).
- **MMR update di `proses_skor.php`** sekarang menyaring `WHERE nickname = ... AND game = ...` (bukan nickname saja), supaya tidak salah sasaran ke tim lain yang kebetulan nickname-nya sama di game berbeda.
- **Leaderboard** menampilkan `nickname` (bukan `nama_tim`), dan pencarian `data-kolom` di `app.js` digeneralisasi supaya satu fungsi JS yang sama bisa dipakai untuk kolom apa pun (Game di Matchmaking Hub, Nickname di Leaderboard).

## Tahap 2: Konsep Jobsheet 9 (CRUD lengkap: Update, Delete, Pagination, Search server-side)
- **`tim/list.php`** (baru): halaman "Kelola Tim" dengan pagination (`LIMIT`/`OFFSET`, `bindValue(..., PDO::PARAM_INT)`) dan pencarian server-side `ILIKE` berdasarkan nickname — persis pola di Jobsheet 9, diterapkan ke tabel `tim`.
- **`tim/edit.php` + `tim/proses_edit.php`** (baru): form Update dengan `id` tersembunyi, validasi sama persis dengan pendaftaran.
- **`tim/hapus.php`** (baru) dan `pertandingan/proses_hapus.php` (diperbarui): keduanya sekarang menolak request selain `POST` (`$_SERVER['REQUEST_METHOD']`).
- **`app.js`**: fungsi `initHapusConfirm()` baru — dialog konfirmasi lewat event `submit` (bisa dibatalkan dengan `preventDefault()`), dipasang otomatis ke semua `<form class="form-hapus">` (dipakai bareng oleh Kelola Tim dan Matchmaking Hub).
- Nav baru: **Kelola Tim**.

*(Auth/login BELUM ada di tahap ini — semua orang masih bisa mengedit/menghapus.)*

## Tahap 3: Konsep Jobsheet 10 (Autentikasi)
- **Tabel `users`** (`sql/02_users.sql`): `nama`, `username` (UNIQUE), `password` (di-hash `password_hash()`), `role`.
- **`api/auth/`** (baru): `register.php`+`proses_register.php`, `login.php`+`proses_login.php` (`password_verify()`), `logout.php` (`session_destroy()`).
- **`api/includes/auth.php`** (baru): guard clause, ditaruh baris pertama di semua halaman "petugas-only": `tim/tambah.php`, `proses_tambah.php`, `edit.php`, `proses_edit.php`, `hapus.php`, `pertandingan/tambah.php`, `proses_tambah.php`, `proses_skor.php`, `proses_hapus.php`, `reset.php`.
- **Navbar dinamis** (`header.php`): tombol Create Match/Daftarkan Tim, kolom Aksi di Kelola Tim & Matchmaking Hub, dan section Submit Score — semua disembunyikan dengan `<?php if ($sudahLogin): ?>` kalau belum login. Area kanan navbar menampilkan nama petugas + Logout, atau link Login.
- **Halaman yang TETAP publik** (sengaja tidak dikunci, murni tampilan/baca): Home, Matchmaking Hub (lihat tabel), Kelola Tim (lihat tabel), Leaderboard.

⚠️ **Catatan penting soal Vercel:** fitur login ini memakai `$_SESSION` PHP biasa (sesuai materi Jobsheet 10), dan itu bekerja normal di Laragon. Tapi di Vercel, tiap request bisa dilayani instance server yang berbeda-beda (serverless), sehingga session **kadang tidak konsisten** — bisa saja tiba-tiba "ke-logout" sendiri. Ini bukan bug di kodenya, tapi keterbatasan arsitektur Vercel untuk session berbasis file. Untuk keperluan jobsheet/demo ke dosen, jalankan fitur login ini di **Laragon (lokal)**, bukan di deployment Vercel.

## Tahap 4: Konsep Jobsheet 11 (Keamanan Web Dasar)
- **`includes/helpers.php`** (baru): fungsi `e()` (`htmlspecialchars` + `ENT_QUOTES` + `UTF-8`). Seluruh `htmlspecialchars(...)` di halaman diganti `e(...)`; angka/ID/skor di-cast `(int)`.
- **`includes/csrf.php`** (baru): `csrf_token()`, `csrf_field()`, `csrf_verify()` (`random_bytes`, `hash_equals`). Di-require lewat `header.php` dan di semua file proses.
- **`csrf_field()`** ditambahkan ke semua form POST (termasuk Login, Register, Reset Data, Hapus Tim/Match, Submit Score).
- **`csrf_verify()`** dipanggil di semua `proses_*.php`, `tim/hapus.php`, dan `reset.php` (yang kini juga hanya menerima POST), setelah guard `auth.php`.
- **`auth/proses_login.php`**: `session_regenerate_id(true)` setelah login berhasil (anti session fixation).
- **`docs/security-checklist.md`** (baru): audit 5 kerentanan dengan bukti sebelum/sesudah dan cara pengujian.
- SQL injection & validasi input: diaudit ulang, sudah aman (tanpa perubahan kode).

## Tahap 5: Konsep Jobsheet 12 (Integrasi Modul, Relasi, Transaction, JOIN)
- **`sql/03_riwayat_mmr.sql`** (baru): tabel `riwayat_mmr` terhubung ke `tim` dan `pertandingan` lewat FOREIGN KEY (`ON DELETE CASCADE`, jadi hapus tim/match dan Reset Data tetap jalan). `01_vanguard_arena.sql` kini men-`DROP` tabel ini lebih dulu.
- **`pertandingan/proses_skor.php`** (diubah): update skor + MMR menang + MMR kalah + INSERT riwayat dibungkus satu **transaction** dengan `SELECT ... FOR UPDATE` pada match (mencegah MMR bertambah dua kali bila disubmit bersamaan). Gagal di langkah mana pun -> `rollBack()`, tidak ada data setengah jadi.
- **`tim/riwayat.php`** (baru): pilih tim -> tabel histori MMR (`JOIN riwayat_mmr` + `pertandingan`). Dikunci `auth.php`.
- **`header.php`** + **`index.php`**: menu & rute Riwayat MMR (hanya saat login).
- Keamanan Jobsheet 11 tetap berlaku (`e()`, `(int)` cast, guard login; halaman baru hanya GET sehingga tanpa form POST baru, form Submit Score tetap memakai `csrf_field()`).
- Belum diterapkan (tugas mandiri): pembatalan/koreksi skor yang memulihkan MMR.
