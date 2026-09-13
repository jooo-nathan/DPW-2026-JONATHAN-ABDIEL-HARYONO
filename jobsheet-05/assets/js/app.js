// ===== Hamburger menu (JS-driven, menggantikan checkbox hack) =====
function initNavToggle() {
    const toggleBtn = document.getElementById("nav-toggle-btn");
    const nav = document.querySelector("header nav");
    if (!toggleBtn || !nav) return;

    toggleBtn.addEventListener("click", function () {
        nav.classList.toggle("nav-open");
    });
}

// ===== Counter "Menampilkan X dari Y ..." =====
function updateCounter() {
    const table = document.querySelector(".table-responsive table");
    const counterEl = document.getElementById("filter-counter");
    if (!table || !counterEl) return;

    const rows = table.querySelectorAll("tbody tr");
    const total = rows.length;
    let tampil = 0;
    rows.forEach(function (row) {
        if (row.style.display !== "none") {
            tampil++;
        }
    });

    const label = counterEl.dataset.label || "data";
    counterEl.textContent = "Menampilkan " + tampil + " dari " + total + " " + label;
}

// ===== Konfirmasi hapus (front-end only, belum ke server) =====
function initHapusConfirm() {
    document.querySelectorAll(".btn-hapus").forEach(function (btn) {
        btn.addEventListener("click", function () {
            const row = btn.closest("tr");
            const nama = row ? row.querySelector("td")?.textContent : "data ini";
            const yakin = confirm("Yakin ingin menghapus \"" + nama + "\"?");
            if (yakin && row) {
                row.remove();
                updateCounter();
            }
        });
    });
}

// ===== Filter/pencarian tabel real-time =====
function initTableFilter() {
    const input = document.getElementById("search-input");
    const table = document.querySelector(".table-responsive table");
    if (!input || !table) return;

    input.addEventListener("keyup", function () {
        const keyword = input.value.toLowerCase();
        const rows = table.querySelectorAll("tbody tr");
        rows.forEach(function (row) {
            const judulSel = row.querySelector("td");
            const teksJudul = judulSel ? judulSel.textContent.toLowerCase() : "";
            row.style.display = teksJudul.includes(keyword) ? "" : "none";
        });
        updateCounter();
    });

    updateCounter(); // tampilkan hitungan awal saat halaman pertama dimuat
}

// ===== Validasi form (client-side) =====
function tampilkanError(input, pesan) {
    hapusError(input);
    const span = document.createElement("span");
    span.className = "error";
    span.textContent = pesan;
    input.insertAdjacentElement("afterend", span);
}

function hapusError(input) {
    const next = input.nextElementSibling;
    if (next && next.classList.contains("error")) {
        next.remove();
    }
}

// ===== Daftar aturan validasi =====
// Tiap aturan: selector field, fungsi cek (true = valid), dan pesan error kalau tidak valid
const daftarAturanValidasi = [
    {
        selector: "[name='judul'], [name='nama']",
        cek: function (nilai) {
            return nilai.trim() !== "";
        },
        pesan: "Field ini wajib diisi."
    },
    {
        selector: "[name='pengarang']",
        cek: function (nilai) {
            return nilai.trim() !== "";
        },
        pesan: "Pengarang wajib diisi."
    },
    {
        selector: "[name='tahun']",
        cek: function (nilai) {
            const angka = parseInt(nilai, 10);
            return !isNaN(angka) && angka >= 1900 && angka <= 2026;
        },
        pesan: "Tahun harus di antara 1900-2026."
    },
    {
        selector: "[name='stok']",
        cek: function (nilai) {
            const angka = parseInt(nilai, 10);
            return !isNaN(angka) && angka >= 0;
        },
        pesan: "Stok tidak boleh negatif."
    }
];

function initValidasiForm() {
    const form = document.getElementById("form-tambah");
    if (!form) return;

    form.addEventListener("submit", function (e) {
        let valid = true;

        daftarAturanValidasi.forEach(function (aturan) {
            const input = form.querySelector(aturan.selector);
            if (!input) return; // field ini tidak ada di form (misal, form anggota tidak punya "stok")

            if (aturan.cek(input.value)) {
                hapusError(input);
            } else {
                tampilkanError(input, aturan.pesan);
                valid = false;
            }
        });

        if (!valid) {
            e.preventDefault();
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    initNavToggle();
    initHapusConfirm();
    initTableFilter();
    initValidasiForm();
});