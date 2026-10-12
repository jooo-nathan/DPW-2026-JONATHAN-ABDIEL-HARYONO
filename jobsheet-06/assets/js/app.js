// =====================================================================
// app.js  (Jobsheet 6)  -  file JS UTAMA yang dimuat di SEMUA halaman
// Isi: 1) hamburger  2) hapus baris  3) filter tabel  4) validasi form
//      5) fungsi fetch generik muatDaftarData()
// Konsep yang sering ditanyakan: DOM, event listener, event delegation,
// async/await, fetch, JSON, try/catch/finally.
// =====================================================================


// ===== 1. Hamburger menu (JS-driven, menggantikan checkbox hack) =====
// Tujuan: membuka/menutup menu di layar kecil lewat klik tombol.
function initNavToggle() {
    // getElementById = ambil elemen berdasarkan id (id harus unik)
    const toggleBtn = document.getElementById("nav-toggle-btn");
    // querySelector = ambil elemen PERTAMA yang cocok dengan selector CSS
    const nav = document.querySelector("header nav");
    // Pengaman: kalau salah satu elemen tidak ada di halaman ini, berhenti.
    // Tanpa ini -> error "Cannot read properties of null" di Console.
    if (!toggleBtn || !nav) return;

    // addEventListener(jenisEvent, fungsi): jalankan fungsi saat tombol diklik
    toggleBtn.addEventListener("click", function () {
        // classList.toggle: kalau class "nav-open" belum ada -> dipasang,
        // kalau sudah ada -> dilepas. CSS (.nav-open) yang menampilkan menu.
        nav.classList.toggle("nav-open");
    });
}


// ===== 2. Konfirmasi hapus (front-end only, belum ke server) =====
// Memakai EVENT DELEGATION di document karena baris tabel sekarang
// dirender dinamis via fetch (lihat buku.js/anggota.js) sehingga
// tombol .btn-hapus belum tentu ada saat DOMContentLoaded.
// Ingat: listener dipasang SATU KALI di induk (document), bukan di tiap tombol.
function initHapusConfirm() {
    document.addEventListener("click", function (e) {
        // e.target = elemen yang BENAR-BENAR diklik pengguna
        console.log(e.target); // coba klik berbagai tempat di halaman, amati di Console

        // closest(".btn-hapus") = naik dari elemen yang diklik ke induknya
        // sampai ketemu .btn-hapus. Hasil null = yang diklik BUKAN tombol hapus.
        const btn = e.target.closest(".btn-hapus");
        if (!btn) return; // klik di tempat lain: abaikan

        // Cari baris <tr> tempat tombol ini berada
        const row = btn.closest("tr");
        // Ambil teks sel pertama (judul buku / nama) sebagai bahan pesan.
        // "?." = optional chaining: kalau td tidak ada, hasilnya undefined (tidak error)
        const nama = row ? row.querySelector("td")?.textContent : "data ini";
        // confirm() = dialog OK/Cancel bawaan browser, mengembalikan true/false
        const yakin = confirm("Yakin ingin menghapus \"" + nama + "\"?");
        if (yakin && row) {
            row.remove(); // hapus baris dari DOM (hanya tampilan; refresh = kembali lagi)
        }
    });
}


// ===== 3. Filter/pencarian tabel real-time =====
// Tujuan: menyaring baris tabel saat pengguna mengetik.
function initTableFilter() {
    const input = document.getElementById("search-input");
    const table = document.querySelector(".table-responsive table");
    if (!input || !table) return; // halaman ini tidak punya kotak pencarian

    // "keyup" = event saat tombol keyboard dilepas (tiap ketikan)
    input.addEventListener("keyup", function () {
        // toLowerCase() supaya pencarian tidak membedakan huruf besar/kecil
        const keyword = input.value.toLowerCase();
        // Ambil SEMUA baris di tbody (hasil berupa daftar/NodeList)
        const rows = table.querySelectorAll("tbody tr");
        rows.forEach(function (row) {
            // row.textContent = seluruh teks di baris (semua kolom, jadi
            // pencarian mencakup judul, pengarang, dst.)
            const teks = row.textContent.toLowerCase();
            // includes() = true bila teks mengandung keyword.
            // "" = tampil kembali (kembalikan ke bawaan), "none" = sembunyikan
            row.style.display = teks.includes(keyword) ? "" : "none";
        });
    });
}


// ===== 4. Validasi form (client-side) =====
// Menampilkan pesan error DI BAWAH input yang salah.
function tampilkanError(input, pesan) {
    hapusError(input); // hapus error lama dulu supaya tidak menumpuk
    const span = document.createElement("span"); // buat elemen baru <span>
    span.className = "error";       // beri class (gaya merah diatur di CSS .error)
    span.textContent = pesan;       // isi teks (textContent aman dari HTML injection)
    // insertAdjacentElement("afterend", ...) = sisipkan TEPAT SETELAH input
    input.insertAdjacentElement("afterend", span);
}

// Menghapus pesan error yang menempel di bawah input (jika ada).
function hapusError(input) {
    // nextElementSibling = elemen saudara tepat setelah input
    const next = input.nextElementSibling;
    // Hanya hapus kalau memang elemen itu pesan error (punya class "error")
    if (next && next.classList.contains("error")) {
        next.remove();
    }
}

// Pasang validasi pada form yang ber-id "form-tambah".
function initValidasiForm() {
    const form = document.getElementById("form-tambah");
    if (!form) return; // halaman tanpa form (mis. daftar): lewati

    // "submit" = event saat form dikirim (klik tombol Simpan)
    form.addEventListener("submit", function (e) {
        let valid = true; // bendera: dianggap valid sampai ada yang salah

        // --- Field judul/nama (satu kode melayani 2 form: name="judul" ATAU "nama") ---
        const judul = form.querySelector("[name='judul'], [name='nama']");
        // trim() membuang spasi di awal/akhir, jadi "   " dianggap kosong
        if (judul && judul.value.trim() === "") {
            tampilkanError(judul, "Field ini wajib diisi.");
            valid = false;
        } else if (judul) {
            hapusError(judul); // sudah benar -> bersihkan error lama
        }

        // --- Field pengarang (hanya ada di form buku) ---
        const pengarang = form.querySelector("[name='pengarang']");
        if (pengarang && pengarang.value.trim() === "") {
            tampilkanError(pengarang, "Pengarang wajib diisi.");
            valid = false;
        } else if (pengarang) {
            hapusError(pengarang);
        }

        // --- Field tahun: harus angka 1900-2026 ---
        const tahun = form.querySelector("[name='tahun']");
        if (tahun) {
            // value selalu berupa TEKS -> parseInt(teks, 10) mengubah ke angka basis 10
            const nilai = parseInt(tahun.value, 10);
            // isNaN = true bila hasilnya bukan angka (mis. input kosong)
            if (isNaN(nilai) || nilai < 1900 || nilai > 2026) {
                tampilkanError(tahun, "Tahun harus di antara 1900-2026.");
                valid = false;
            } else {
                hapusError(tahun);
            }
        }

        // --- Field stok: tidak boleh negatif ---
        const stok = form.querySelector("[name='stok']");
        if (stok) {
            const nilai = parseInt(stok.value, 10);
            if (isNaN(nilai) || nilai < 0) {
                tampilkanError(stok, "Stok tidak boleh negatif.");
                valid = false;
            } else {
                hapusError(stok);
            }
        }

        // Jika ada yang salah, BATALKAN pengiriman form (aksi bawaan submit)
        if (!valid) {
            e.preventDefault();
        }
    });
}


// ===== Titik awal: jalankan semua inisialisasi setelah HTML selesai dimuat =====
// DOMContentLoaded = event saat struktur HTML sudah siap (tanpa menunggu gambar).
document.addEventListener("DOMContentLoaded", function () {
    initNavToggle();
    initHapusConfirm();
    initTableFilter();
    initValidasiForm();
});


// ===== 5. Fungsi generik: ambil & tampilkan data dari file JSON mana pun =====
// namaFileJson: nama file di folder data/, mis. "buku.json"
// daftarKunci: urutan properti yang mau ditampilkan sebagai kolom
// "async" = fungsi ini boleh memakai "await" di dalamnya dan mengembalikan Promise.
async function muatDaftarData(namaFileJson, daftarKunci) {
    const tbody = document.querySelector(".table-responsive table tbody");
    const loading = document.getElementById("loading-indicator");
    if (!tbody) return; // bukan halaman daftar -> berhenti

    loading.style.display = "block"; // tampilkan tulisan "Memuat data..."
    tbody.innerHTML = "";            // kosongkan tabel (penting saat "Muat Ulang")

    // try: bagian yang BERPOTENSI GAGAL (jaringan, file hilang, JSON rusak)
    try {
        // Simulasi delay jaringan 3000 ms (3 detik) supaya loading terlihat.
        // await = tunggu sampai selesai dulu, halaman tetap tidak membeku.
        await new Promise((resolve) => setTimeout(resolve, 3000));

        // fetch() = minta data ke URL. Path "../data/" karena halaman daftar
        // berada di subfolder (buku/ atau anggota/), jadi naik satu folder.
        const res = await fetch("../data/" + namaFileJson);
        // res.ok = false bila status HTTP error (404 file tidak ada, dst.).
        // fetch TIDAK otomatis error untuk 404, jadi harus dicek manual.
        if (!res.ok) {
            // throw = lempar error -> langsung lompat ke blok catch
            throw new Error("Gagal mengambil data (status " + res.status + ")");
        }
        // res.json() = ubah teks JSON menjadi array/objek JavaScript (juga perlu await)
        const daftarData = await res.json();

        // Untuk SETIAP objek di array JSON -> buat satu baris tabel
        daftarData.forEach(function (item) {
            const tr = document.createElement("tr"); // buat <tr> kosong
            let html = "";                           // wadah teks HTML sel-sel
            // Untuk setiap kunci kolom -> tambah satu <td>.
            // item[kunci] = baca properti berdasar NAMA di variabel
            // (kunci "judul" -> item["judul"] sama dengan item.judul)
            daftarKunci.forEach(function (kunci) {
                html += "<td>" + item[kunci] + "</td>";
            });
            // Kolom terakhir: tombol Aksi. Class "btn-hapus" dipakai oleh event
            // delegation di initHapusConfirm() untuk mengenali tombol hapus.
            html +=
                "<td>" +
                "<button type=\"button\">Edit</button> " +
                "<button type=\"button\" class=\"btn-hapus\">Hapus</button>" +
                "</td>";
            tr.innerHTML = html;     // isi <tr> dengan HTML yang dirakit
            tbody.appendChild(tr);   // tempelkan baris ke tabel
        });
    // catch: jalan hanya jika di dalam try ada error (termasuk throw di atas)
    } catch (err) {
        // colspan = gabungkan sel selebar semua kolom (+1 untuk kolom Aksi)
        const kolom = daftarKunci.length + 1;
        tbody.innerHTML =
            "<tr><td colspan=\"" + kolom + "\">Gagal memuat data: " + err.message + "</td></tr>";
    // finally: SELALU jalan (berhasil maupun gagal) -> cocok untuk bersih-bersih
    } finally {
        loading.style.display = "none"; // sembunyikan "Memuat data..."
    }
}
