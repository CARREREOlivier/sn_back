<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/db.php';

class RecitRepository {
    private PDO $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // Méthode pour récupérer tous les récits
    public function findAll(): array {
        if ($this->conn === null) {
            throw new Exception("Failed to connect to the database");
        }

        // Requête pour récupérer tous les récits
        $query = "SELECT * FROM recits";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        // Retourner les résultats sous forme de tableau associatif
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Méthode pour récupérer un récit par son slug
    public function findBySlug(string $slug): ?array {
        $query = "SELECT * FROM recits WHERE slug = :slug";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
        $stmt->execute();
        $recit = $stmt->fetch(PDO::FETCH_ASSOC);

        return $recit ?: null; // Retourne null si aucun récit n'est trouvé
    }
    // Méthode pour créer un nouveau récit
    public function create(array $data): bool {
        $query = "INSERT INTO recits (title, description, slug, author_id, creation_date, last_update_date) 
          VALUES (:title, :description, :slug, :author_id, :creation_date, :last_update_date)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':slug', $data['slug']);
        $stmt->bindParam(':creation_date', $data['creation_date']);
        $stmt->bindParam(':last_update_date', $data['last_update_date']);
        $stmt->bindParam(':author_id', $data['author_id']);
        return $stmt->execute();
    }

    // Méthode pour supprimer un récit par son slug
    public function delete(string $slug): bool {
        if ($this->conn === null) {
            throw new Exception("Failed to connect to the database");
        }

        try {
            // Commence une transaction pour assurer la suppression en cascade
            $this->conn->beginTransaction();

            // Récupère l'id du récit à partir du slug
            $queryRecit = "SELECT recit_id FROM recits WHERE slug = :slug";
            $stmtRecit = $this->conn->prepare($queryRecit);
            $stmtRecit->bindParam(':slug', $slug, PDO::PARAM_STR);
            $stmtRecit->execute();
            $recit = $stmtRecit->fetch(PDO::FETCH_ASSOC);

            if (!$recit) {
                // Si aucun récit n'est trouvé, annuler la transaction
                $this->conn->rollBack();
                return false;
            }

            $recitId = $recit['recit_id'];

            // Récupérer les IDs dans TOC pour les enregistrements liés au récit
            $queryTOCIds = "SELECT toc_id FROM toc WHERE recit_id = :recitId";
            $stmtTOCIds = $this->conn->prepare($queryTOCIds);
            $stmtTOCIds->bindParam(':recitId', $recitId, PDO::PARAM_INT);
            $stmtTOCIds->execute();
            $tocIds = $stmtTOCIds->fetchAll(PDO::FETCH_COLUMN);

            if ($tocIds) {
                // Supprimer les articles liés aux IDs dans TOC
                $queryArticles = "DELETE FROM articles WHERE toc_id IN (" . implode(",", array_map('intval', $tocIds)) . ")";
                $stmtArticles = $this->conn->prepare($queryArticles);
                $stmtArticles->execute();
            }

            // Suppression des enregistrements dans TOC liés au récit
            $queryTOC = "DELETE FROM toc WHERE recit_id = :recitId";
            $stmtTOC = $this->conn->prepare($queryTOC);
            $stmtTOC->bindParam(':recitId', $recitId, PDO::PARAM_INT);
            $stmtTOC->execute();

            // Suppression du récit lui-même
            $queryDeleteRecit = "DELETE FROM recits WHERE recit_id = :recitId";
            $stmtDeleteRecit = $this->conn->prepare($queryDeleteRecit);
            $stmtDeleteRecit->bindParam(':recitId', $recitId, PDO::PARAM_INT);
            $stmtDeleteRecit->execute();

            // Valide la transaction
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            // Annule la transaction en cas d'erreur
            $this->conn->rollBack();
            throw new Exception("Failed to delete recit: " . $e->getMessage());
        }
    }


// Méthode pour mettre à jour un récit par son slug
    public function update(string $slug, array $data): bool {
        if ($this->conn === null) {
            throw new Exception("Failed to connect to the database");
        }

        $query = "UPDATE recits SET title = :title, description = :description, last_update_date = :last_update_date WHERE slug = :slug";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':last_update_date', $data['last_update_date']);
        $stmt->bindParam(':slug', $slug);

        return $stmt->execute();
    }

}
?>
