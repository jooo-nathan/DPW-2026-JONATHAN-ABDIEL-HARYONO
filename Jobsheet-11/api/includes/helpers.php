<?php
// Membungkus output data sebelum dicetak ke HTML, untuk mencegah XSS
// (Cross-Site Scripting). Dipakai di SEMUA tempat yang mencetak data dari
// database / $_GET / $_SESSION.
function e($value)
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}
