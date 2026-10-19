-- VanguardArena
-- Jalankan seluruh file ini di SQL Editor Neon.
-- Catatan: dua baris DROP di bawah menghapus tabel lama, jadi menjalankan ulang = data kembali kosong.

DROP TABLE IF EXISTS pertandingan;
DROP TABLE IF EXISTS tim;

-- Tabel tim.
-- nickname   : tag pendek ala tim esports (1-3 karakter, mis. "RRQ", "EVO").
-- jumlah_anggota : opsional, sekadar data tambahan, tidak dipakai fitur lain.
-- UNIQUE(nickname, game) dan UNIQUE(nama_tim, game): nickname/nama_tim BOLEH
-- sama, ASALKAN game-nya berbeda (dua tim "RRQ" boleh ada kalau main game
-- yang beda; tidak boleh ada dua "RRQ" yang sama-sama main Valorant).
CREATE TABLE tim (
    id             SERIAL PRIMARY KEY,
    nickname       VARCHAR(3) NOT NULL,
    nama_tim       VARCHAR(30) NOT NULL,
    jumlah_anggota INT,
    game           VARCHAR(30) NOT NULL,
    mmr            INT NOT NULL DEFAULT 1000,
    UNIQUE (nickname, game),
    UNIQUE (nama_tim, game)
);

-- Tabel pertandingan: tim1/tim2 menyimpan NICKNAME (bukan nama_tim).
-- tim2 kosong (NULL) berarti masih menunggu lawan.
CREATE TABLE pertandingan (
    id     SERIAL PRIMARY KEY,
    game   VARCHAR(30) NOT NULL,
    tim1   VARCHAR(3) NOT NULL,
    tim2   VARCHAR(3),
    skor1  INT,
    skor2  INT,
    status VARCHAR(15) NOT NULL DEFAULT 'Waiting'
);

-- Sengaja tanpa data contoh: tim dan match diisi sendiri lewat halaman
-- "Daftarkan Tim" dan "Create Match".
