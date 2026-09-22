-- VanguardArena
-- Jalankan seluruh file ini di SQL Editor Neon.
-- Catatan: dua baris DROP di bawah menghapus tabel lama, jadi menjalankan ulang = data kembali kosong.

DROP TABLE IF EXISTS pertandingan;
DROP TABLE IF EXISTS tim;

-- Tabel tim: nama_tim tidak boleh sama (UNIQUE)
CREATE TABLE tim (
    id       SERIAL PRIMARY KEY,
    nama_tim VARCHAR(30) NOT NULL UNIQUE,
    game     VARCHAR(30) NOT NULL,
    mmr      INT NOT NULL DEFAULT 1000
);

-- Tabel pertandingan: tim2 kosong (NULL) berarti masih menunggu lawan
CREATE TABLE pertandingan (
    id     SERIAL PRIMARY KEY,
    game   VARCHAR(30) NOT NULL,
    tim1   VARCHAR(30) NOT NULL,
    tim2   VARCHAR(30),
    skor1  INT,
    skor2  INT,
    status VARCHAR(15) NOT NULL DEFAULT 'Waiting'
);

-- Sengaja tanpa data contoh: tim dan match diisi sendiri lewat halaman
-- "Daftarkan Tim" dan "Create Match", supaya datanya benar-benar mulai dari kosong.
