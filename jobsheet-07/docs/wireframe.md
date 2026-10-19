# Wireframe & User Flow — Vanguard Arena

Sub-CPMK: Merancang UI/UX aplikasi (proyek).

Halaman yang sudah ada (Home, Matchmaking Hub, Create Match, Kelola Tim, Daftarkan Tim — Jobsheet 1-3) belum mencakup Login/Register Petugas, Submit Score, dan Riwayat MMR. Dokumen ini merancang wireframe halaman-halaman tersebut sebelum diimplementasikan di jobsheet berikutnya.

## Aktor
- **Tamu**: hanya bisa melihat Home, Leaderboard, dan Matchmaking Hub tanpa login.
- **Petugas**: login untuk membuat/menghapus match, mendaftarkan/mengubah tim, menginput skor, dan melihat Riwayat MMR.

## Konsep Tata Letak (dipakai di semua halaman)
Sidebar vertikal di kiri berisi logo dan menu yang dikelompokkan (Arena, Tim, Akun). Konten halaman ada di kanan. Di layar ponsel, sidebar berubah menjadi top bar dengan tombol hamburger.

```
+----------------+--------------------------------------------------+
| VANGUARD       |  Judul Halaman                    [ Tombol Aksi ] |
| ARENA          |  Deskripsi singkat                                |
|                |--------------------------------------------------|
| Arena          |                                                  |
|  > Home        |   Kartu konten (section putih, sudut diagonal)   |
|  > Leaderboard |                                                  |
|  > Matchmaking |                                                  |
|  > Create Match|                                                  |
|                |                                                  |
| Tim            |                                                  |
|  > Kelola Tim  |                                                  |
|  > Daftarkan   |                                                  |
|                |                                                  |
| Akun           |                                                  |
|  > Login       |                                                  |
|  > Register    |--------------------------------------------------|
|                |  footer                                          |
+----------------+--------------------------------------------------+
```

## User Flow — Membuat Match
```
[Petugas Login] -> [Menu "Create Match"] -> [Pilih Game]
        -> [Pilih Team 1 (hanya tim dari game itu)]
        -> [Pilih Team 2 atau "Tanpa lawan"]
        -> [Buat match]
        -> tanpa lawan: status Waiting | dengan lawan: status In-Progress
        -> [Kembali ke Matchmaking Hub]
```

## User Flow — Submit Score
```
[Matchmaking Hub] -> [Cari match In-Progress] -> [Isi skor Team 1 & Team 2]
        -> skor seri? -> [Tolak, minta isi ulang]
        -> [Submit] -> [Tim menang +25 MMR, tim kalah -15 MMR]
        -> [Status match jadi Completed] -> [Leaderboard ikut berubah]
```

## Wireframe: Halaman Login
```
+-----------------------------+------------------------------------+
|  Masuk sebagai petugas      |  Login petugas                     |
|                             |                                    |
|  Tamu tetap bisa melihat    |  Username [______________]         |
|  Leaderboard tanpa login.   |  Password [______________]         |
|                             |                                    |
|  | Membuat & hapus match    |  [ Masuk ]                         |
|  | Mendaftarkan tim         |                                    |
|  | Menginput skor           |  Belum punya akun? Daftar di sini  |
+-----------------------------+------------------------------------+
```

## Wireframe: Home (Dashboard)
```
+--------------------------------------------------------------+
|  Selamat Datang di Vanguard Arena        | Sedang berlangsung |
|  Platform matchmaking esports...         |  Mobile Legends    |
|  [Create Match] [Daftarkan Tim]          |  RGE vs KRN        |
|                                          |  PUBG Mobile       |
|                                          |  WLF vs SPT        |
+--------------------------------------------------------------+
|  Ringkasan                                                   |
|  [Tim Terdaftar] [Match Live] [Match Selesai] [Peringkat 1]  |
+--------------------------------------------------------------+
|  Global Leaderboard                                          |
|  Rank | Nickname | Nama Tim | Game | MMR                     |
+--------------------------------------------------------------+
```

## Wireframe: Form Submit Score
```
+--------------------------------------+
|  Submit Score                        |
|--------------------------------------|
|  Match    : [ dropdown, hanya         |
|               status In-Progress ]    |
|  Skor Team 1 : [____]                |
|  Skor Team 2 : [____]                |
|  Catatan: skor tidak boleh seri.     |
|                                      |
|          [  Submit score  ]          |
+--------------------------------------+
```

## Wireframe: Riwayat MMR per Tim
```
+--------------------------------------------------+
|  Riwayat MMR                                     |
|  Pilih tim: [ NVS - Nova Strike (Valorant) v ]   |
|--------------------------------------------------|
|  Waktu      | Match     | Skor   | Hasil | MMR   |
|  12 Okt     | NVS v AUR | 13 - 9 | Menang| +25   |
|  10 Okt     | NVS v ... | 7 - 13 | Kalah | -15   |
+--------------------------------------------------+
```

## Konsistensi dengan Desain yang Sudah Berjalan
- Warna dan tipografi mengikuti `assets/css/style.css` (indigo, lavender, volt; font Oxanium untuk judul dan Hanken Grotesk untuk teks).
- Aturan game: tim yang bertanding harus bermain game yang sama (Valorant, Mobile Legends, Dota 2, PUBG Mobile).
- Edge case yang perlu ditangani saat implementasi: skor seri ditolak, match tanpa lawan tidak bisa diberi skor, dan nickname + game harus unik.
