# Manual Pengguna - Vanguard Arena

Panduan ini untuk pemakai aplikasi (bukan developer). Tidak perlu paham kode.

## Siapa yang bisa melakukan apa?
- **Tamu** (tanpa login): melihat Home, Global Leaderboard, Matchmaking Hub, dan daftar tim.
- **Petugas** (sudah login): semua yang bisa dilakukan tamu, ditambah membuat match, mendaftarkan dan mengubah tim, menginput skor, dan melihat Riwayat MMR.

## Membuat akun dan login
1. Klik **Login petugas** di bagian bawah sidebar, lalu pilih **Daftar di sini**.
2. Isi nama lengkap, username, dan password (minimal 6 karakter), lalu klik daftar.
3. Kembali ke halaman login, masukkan username dan password.
4. Nama Anda tampil di bawah sidebar ("Masuk sebagai ..."). Klik **Logout** untuk keluar.

## Mendaftarkan tim
1. Menu **Tim > Daftarkan Tim**.
2. Isi Nickname (1-3 karakter, contoh RRQ), Nama Tim (3-30 karakter), Jumlah Anggota (boleh kosong), dan Game.
3. Klik **Register**. Tim baru mulai dari **1000 MMR**.
4. Nickname + Game harus unik. Nickname yang sama boleh dipakai bila game-nya berbeda.

## Mengubah atau menghapus tim
Menu **Tim > Kelola Tim**. Klik **Edit** untuk mengubah, atau **Hapus** lalu konfirmasi. Kotak pencarian mencari berdasarkan nickname, dan tabel dibagi per halaman (5 tim per halaman).

## Membuat match
1. Menu **Arena > Create Match**, pilih game lalu klik **Lanjut**.
2. Pilih Team 1. Team 2 boleh dikosongkan.
3. Tanpa lawan, status match **Waiting**. Dengan lawan, status **In-Progress**.
4. Hanya tim dari game yang sama yang muncul di daftar pilihan.

## Menginput skor
1. Menu **Arena > Matchmaking Hub**, bagian **Submit Score** di bawah tabel.
2. Pilih match yang In-Progress, isi skor kedua tim. **Skor tidak boleh seri.**
3. Tim menang **+25 MMR**, tim kalah **-15 MMR**, dan status match menjadi **Completed**.

## Riwayat MMR
Menu **Tim > Riwayat MMR**, pilih tim, lalu klik **Tampilkan**. Tabel menunjukkan waktu, lawan, skor, hasil, perubahan MMR, dan MMR sesudahnya.

## Reset data
Di bagian bawah Home (hanya petugas). Tombol **Reset Data** menghapus **semua** tim, match, dan riwayat. Tidak bisa dibatalkan.

## Pesan error yang sering muncul
| Pesan | Penyebab | Solusi |
|---|---|---|
| Nickname + game sudah terdaftar | Tim dengan kombinasi itu sudah ada | Ganti nickname atau game |
| Skor tidak boleh seri | Skor kedua tim sama | Isi skor yang berbeda |
| Token CSRF tidak valid | Halaman terlalu lama terbuka | Muat ulang halaman, ulangi aksi |
