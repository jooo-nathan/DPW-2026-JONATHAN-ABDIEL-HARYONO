<?php
// Memulai session. Di Vercel (serverless) session berbasis file TIDAK
// bisa dipakai: tiap request bisa dijalankan di instance berbeda dengan
// folder /tmp berbeda, sehingga login & token CSRF "hilang". Karena itu di
// Vercel session disimpan di tabel PostgreSQL `sessions`.
// Di lokal (php -S / Laragon) tetap memakai session file bawaan PHP.

if (!class_exists('DbSessionHandler')) {
    class DbSessionHandler implements SessionHandlerInterface
    {
        private $pdo;

        public function __construct(PDO $pdo)
        {
            $this->pdo = $pdo;
        }

        public function open(string $path, string $name): bool
        {
            $this->pdo->exec(
                "CREATE TABLE IF NOT EXISTS sessions (
                    id         VARCHAR(128) PRIMARY KEY,
                    data       TEXT NOT NULL,
                    updated_at BIGINT NOT NULL
                )"
            );
            return true;
        }

        public function close(): bool
        {
            return true;
        }

        public function read(string $id): string|false
        {
            $stmt = $this->pdo->prepare("SELECT data FROM sessions WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetchColumn();
            return $row === false ? '' : (string) base64_decode($row);
        }

        public function write(string $id, string $data): bool
        {
            $stmt = $this->pdo->prepare(
                "INSERT INTO sessions (id, data, updated_at) VALUES (:id, :data, :t)
                 ON CONFLICT (id) DO UPDATE SET data = EXCLUDED.data, updated_at = EXCLUDED.updated_at"
            );
            return $stmt->execute(['id' => $id, 'data' => base64_encode($data), 't' => time()]);
        }

        public function destroy(string $id): bool
        {
            $stmt = $this->pdo->prepare("DELETE FROM sessions WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        }

        public function gc(int $max_lifetime): int|false
        {
            $stmt = $this->pdo->prepare("DELETE FROM sessions WHERE updated_at < :t");
            $stmt->execute(['t' => time() - $max_lifetime]);
            return $stmt->rowCount();
        }
    }
}

function mulai_session()
{
    if (session_status() !== PHP_SESSION_NONE) {
        return;
    }

    if (getenv('VERCEL') || getenv('SESSION_DRIVER') === 'db') {
        require_once __DIR__ . '/koneksi.php';
        session_set_save_handler(new DbSessionHandler(db_connect()), true);
    }

    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

    ini_set('session.use_strict_mode', '1');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => $https,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}
