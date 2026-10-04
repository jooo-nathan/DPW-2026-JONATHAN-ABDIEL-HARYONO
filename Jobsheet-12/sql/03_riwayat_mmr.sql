-- Jobsheet 12: tabel riwayat_mmr, menghubungkan tim dan pertandingan lewat FOREIGN KEY
-- Jalankan SETELAH 01_vanguard_arena.sql (tabel tim & pertandingan harus sudah ada).
-- ON DELETE CASCADE: kalau tim / match dihapus (termasuk Reset Data), catatan riwayatnya ikut terhapus,
-- jadi tidak ada baris yatim dan tidak ada error foreign key.

CREATE TABLE IF NOT EXISTS riwayat_mmr (
    id             SERIAL PRIMARY KEY,
    tim_id         INTEGER NOT NULL REFERENCES tim(id) ON DELETE CASCADE,
    pertandingan_id INTEGER NOT NULL REFERENCES pertandingan(id) ON DELETE CASCADE,
    hasil          VARCHAR(10) NOT NULL,   -- 'Menang' / 'Kalah'
    perubahan      INT NOT NULL,           -- +25 atau -15
    mmr_sesudah    INT NOT NULL,
    dicatat_pada   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
