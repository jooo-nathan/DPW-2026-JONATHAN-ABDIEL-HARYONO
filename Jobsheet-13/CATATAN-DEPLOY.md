# Catatan Deploy ke Vercel

## Struktur
```
Jobsheet-13/
├── api/index.php      Satu-satunya serverless function (front controller/router)
├── app/               Seluruh kode aplikasi (halaman, includes, assets)
├── sql/  docs/        Skema database & dokumentasi
└── vercel.json
```

## Apa yang diperbaiki
1. **CSS tidak tampil**: `asset.php` membaca parameter `f`, tetapi `vercel.json`
   mengirim `file` -> selalu 404. Sekarang `api/index.php` menyajikan
   `/assets/css/*.css` dan `/assets/js/*.js` langsung (tanpa file `asset.php`).
2. **Runtime tidak valid**: `vercel-php@0.9.0` tidak ada (versi terbaru 0.7.4),
   deploy gagal. Sekarang memakai `vercel-php@0.7.4` (PHP 8.3).
3. **Semua halaman menampilkan Home**: rute `/(.*)` -> `index.php` membuat
   `/tim/list.php`, `/auth/login.php`, dll. tidak pernah jalan. Sekarang
   router meneruskan ke file di `app/` (daftar putih: `index`, `reset`, dan
   folder `auth`, `tim`, `pertandingan`; folder `includes` tidak bisa diakses).
4. **Login/CSRF hilang acak di serverless**: session file di `/tmp` tidak
   dibagi antar-instance. Sekarang di Vercel session disimpan di tabel
   PostgreSQL `sessions` (dibuat otomatis; lihat `sql/04_sessions.sql`).
5. `base_path()` memakai `APP_ROUTE` dari router, jadi link CSS/menu benar.
6. `config.php` juga membaca `DATABASE_URL`/`POSTGRES_URL` (otomatis dari
   integrasi Neon di Vercel). `koneksi.php` tidak lagi menampilkan detail error
   database ke pengunjung (ditulis ke log; set `APP_DEBUG=1` untuk debugging).
7. Perbaikan lain: Logout kini POST + CSRF; tombol Reset Data hanya untuk
   petugas login + konfirmasi, dan ikut mengosongkan `riwayat_mmr` dalam
   transaction; edit nickname tim ikut memperbarui match terkait; tim yang
   masih punya match belum selesai tidak bisa dihapus; `id` di-cast ke integer;
   cookie session `HttpOnly`/`SameSite`/`Secure`; header keamanan dasar.

## Cara deploy
1. Jalankan `sql/01`, `02`, `03` (urut) di SQL Editor Neon.
2. Vercel > Settings > Environment Variables: isi `DB_HOST`, `DB_PORT`,
   `DB_NAME`, `DB_USER`, `DB_PASS`, `DB_SSLMODE=require`
   (atau cukup `DATABASE_URL` dari Neon).
3. Pastikan Framework Preset = **Other**, Root Directory = folder yang berisi
   `vercel.json`. Lalu Redeploy.

## Jalankan lokal
`php -S localhost:8000 -t app`  lalu buka http://localhost:8000
