<?php
declare(strict_types=1);

require_once __DIR__ . '/../Utils/EnvLoader.php';

class Database {
    private string $host;
    private string $db_name;
    private string $username;
    private string $password;
    private ?PDO $conn = null;

    public function __construct() {
        $this->loadConfig();
    }

    // Charge la configuration depuis le fichier .env
    private function loadConfig(): void {
        $env = EnvLoader::load(__DIR__ . '/../config/.env');
        $this->host = $env['DB_HOST'] ?? 'localhost';
        $this->db_name = $env['DB_NAME'] ?? '';
        $this->username = $env['DB_USER'] ?? '';
        $this->password = $env['DB_PASSWORD'] ?? '';
    }

    public function getConnection(): ?PDO {
        if ($this->conn === null) {
            try {
                $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name};charset=utf8", $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $exception) {
                echo json_encode(["status" => "error", "message" => "Connection error: " . $exception->getMessage()]);
                return null;
            }
        }
        return $this->conn;
    }
}
?>
