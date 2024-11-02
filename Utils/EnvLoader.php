<?php
declare(strict_types=1);

class EnvLoader {
    public static function load(string $filePath = __DIR__ . '/../../config/.env'): array {
        $env = [];
        if (file_exists($filePath)) {
            $env = parse_ini_file($filePath);
        }
        return $env;
    }
}
?>
