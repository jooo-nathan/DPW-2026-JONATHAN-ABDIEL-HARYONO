<?php
// Fungsi-fungsi kecil yang dipakai di banyak halaman.

// Pilihan game (dipakai di dropdown dan untuk validasi)
const DAFTAR_GAME = ['Valorant', 'Mobile Legends', 'Dota 2', 'EA FC 25'];

// Mengamankan teks sebelum ditampilkan di HTML (mencegah XSS)
function e($teks)
{
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}

// Tier ditentukan dari MMR
function badge_tier($mmr)
{
    if ($mmr >= 1500) {
        $nama = 'Diamond';
    } elseif ($mmr >= 1300) {
        $nama = 'Platinum';
    } elseif ($mmr >= 1100) {
        $nama = 'Gold';
    } elseif ($mmr >= 900) {
        $nama = 'Silver';
    } else {
        $nama = 'Bronze';
    }
    return '<span class="badge tier-' . strtolower($nama) . '">' . $nama . '</span>';
}

// Badge status pertandingan
function badge_status($status)
{
    $kelas = [
        'Waiting'     => 'status-waiting',
        'In-Progress' => 'status-live',
        'Completed'   => 'status-done',
    ];
    return '<span class="badge ' . ($kelas[$status] ?? '') . '">' . e($status) . '</span>';
}

// Mahkota / medali untuk peringkat 1-3, angka biasa untuk sisanya
function badge_rank($no)
{
    if ($no === 1) {
        return '<span class="rank rank-1" title="Peringkat 1">&#128081;</span>';
    }
    if ($no === 2) {
        return '<span class="rank rank-2" title="Peringkat 2">&#129352;</span>';
    }
    if ($no === 3) {
        return '<span class="rank rank-3" title="Peringkat 3">&#129353;</span>';
    }
    return '<span class="rank">' . $no . '</span>';
}

// Pindah halaman sambil membawa pesan lewat alamat (?status=...&pesan=...).
// Dipilih supaya tetap jalan di Vercel, yang tidak menyimpan session antar halaman.
function flash_redirect($url, $type, $pesan)
{
    $pemisah = strpos($url, '?') === false ? '?' : '&';
    header('Location: ' . $url . $pemisah . 'status=' . $type . '&pesan=' . urlencode($pesan));
    exit;
}

// Menampilkan pesan yang dibawa flash_redirect (kalau ada)
function tampil_flash()
{
    $type  = $_GET['status'] ?? '';
    $pesan = $_GET['pesan'] ?? '';
    if ($pesan !== '' && in_array($type, ['success', 'error'], true)) {
        echo '<p class="flash flash-' . $type . '">' . e($pesan) . '</p>';
    }
}
