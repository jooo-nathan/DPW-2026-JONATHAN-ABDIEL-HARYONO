// =====================================================================
// buku.js  -  skrip KHUSUS halaman buku/list.html
// Dimuat SETELAH app.js, jadi fungsi muatDaftarData() sudah tersedia.
// =====================================================================

// CATATAN (sisa percobaan, tidak dihapus agar kode Anda tetap utuh):
// Fungsi di bawah adalah versi LAMA (sebelum dibuat generik di app.js).
// Fungsi ini TIDAK PERNAH DIPANGGIL, karena halaman sekarang memakai
// muatDaftarData() dari app.js. Bila ditanya: ini kode yang digantikan
// fungsi generik (versi lama menulis kolom satu per satu: buku.judul, dst.).

// Mengambil & menampilkan Daftar Buku secara asinkron dari data/buku.json
async function muatDaftarBuku() {
    const tbody = document.querySelector(".table-responsive table tbody");
    const loading = document.getElementById("loading-indicator");
    if (!tbody) return;

    loading.style.display = "block";
    tbody.innerHTML = "";

    try {
        // simulasi delay jaringan agar loading indicator terlihat
        await new Promise((resolve) => setTimeout(resolve, 600));

        const res = await fetch("../data/buku.json");
        if (!res.ok) {
            throw new Error("Gagal mengambil data (status " + res.status + ")");
        }
        const daftarBuku = await res.json();

        daftarBuku.forEach(function (buku) {
            const tr = document.createElement("tr");
            // kolom ditulis manual (hardcode) -> inilah kelemahan versi lama
            tr.innerHTML =
                "<td>" + buku.judul + "</td>" +
                "<td>" + buku.pengarang + "</td>" +
                "<td>" + buku.tahun + "</td>" +
                "<td>" + buku.stok + "</td>" +
                "<td>" +
                "<button type=\"button\">Edit</button> " +
                "<button type=\"button\" class=\"btn-hapus\">Hapus</button>" +
                "</td>";
            tbody.appendChild(tr);
        });
    } catch (err) {
        tbody.innerHTML =
            "<tr><td colspan=\"5\">Gagal memuat data: " + err.message + "</td></tr>";
    } finally {
        loading.style.display = "none";
    }
}

// --- PEMANGGIL #1 ---
// Saat HTML siap: muat data buku + pasang tombol "Muat Ulang".
document.addEventListener("DOMContentLoaded", function () {
    // Urutan kunci = urutan kolom tabel (harus sama dengan <th> di list.html)
    const daftarKolomBuku = ["judul", "pengarang", "tahun", "stok", "kategori"];
    muatDaftarData("buku.json", daftarKolomBuku); // ambil JSON & isi tabel
    const btnMuatUlang = document.getElementById("btn-muat-ulang");
    if (btnMuatUlang) { // pengaman: tombol ada di halaman ini?
        btnMuatUlang.addEventListener("click", function () {
            muatDaftarData("buku.json", daftarKolomBuku); // ulangi fetch
        });
    }
});

// --- PEMANGGIL #2 (DUPLIKAT dari #1) ---
// CATATAN PENTING: blok ini isinya SAMA dengan pemanggil #1, sehingga
// saat halaman dibuka data dimuat DUA KALI dan tombol Muat Ulang punya
// DUA listener. Tabel tetap tampil benar karena tbody dikosongkan di awal
// tiap pemuatan, tapi ini pemborosan. Perbaikan: hapus salah satu blok.

// Halaman Daftar Buku — tinggal panggil fungsi generik dari app.js
// document.addEventListener("DOMContentLoaded", function () {
//     const daftarKolomBuku = ["judul", "pengarang", "tahun", "stok", "kategori"];

//     muatDaftarData("buku.json", daftarKolomBuku);

//     const btnMuatUlang = document.getElementById("btn-muat-ulang");
//     if (btnMuatUlang) {
//         btnMuatUlang.addEventListener("click", function () {
//             muatDaftarData("buku.json", daftarKolomBuku);
//         });
//     }
// });
