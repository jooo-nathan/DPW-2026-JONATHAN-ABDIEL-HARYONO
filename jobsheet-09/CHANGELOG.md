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
