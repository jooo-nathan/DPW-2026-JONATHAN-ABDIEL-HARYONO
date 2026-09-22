// ===== Hamburger menu =====
function initNavToggle() {
    const toggleBtn = document.getElementById("nav-toggle-btn");
    const nav = document.querySelector("header nav");
    if (!toggleBtn || !nav) return;

    toggleBtn.addEventListener("click", function () {
        nav.classList.toggle("nav-open");
    });
}

// ===== Pencarian tabel (khusus kolom Game) =====
function initTableFilter() {
    const input = document.getElementById("search-input");
    const table = document.querySelector(".table-responsive table");
    if (!input || !table) return;

    input.addEventListener("keyup", function () {
        const keyword = input.value.toLowerCase();
        table.querySelectorAll("tbody tr").forEach(function (row) {
            const selGame = row.querySelector('[data-kolom="game"]');
            if (!selGame) return; // baris tanpa data (pesan "Belum ada match") dilewati
            const teksGame = selGame.textContent.toLowerCase();
            row.style.display = teksGame.includes(keyword) ? "" : "none";
        });
    });
}

document.addEventListener("DOMContentLoaded", function () {
    initNavToggle();
    initTableFilter();
});
