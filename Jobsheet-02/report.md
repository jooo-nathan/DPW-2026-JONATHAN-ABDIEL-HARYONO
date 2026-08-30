# JOBSHEET 2

## A. MENCOBA MEMBUKA `index.html` DI BROWSER

### 1. Beranda (`index.html`)

![Beranda](img/Beranda2.png)

### 2. Daftar Buku

![DaftarBuku](img/DaftarBuku.png)

### 3. Tambah Buku

![TambahBuku](img/TambahBuku.png)

### 4. Daftar Anggota

![DaftarAnggota](img/DaftarAnggota.png)

### 5. Tambah Anggota

![TambahAnggota](img/TambahAnggota.png)

### 6. Mencoba membuka *DevTools* browser

![Inspect](img/Inspect.png)

## B. MEMBANDINGKAN `index.html` PADA JOBSHEET 1 DENGAN `index.html` PADA JOBSHEET 2

<img src="img/Beranda1.png" width=45%>
<img src="img/Beranda2.png" width=45%>

Terlihat perbedaan yang sangat besar, mulai dari warna, font, hingga peletakannya.

## C. LATIHAN TAMBAHAN

### 1. GANTI WARNA

- Mengganti warna #1d5b8a (warna biru tema) di seluruh file `style.css` dengan warna #4B0082 (indigo).

![Latihan1.1](img/Latihan1.1.png)

- Hasil setelah diganti :

![Latihan1.2](img/Latihan1.2.png)

### 2. MENAMBAHKAN KOLOM KEEMPAT DI STATISTIK BERANDA

- Menambahkan article baru di `index.html`

![Latihan2.1](img/Latihan2.1.png)

- Modifikasi `style.css`

![Latihan2.2](img/Latihan2.2.png)

- Hasil setelah diganti :

![Latihan2.3](img/Latihan2.3.png)

### 3. MEMBUAT TOMBOL KETIGA DI TABEL

- Menambahkan tombol "Detail" di antara "Edit" dan "Hapus"

![Latihan3.1](img/Latihan3.1.png)

- Hasil setelah diganti :

Warna tidak sesuai, karena pewarnaan button pada `sytle.css` membedakan berdasarkan urutan tombolnya, bukan berdasarkan teks/isinya, sehingga hanya tombol pertama dan terakhir saja yang diberi warna kustom.

![Latihan3.2](img/Latihan3.2.png)

- Perbaikan dengan memberi class khusus

![Latihan3.3](img/Latihan3.3.png)

![Latihan3.4.1](img/Latihan3.4.1.png)

- Hasil setelah diperbaiki (final) :

![Latihan3.5](img/Latihan3.5.png)

### 4. UJI RESPONSIVITAS SEDERHANA

- Tampilan awal (desktop) :

![Latihan4.1](img/Latihan4.1.png)

- Tampilan yang lebih sempit (menyerupai lebar layar HP) :

![Latihan4.2](img/Latihan4.2.png)

`flex-wrap: wrap` pada navbar secara otomatis mulai memindahkan daftar menu ke baris di bawah judul aplikasi pada lebar layar sekitar 615 px (menggunakan extension chrome "page ruler"). Hal ini berguna agar navigasi tetap rapi dan tidak terpotong.

