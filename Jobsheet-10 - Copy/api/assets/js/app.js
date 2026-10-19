// ===== Hamburger menu =====
function initNavToggle() {
    const toggleBtn = document.getElementById("nav-toggle-btn");
    const nav = document.querySelector("header nav");
    if (!toggleBtn || !nav) return;

    toggleBtn.addEventListener("click", function () {
        nav.classList.toggle("nav-open");
    });
}

// ===== Pencarian tabel (1 kolom per halaman, ditandai atribut data-kolom) =====
// Dipakai di 2 tempat berbeda: Matchmaking Hub (kolom Game) dan
// Leaderboard (kolom Nickname) -- fungsi yang sama, kolom yang ditandai
// beda-beda per halaman, jadi tidak perlu ditulis dua fungsi terpisah.
function initTableFilter() {
    const input = document.getElementById("search-input");
    const table = document.querySelector(".table-responsive table");
    if (!input || !table) return;

    input.addEventListener("keyup", function () {
        const keyword = input.value.toLowerCase();
        table.querySelectorAll("tbody tr").forEach(function (row) {
            const sel = row.querySelector('[data-kolom]');
            if (!sel) return; // baris tanpa data (pesan "Belum ada match/tim") dilewati
            const teks = sel.textContent.toLowerCase();
            row.style.display = teks.includes(keyword) ? "" : "none";
        });
    });
}

// ===== Konfirmasi sebelum hapus (event "submit", bukan "click") =====
// Tombol Hapus ada di dalam <form class="form-hapus" method="post"> yang
// sungguhan mengirim DELETE ke server. Event "submit" terjadi TEPAT
// SEBELUM data terkirim, jadi masih bisa dibatalkan (preventDefault) kalau
// pengguna menekan Cancel -- beda dengan mendengarkan "click" pada tombolnya,
// yang sudah terlambat untuk mencegah pengiriman form.
function initHapusConfirm() {
    document.addEventListener("submit", function (e) {
        const form = e.target;
        if (!form.classList.contains("form-hapus")) return;

        const row = form.closest("tr");
        const nama = row ? row.querySelector("td")?.textContent : "data ini";
        const yakin = confirm("Yakin ingin menghapus \"" + nama + "\"?");
        if (!yakin) {
            e.preventDefault();
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    initNavToggle();
    initTableFilter();
    initHapusConfirm();
});
