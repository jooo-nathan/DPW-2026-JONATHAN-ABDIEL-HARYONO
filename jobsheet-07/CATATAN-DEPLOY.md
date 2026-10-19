# Catatan Penyesuaian untuk Vercel

Proyek aslinya berstruktur datar (semua file PHP di root). Vercel mewajibkan
kode yang dijalankan sebagai program ada di dalam folder `api/`, jadi
perubahan berikut dilakukan **khusus untuk keperluan deploy** (bukan bagian
dari materi Jobsheet 13):

1. **Semua folder PHP** (`includes/`, `auth/`, `tim/`, `pertandingan/`,
   `assets/`, `index.php`, `reset.php`) dipindah ke dalam `api/`.
   `docs/`, `sql/`, dan `README.md` tetap di luar (bukan kode yang
   dijalankan). Tidak ada isi file PHP yang diubah logikanya — murni pindah
   folder saja, karena semua path internal proyek ini sudah ditulis relatif
   (lewat `base_path()` di `includes/helpers.php`), jadi tetap benar di
   lokasi barunya.
2. **`api/asset.php`** (baru): di Vercel, file `.css`/`.js` di dalam `api/`
   tidak otomatis bisa diakses langsung. File ini yang membacakannya.
3. **`vercel.json`** (baru): mengatur folder mana yang dijalankan sebagai
   PHP, dan mengarahkan alamat `/assets/...` ke `asset.php`.
4. **`includes/koneksi.php`**: ditambah penyesuaian khusus Neon (menambahkan
   `options=endpoint=...` ke DSN). Tanpa ini, koneksi ke Neon dari Vercel
   gagal dengan pesan "Endpoint ID is not specified" — keterbatasan versi
   library PostgreSQL yang dipakai runtime PHP di Vercel.

## Cara deploy
1. Jalankan `sql/01_vanguard_arena.sql`, `02_users.sql`, `03_riwayat_mmr.sql`
   di SQL Editor Neon (urut sesuai nomor).
2. Di Vercel: Settings > Environment Variables, isi `DB_HOST`, `DB_PORT`,
   `DB_NAME`, `DB_USER`, `DB_PASS` (dari connection string Neon), dan
   `DB_SSLMODE` = `require`.
3. Deploy. Sudah dites lokal: halaman utama, asset CSS, proteksi path
   traversal di `asset.php`, dan alur registrasi (CSRF token + simpan ke
   database) — semuanya berjalan normal setelah dipindah ke struktur ini.
