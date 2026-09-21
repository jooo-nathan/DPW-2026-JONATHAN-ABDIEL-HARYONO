// ===== Hamburger menu =====
function initNavToggle() {
    const toggleBtn = document.getElementById("nav-toggle-btn");
    const nav = document.querySelector("header nav");
    if (!toggleBtn || !nav) return;

    toggleBtn.addEventListener("click", function () {
        nav.classList.toggle("nav-open");
    });
}

// ===== Pencarian tabel real-time =====
// Kolom #search-input menyaring baris di tabel yang punya class "filterable".
function initTableFilter() {
    const input = document.getElementById("search-input");
    const table = document.querySelector("table.filterable");
    if (!input || !table) return;

    input.addEventListener("keyup", function () {
        const keyword = input.value.toLowerCase();
        table.querySelectorAll("tbody tr").forEach(function (row) {
            const teks = row.textContent.toLowerCase();
            row.style.display = teks.includes(keyword) ? "" : "none";
        });
    });
}

document.addEventListener("DOMContentLoaded", function () {
    initNavToggle();
    initTableFilter();
});
