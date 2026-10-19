-- Penyimpanan session di database (wajib untuk Vercel / serverless).
-- Tabel ini sebenarnya dibuat otomatis oleh app/includes/session.php,
-- file ini disediakan kalau ingin membuatnya manual. Aman dijalankan ulang.
CREATE TABLE IF NOT EXISTS sessions (
    id         VARCHAR(128) PRIMARY KEY,
    data       TEXT NOT NULL,
    updated_at BIGINT NOT NULL
);
