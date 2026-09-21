-- VanguardArena: jalankan file ini sekali di SQL Editor Neon (aman dijalankan ulang).

CREATE TABLE IF NOT EXISTS pemain (
    id             SERIAL PRIMARY KEY,
    username       VARCHAR(20) NOT NULL UNIQUE,
    game_utama     VARCHAR(30) NOT NULL,
    mmr            INT NOT NULL DEFAULT 1000,
    tanggal_daftar TIMESTAMP DEFAULT NOW()
);

-- pemain1 / pemain2 menyimpan username. pemain2 kosong (NULL) = masih menunggu lawan.
CREATE TABLE IF NOT EXISTS pertandingan (
    id      SERIAL PRIMARY KEY,
    game    VARCHAR(30) NOT NULL,
    pemain1 VARCHAR(20) NOT NULL REFERENCES pemain(username),
    pemain2 VARCHAR(20) REFERENCES pemain(username),
    skor1   INT,
    skor2   INT,
    status  VARCHAR(15) NOT NULL DEFAULT 'Waiting'
            CHECK (status IN ('Waiting', 'In-Progress', 'Completed')),
    dibuat  TIMESTAMP DEFAULT NOW()
);

-- Data contoh
INSERT INTO pemain (username, game_utama, mmr) VALUES
    ('NovaStrike', 'Valorant',       1620),
    ('ShadowFang', 'Valorant',       1385),
    ('IronWolf',   'Mobile Legends', 1240),
    ('PixelQueen', 'Dota 2',         1105),
    ('RexOverdrive','EA FC 25',      1010),
    ('CipherX',    'Valorant',        940),
    ('BlazeRunner','Mobile Legends',  870),
    ('LunaEcho',   'Dota 2',          815)
ON CONFLICT (username) DO NOTHING;

INSERT INTO pertandingan (game, pemain1, pemain2, skor1, skor2, status)
SELECT * FROM (VALUES
    ('Valorant',       'NovaStrike', 'ShadowFang', NULL::int, NULL::int, 'In-Progress'),
    ('Mobile Legends', 'IronWolf',   NULL::varchar, NULL::int, NULL::int, 'Waiting'),
    ('Dota 2',         'PixelQueen', NULL::varchar, NULL::int, NULL::int, 'Waiting'),
    ('EA FC 25',       'RexOverdrive','CipherX',   NULL::int, NULL::int, 'In-Progress'),
    ('Valorant',       'NovaStrike', 'CipherX',    13,        7,         'Completed'),
    ('Dota 2',         'LunaEcho',   'PixelQueen', 1,         2,         'Completed')
) AS data(game, pemain1, pemain2, skor1, skor2, status)
WHERE NOT EXISTS (SELECT 1 FROM pertandingan);
