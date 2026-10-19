# Wireframe - Vanguard Arena (versi akhir)

## Tata letak (semua halaman)
```
+----------------+--------------------------------------------------+
| Vanguard       |                                                  |
| Arena          |   Kartu konten (section putih, sudut diagonal)   |
| ____           |                                                  |
| Arena          |   Judul halaman           [ Tombol aksi ]        |
|  > Home        |   Tabel / form / pesan                           |
|  > Leaderboard |                                                  |
|  > Matchmaking |                                                  |
|  > Create Match|                                                  |
| Tim            |                                                  |
|  > Kelola Tim  |                                                  |
|  > Daftarkan   |                                                  |
|  > Riwayat MMR |                                                  |
| -------------- |                                                  |
| Masuk sebagai  |--------------------------------------------------|
| [Logout]       |  footer                                          |
+----------------+--------------------------------------------------+
```
Layar <= 900px: sidebar menjadi bar atas, menu dibuka lewat tombol hamburger.

## Home
```
+----------------------------------------------+-----------------+
| Selamat Datang di Vanguard Arena             | Sedang          |
| tagline                                      | berlangsung     |
| [Create Match] [Daftarkan Tim]               | RGE vs KRN ...  |
+----------------------------------------------+-----------------+
| [Registered Teams] [Live Matches] [#1 Team]                    |
+----------------------------------------------------------------+
| Global Leaderboard  (cari nickname)                            |
| Rank | Nickname | Game | MMR                                   |
+----------------------------------------------------------------+
| Reset Data (petugas)                                           |
+----------------------------------------------------------------+
```

## Alur Create Match (2 langkah tanpa JavaScript)
```
[Pilih game] -> [Lanjut] -> [Team 1 + Team 2 (opsional) dari game itu]
   -> tanpa lawan: Waiting | dengan lawan: In-Progress
```

## Alur Submit Score
```
[Match In-Progress] -> [Isi skor] -> seri? tolak
   -> BEGIN: update match, MMR menang +25, MMR kalah -15, catat riwayat -> COMMIT
```
