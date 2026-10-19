// ===== Menu hamburger (digerakkan JS, menggantikan checkbox hack Jobsheet 3-4) =====
function initNavToggle() {
    const tombol = document.getElementById("nav-toggle-btn");
    const nav = document.querySelector("aside nav");
    if (!tombol || !nav) return;

    tombol.addEventListener("click", function () {
        const terbuka = nav.classList.toggle("nav-open");
        tombol.setAttribute("aria-expanded", terbuka);
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

// ===== Konfirmasi hapus (front-end only) =====
// Baris hanya hilang dari tampilan; data di $_SESSION tetap ada, jadi muncul lagi saat refresh.
// Penghapusan sungguhan di server baru dipelajari di jobsheet berikutnya.
function initHapusConfirm() {
    document.querySelectorAll(".btn-hapus").forEach(function (btn) {
        btn.addEventListener("click", function () {
            const row = btn.closest("tr");
            const sel = row.querySelectorAll("td");
            // contoh teks: "NVS - Nova Strike" (tim) atau "Valorant - NVS" (match)
            const nama = sel.length > 1
                ? sel[0].textContent + " - " + sel[1].textContent
                : "data ini";

            const yakin = confirm("Yakin ingin menghapus \"" + nama + "\"?");
            if (yakin) {
                row.classList.add("hilang"); // memudar dulu (lihat style.css)
                setTimeout(function () {
                    row.remove();
                    updateCounter();
                }, 250);
            }
        });
    });
}

// ===== Filter/pencarian tabel real-time (berdasarkan kolom pertama) =====
function initTableFilter() {
    const input = document.getElementById("search-input");
    const table = document.querySelector(".table-responsive table");
    if (!input || !table) return;

    input.addEventListener("keyup", function () {
        const keyword = input.value.toLowerCase();
        const rows = table.querySelectorAll("tbody tr");
        rows.forEach(function (row) {
            const kolomPertama = row.querySelector("td");
            const teks = kolomPertama ? kolomPertama.textContent.toLowerCase() : "";
            row.style.display = teks.includes(keyword) ? "" : "none";
        });
        updateCounter();
    });

    updateCounter(); // tampilkan hitungan awal saat halaman pertama dimuat
}

// ===== Pesan error & sukses pada form =====
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

// Mengambil nama game dari <optgroup> tempat sebuah <option> tim berada
function gameDariTim(select) {
    const opsi = select.options[select.selectedIndex];
    return opsi && opsi.parentElement.tagName === "OPTGROUP" ? opsi.parentElement.label : "";
}

// ===== Daftar aturan validasi =====
// Tiap aturan: selector field, fungsi cek (true = valid), dan pesan error kalau tidak valid.
// Parameter cek: (nilai, form, input). Satu field boleh punya beberapa aturan;
// aturan pertama yang gagal itulah yang ditampilkan.
const daftarAturanValidasi = [
    // --- Form Daftarkan Tim ---
    {
        selector: "[name='nickname']",
        cek: function (nilai) {
            return /^[A-Za-z0-9]{1,3}$/.test(nilai.trim());
        },
        pesan: "Nickname wajib 1-3 karakter (huruf atau angka)."
    },
    {
        selector: "[name='nama_tim']",
        cek: function (nilai) {
            const panjang = nilai.trim().length;
            return panjang >= 3 && panjang <= 30;
        },
        pesan: "Nama tim wajib 3-30 karakter."
    },
    {
        selector: "[name='jumlah_anggota']",
        cek: function (nilai) {
            if (nilai.trim() === "") return true; // opsional
            const angka = parseInt(nilai, 10);
            return !isNaN(angka) && angka >= 1 && angka <= 20;
        },
        pesan: "Jumlah anggota harus 1-20 (boleh dikosongkan)."
    },
    {
        selector: "[name='game']",
        cek: function (nilai) {
            return nilai !== "";
        },
        pesan: "Pilih game terlebih dahulu."
    },

    // --- Form Create Match ---
    {
        selector: "[name='tim1']",
        cek: function (nilai) {
            return nilai !== "";
        },
        pesan: "Pilih Team 1."
    },
    {
        selector: "[name='tim1']",
        cek: function (nilai, form, input) {
            return gameDariTim(input) === form.querySelector("[name='game']").value;
        },
        pesan: "Team 1 harus bermain game yang dipilih."
    },
    {
        selector: "[name='tim2']",
        cek: function (nilai, form) {
            return nilai === "" || nilai !== form.querySelector("[name='tim1']").value;
        },
        pesan: "Team 2 tidak boleh sama dengan Team 1."
    },
    {
        selector: "[name='tim2']",
        cek: function (nilai, form, input) {
            return nilai === "" || gameDariTim(input) === form.querySelector("[name='game']").value;
        },
        pesan: "Team 2 harus bermain game yang dipilih."
    }
];

function initValidasiForm() {
    document.querySelectorAll("form.form-validasi").forEach(function (form) {
        form.addEventListener("submit", function (e) {
            let valid = true;
            const bermasalah = []; // field yang sudah punya error dari aturan sebelumnya

            daftarAturanValidasi.forEach(function (aturan) {
                const input = form.querySelector(aturan.selector);
                if (!input) return;                    // field ini tidak ada di form ini
                if (bermasalah.includes(input)) return; // tampilkan satu error per field

                if (aturan.cek(input.value, form, input)) {
                    hapusError(input);
                } else {
                    tampilkanError(input, aturan.pesan);
                    bermasalah.push(input);
                    valid = false;
                }
            });

            // Jika ada yang salah, BATALKAN pengiriman. Jika valid, form dikirim ke
            // proses_tambah.php, di mana server memvalidasi lagi (validasi client bisa dilewati).
            if (!valid) {
                e.preventDefault();
            }
        });
    });
}

document.addEventListener("DOMContentLoaded", function () {
    initNavToggle();
    initHapusConfirm();
    initTableFilter();
    initValidasiForm();
});
