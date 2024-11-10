<?php
declare(strict_types=1);

require_once __DIR__ . '/../Utils/EnvLoader.php';

class Database {
    private string $host;
    private string $db_name;
    private string $username;
    private string $password;
    private static ?PDO $instance = null;

    private function __construct() {
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

    // Méthode pour obtenir l'instance PDO unique
    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $db = new Database();
            try {
                self::$instance = new PDO(
                    "mysql:host={$db->host};dbname={$db->db_name};charset=utf8",
                    $db->username,
                    $db->password
                );
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $exception) {
                die(json_encode(["status" => "error", "message" => "Connection error: " . $exception->getMessage()]));
            }
        }
        return self::$instance;
    }
}
