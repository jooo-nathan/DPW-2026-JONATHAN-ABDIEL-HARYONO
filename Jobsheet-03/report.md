# JOBSHEET 3

## A. PENGUJIAN MANDIRI DI BROWSER

### 1. Memmbuka `index.html` di browser

![Uji1](img/Uji1.png)

### 2. Membuka *DevTools*

![Uji2](img/Uji2.png)

### 3. Mengaktifkan mode responsif

![Uji3](img/Uji3.png)

### 4. Mengubah lebar layar secara bertahap

#### - Percobaan mengubah kartu statistik

Kartu statistik berubah menjadi 2 kolom setelah melewati lebar 768px

![Uji4.1.1](img/Uji4.1.1.png)

Kartu statistik berubah menjadi 1 kolom setelah melewati lebar 480px

![Uji4.1.2](img/Uji4.1.2.png)

#### - Percobaan mengubah menu navigasi

Menu navigasi berubah menajadi logo seperti burger saat melewati lebar 480px

![uji4.2](img/Uji4.2.png)

#### - Percobaan menu vertikal

Menu terbuka vertikal di bawah header

![Uji4.3](img/Uji4.3.png)

#### - Percobaan fitur scroll daftar buku

Tabel pada list buku menjadi responsif (bisa discroll secara horizontal)

![Uji4.4](img/Uji4.4.png)

## B. LATIHAN TAMBAHAN

### 1. Menambahkan breakpoint baru

Mengubah max-width dari 1000px menjadi 1600px

![Latihan1](img/Latihan1.png)

Sebelum diubah :

![Latihan1.1](img/Latihan1.1.png)

Setelah diubah : 

![Latihan1.2](img/Latihan1.2.png)

Dampaknya adalah layar utama menjadi lebih lebar dari sebelumnya, bertambah 600px lebih lebar.

### 2. Mengubah breakpoint tablet

Mengubah breakpoint tablet dari 768px menjadi 900px

![Latihan2](img/Latihan2.png)

Sebelum diubah : 

![Latihan2.1](img/Latihan2.1.png)

Setelah diubah : 

![Latihan2.2](img/Latihan2.2.png)

Lebar maksimal berubah dari 768px menjadi 900px. Buktinya adalah sebelum diubah, article akan menyesuaikan menjadi 2 kolom setelah melewati batas width 768 pixel. Setelah diubah, article langsung menyesuaikan menjadi 2 kolom setelah melewati batas width 900 pixel.

### 3. Menerapkan pola table-responsive ke elemen lain



### 4. Mengubah posisi ikon hamburger

![Latihan4](img/Latihan4.png)

Saat `.nav-toggle-label` dipindahkan ke setelah `<nav>`, CSS `.nav-toggle:checked ~ nav` masih tetap bekerja. Hal ini terjadi karena elemen sumber pencarian combinator ~ adalah `<input id="nav-toggle">` yang posisinya masih berada di sebelum `<nav>`. Fitur menu baru akan gagal/tidak bekerja apabila tag `<input class="nav-toggle">` dipindahkan ke posisi setelah `<nav>`.

### 5. Membandingkan dengan pendekatan mobile-first

![Latihan5](img/Latihan5.png)

Dalam pendekatan Mobile-First, CSS ditulis mulai dari tampilan layar terkecil (HP) secara default tanpa media query, lalu menggunakan @media (min-width: ...) secara bertahap untuk menambahkan atau melebarkan tata letak saat layar membesar ke ukuran tablet dan desktop.