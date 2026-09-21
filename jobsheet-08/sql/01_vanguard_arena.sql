-- VanguardArena
-- Jalankan seluruh file ini di SQL Editor Neon.
-- Catatan: dua baris DROP di bawah menghapus tabel lama, jadi menjalankan ulang = data kembali ke awal.

DROP TABLE IF EXISTS pertandingan;
DROP TABLE IF EXISTS pemain;

-- Tabel pemain: username tidak boleh sama (UNIQUE)
CREATE TABLE pemain (
    id         SERIAL PRIMARY KEY,
    username   VARCHAR(20) NOT NULL UNIQUE,
    game_utama VARCHAR(30) NOT NULL,
    mmr        INT NOT NULL DEFAULT 1000
);

-- Tabel pertandingan: pemain2 kosong (NULL) berarti masih menunggu lawan
CREATE TABLE pertandingan (
    id      SERIAL PRIMARY KEY,
    game    VARCHAR(30) NOT NULL,
    pemain1 VARCHAR(20) NOT NULL,
    pemain2 VARCHAR(20),
    skor1   INT,
    skor2   INT,
    status  VARCHAR(15) NOT NULL DEFAULT 'Waiting'
);

-- Data contoh
INSERT INTO pemain (username, game_utama, mmr) VALUES
    ('NovaStrike',   'Valorant',       1620),
    ('ShadowFang',   'Valorant',       1385),
    ('IronWolf',     'Mobile Legends', 1240),
    ('PixelQueen',   'Dota 2',         1105),
    ('RexOverdrive', 'EA FC 25',       1010),
    ('CipherX',      'Valorant',        940),
    ('BlazeRunner',  'Mobile Legends',  870),
    ('LunaEcho',     'Dota 2',          815);

INSERT INTO pertandingan (game, pemain1, pemain2, skor1, skor2, status) VALUES
    ('Valorant',       'NovaStrike',   'ShadowFang', NULL, NULL, 'In-Progress'),
    ('Mobile Legends', 'IronWolf',     NULL,         NULL, NULL, 'Waiting'),
    ('Dota 2',         'PixelQueen',   NULL,         NULL, NULL, 'Waiting'),
    ('EA FC 25',       'RexOverdrive', 'CipherX',    NULL, NULL, 'In-Progress'),
    ('Valorant',       'NovaStrike',   'CipherX',    13,   7,    'Completed'),
    ('Dota 2',         'LunaEcho',     'PixelQueen', 1,    2,    'Completed');
