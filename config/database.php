<?php
declare(strict_types=1);

class Database {
    private string $host;
    private string $db_name;
    private string $username;
    private string $password;
    public ?PDO $conn = null;

    public function __construct() {
        $this->loadEnv();
    }

    private function loadEnv(): void {
        if (file_exists(__DIR__ . '/.env')) {
            $env = parse_ini_file(__DIR__ . '/.env');
            $this->host = $env['DB_HOST'] ?? 'localhost';
            $this->db_name = $env['DB_NAME'] ?? '';
            $this->username = $env['DB_USER'] ?? '';
            $this->password = $env['DB_PASSWORD'] ?? '';
        }
    }

    public function getConnection(): ?PDO {
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo json_encode(["status" => "error", "message" => "Connection error: " . $exception->getMessage()]);
            return null;
        }
        return $this->conn;
    }
}
?>
