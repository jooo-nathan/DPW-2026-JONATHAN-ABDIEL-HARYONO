# JOBSHEET 4

## LATIHAN TAMBAHAN

### 1. Menggambar wireframe halaman baru

+---------------------------------------------------------------------------------+
|                                   SIMPUS-Mini                                   |
|                                                                                 |
|                                                                                 |
|                   Selamat Datang di Sistem Perpustakaan Mini                    |
|                                                                                 |
|                       Aplikasi sederhana untuk mengelola                        |
|                       data buku dan anggota perpustakaan                        |
|                                                                                 |
|                              Apa panggilan anda?                                |
|                               [______________]                                  |
|                                                                                 |
|                             [ Mulai menjelajah! ]                               |
|                                                                                 |
+---------------------------------------------------------------------------------+

### 2. Membuat user flow baru

Userflow untuk "Petugas mencari anggota yang tunggakannya sudah lewat jatuh tempo."

[Petugas Login] -> [Dashboard] -> [Pilih menu "Peminjaman"]
    -> [Pilih menu "Daftar Peminjaman"] -> [Filter berdasarkan "Dipinjam"]
    -> [Bandingkan tanggal pinjam dengan tanggal hari ini]
    -> [Tandai anggota yang sudah jatuh tempo]

### 3. Mengidentifikasi edge tambahan

Terjadi verifikasi terhadap peminjaman tersebut. Sistem akan menanyakan apakah yang petugas lakukan sudah benar (meminjamkan 2 buah buku yang sama pada seorang anggota). Jika petugas memverifikasi kebenarannya, maka diperbolehkan (karena seorang anggota meminjam 2 buku yang sama adalah hal yang wajar, mungkin untuk dipinjamkan temannya atau semacamnya). Jika petugas ternyata salah memilih buku, maka petugas dapat memilih "Tidak" agar kembali ke dashboard kembali.

### 4. Implementasi wireframe Login sebagai HTML statis

Menambahkan login page <a href="login.html">disini</a>.
