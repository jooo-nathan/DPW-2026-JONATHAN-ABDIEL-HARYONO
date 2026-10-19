# Jobsheet 13 - Deployment & Dokumentasi (Vanguard Arena)

Sub-CPMK: Mendeploy dan mendokumentasikan aplikasi.

Ini **snapshot akhir** proyek Vanguard Arena setelah Jobsheet 1-12, siap didemokan pada UAS.

## Deskripsi
Vanguard Arena adalah aplikasi web matchmaking esports berbasis tim: mendaftarkan tim, membuat pertandingan, menginput skor, memperbarui MMR otomatis, dan menampilkan Global Leaderboard, dengan login untuk petugas.

**Stack:** HTML5, CSS3, JavaScript, PHP native, PostgreSQL (PDO_PGSQL).
**Game yang didukung:** Valorant, Mobile Legends, Dota 2, PUBG Mobile (khusus game beregu).

## Yang baru di Jobsheet 13
- `includes/config.php`: kredensial database dibaca dari **environment variable** (nilai cadangan untuk lokal).
- `includes/koneksi.php`: memakai `config.php`, tidak ada kredensial di dalam kode.
- `includes/helpers.php`: fungsi `base_path()` menghitung path relatif dari kedalaman folder, jadi proyek jalan di root maupun di subfolder.
- Dokumentasi: README ini, `docs/manual-pengguna.md`, `docs/security-checklist.md`, `docs/wireframe.md`.

## ERD
```
tim                      pertandingan            riwayat_mmr              users
----                     -------------           -----------              -----
id (PK)                  id (PK)                 id (PK)                  id (PK)
nickname (1-3)           game                    tim_id (FK -> tim)       nama
nama_tim                 tim1 (nickname)         pertandingan_id (FK)     username (UQ)
jumlah_anggota           tim2 (NULL = Waiting)   hasil (Menang/Kalah)     password (hash)
game                     skor1, skor2            perubahan (+25 / -15)    role
mmr (default 1000)       status                  mmr_sesudah
UQ(nickname, game)                               dicatat_pada
UQ(nama_tim, game)
```
`riwayat_mmr` memakai `ON DELETE CASCADE`: menghapus tim atau match ikut menghapus catatan riwayatnya.

## Fitur per role
| Fitur | Tamu | Petugas |
|---|---|---|
| Home, statistik, Global Leaderboard | Ya | Ya |
| Matchmaking Hub, Kelola Tim (lihat) | Ya | Ya |
| Daftarkan / Edit / Hapus Tim | Tidak | Ya |
| Create Match, Hapus Match | Tidak | Ya |
| Submit Score (transaction MMR) | Tidak | Ya |
| Riwayat MMR | Tidak | Ya |
| Reset Data | Tidak | Ya |

## Instalasi
1. Salin folder ini ke komputer/server yang punya PHP 8+ (ekstensi `pdo_pgsql`) dan PostgreSQL.
2. Buat database dan impor skema **berurutan** (`riwayat_mmr` mereferensikan `tim` dan `pertandingan`):
   ```bash
   createdb vanguard_arena
   psql -d vanguard_arena -f sql/01_vanguard_arena.sql
   psql -d vanguard_arena -f sql/02_users.sql
   psql -d vanguard_arena -f sql/03_riwayat_mmr.sql
   ```
   Peringatan: `01_vanguard_arena.sql` memuat `DROP TABLE`; menjalankannya ulang mengosongkan data.
3. Atur koneksi lewat environment variable (semua opsional; default untuk lokal):

   | Variabel | Default | Keterangan |
   |---|---|---|
   | `DB_HOST` | `localhost` | alamat server database |
   | `DB_PORT` | `5432` | port |
   | `DB_NAME` | `vanguard_arena` | nama database |
   | `DB_USER` | `postgres` | user |
   | `DB_PASS` | `postgres` | password |
   | `DB_SSLMODE` | `prefer` | pakai `require` untuk database online |

   Contoh produksi:
   ```bash
   DB_HOST=127.0.0.1 DB_USER=produser DB_PASS=rahasia DB_SSLMODE=require php -S localhost:8000
   ```
4. Jalankan:
   - **PHP built-in server:** `php -S localhost:8000`, lalu buka `http://localhost:8000/index.php`
   - **Laragon / Apache:** arahkan virtual host ke folder ini, atau taruh di subfolder `www` (path relatif dihitung otomatis).
5. Buka halaman **Login petugas**, pilih **Daftar di sini**, buat akun petugas pertama.

## Struktur folder
```
Jobsheet-13/
├── index.php              Home (statistik + leaderboard dari database)
├── reset.php              Kosongkan data (POST + CSRF)
├── includes/              config, koneksi, header (sidebar), footer, auth, csrf, helpers, games
├── assets/css, assets/js  tema Volt Lobby + interaksi
├── tim/                   CRUD tim, riwayat MMR
├── pertandingan/          list, buat, hapus, submit skor
├── auth/                  register, login, logout
├── sql/                   skema 01-03 (jalankan berurutan)
└── docs/                  wireframe, security-checklist, manual-pengguna
```

## Dokumen pendukung
- [`docs/wireframe.md`](docs/wireframe.md): rancangan UX
- [`docs/security-checklist.md`](docs/security-checklist.md): audit keamanan
- [`docs/manual-pengguna.md`](docs/manual-pengguna.md): panduan pengguna akhir

## Catatan
- Seluruh berkas PHP lolos `php -l`. Sudah diuji end-to-end (PHP 8.3 + PostgreSQL 16): register, login, tambah tim, tolak duplikat, buat match, submit skor (MMR 1025/985), riwayat, hapus, proteksi CSRF, guard login, dan koneksi lewat environment variable.
- Untuk presentasi UAS, siapkan alasan desain: kenapa `UNIQUE(nickname, game)`, kenapa update MMR memakai **transaction**, dan kenapa kredensial dipisah ke environment variable.
