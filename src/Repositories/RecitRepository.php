<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/db.php';

class RecitRepository {
    private PDO $conn;

    public function __construct() {
        $this->conn = Database::getInstance();
    }

    // Méthode pour récupérer tous les récits
    public function findAll(): array {
        $query = "SELECT * FROM recits";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Méthode pour récupérer un récit par son slug
    public function findBySlug(string $slug): ?array {
        $query = "SELECT * FROM recits WHERE slug = :slug";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Méthode pour créer un nouveau récit
    public function create(array $data): bool {
        $query = "INSERT INTO recits (title, description, slug, author_id, creation_date, last_update_date) 
                  VALUES (:title, :description, :slug, :author_id, :creation_date, :last_update_date)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':slug', $data['slug']);
        $stmt->bindParam(':author_id', $data['author_id']);
        $stmt->bindParam(':creation_date', $data['creation_date']);
        $stmt->bindParam(':last_update_date', $data['last_update_date']);
        return $stmt->execute();
    }

    // Méthode pour mettre à jour un récit par son slug
    public function update(string $slug, array $data): bool {
        $query = "UPDATE recits SET title = :title, description = :description, last_update_date = :last_update_date WHERE slug = :slug";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':last_update_date', $data['last_update_date']);
        $stmt->bindParam(':slug', $slug);
        return $stmt->execute();
    }

    // Méthode pour supprimer un récit par son slug
    public function delete(string $slug): bool {
        try {
            $this->conn->beginTransaction();
            // Logic to delete TOC entries and articles related to the recit by slug
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw new Exception("Failed to delete recit: " . $e->getMessage());
        }
    }
}
?>
