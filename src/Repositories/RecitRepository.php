<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseRepository.php';
require_once __DIR__ . '/../models/RecitModel.php';

class RecitRepository extends BaseRepository {
    protected string $table = 'recits';
    protected string $primaryKey = 'recit_id';

    // Méthode pour créer un nouveau récit
    //$entity est un objet RecitModel dans ce projet
    /**
     * @param mixed $recit
     * @return bool
     */
    public function create(mixed $recit): bool {
        $query = "INSERT INTO {$this->table} (title, description, slug, author_id, creation_date, last_update_date) 
              VALUES (:title, :description, :slug, :author_id, :creation_date, :last_update_date)";
        $params = [
            ':title' => $recit->getTitle(),
            ':description' => $recit->getDescription(),
            ':slug' => $recit->getSlug(),
            ':author_id' => $recit->getAuthorId(),
            ':creation_date' => $recit->getCreationDate(),
            ':last_update_date' => $recit->getLastUpdateDate()
        ];
        return $this->executeQuery($query, $params);
    }

    // Méthode pour mettre à jour un récit
    //$recit est un objet RecitModel dans ce projet
    /**
     * @param int $id
     * @param mixed $recit
     * @return bool
     */
    public function update(int $id, mixed $recit): bool {
        $query = "UPDATE {$this->table} SET title = :title, description = :description, last_update_date = :last_update_date 
              WHERE {$this->primaryKey} = :id";
        $params = [
            ':title' => $recit->getTitle(),
            ':description' => $recit->getDescription(),
            ':last_update_date' => $recit->getLastUpdateDate(),
            ':id' => $id
        ];
        return $this->executeQuery($query, $params);
    }
// Méthode pour supprimer un récit par son slug
    public function deleteBySlug(string $slug): bool {
        try {
            $this->conn->beginTransaction();

            // Obtenir l'ID du récit à partir du slug
            $recit = $this->fetchSingleRow("SELECT {$this->primaryKey} FROM {$this->table} WHERE slug = :slug", [':slug' => $slug]);

            if (!$recit) {
                $this->conn->rollBack();
                return false;
            }

            $recitId = $recit[$this->primaryKey];

            // Obtenir les IDs dans TOC pour les enregistrements liés
            $tocIds = $this->fetchAllRows("SELECT toc_id FROM toc WHERE recit_id = :recitId", [':recitId' => $recitId]);

            if ($tocIds) {
                $tocIdList = implode(",", array_map('intval', array_column($tocIds, 'toc_id')));
                $this->executeQuery("DELETE FROM articles WHERE toc_id IN ($tocIdList)");
            }

            // Supprimer les enregistrements dans TOC
            $this->executeQuery("DELETE FROM toc WHERE recit_id = :recitId", [':recitId' => $recitId]);

            // Supprimer le récit
            $this->executeQuery("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :recitId", [':recitId' => $recitId]);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw new Exception("Failed to delete recit: " . $e->getMessage());
        }
    }



}
?>
