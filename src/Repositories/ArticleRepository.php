<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseRepository.php';
require_once __DIR__ . '/../models/ArticleModel.php';

class ArticleRepository extends BaseRepository {
    protected string $table = 'articles';
    protected string $primaryKey = 'article_id';

    // Méthode pour insérer une entrée dans la table TOC et retourner l'ID créé
    private function insertTOC(int $recitId, string $title, string $slug): int {
        $queryTOC = "INSERT INTO toc (recit_id, title, slug) 
                     VALUES (:recit_id, :title, :slug)";
        $paramsTOC = [
            ':recit_id' => $recitId,
            ':title' => $title,
            ':slug' => $slug
        ];
        $this->executeQuery($queryTOC, $paramsTOC);
        return (int) $this->conn->lastInsertId();
    }

    // Méthode pour créer un nouvel article avec une entrée dans TOC
    public function createArticle(mixed $article, int $recitId, string $slug): bool {
        try {
            $this->conn->beginTransaction();

            // Étape 1 : Insertion de l'entrée dans TOC
            $tocId = $this->insertTOC($recitId, $article->getTitle(), $slug);

            // Étape 2 : Assigner $tocId à l'objet Article
            $article->setTocId($tocId);

            // Étape 3 : Utiliser la méthode `create` pour insérer l'article dans `articles`
            $result = $this->create($article);

            $this->conn->commit();
            return $result;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw new Exception("Failed to create article: " . $e->getMessage());
        }
    }

    // Méthode générique pour insérer un article dans la table `articles`
    public function create(mixed $article): bool {
        $queryArticle = "INSERT INTO {$this->table} (toc_id, title, content, creation_date, last_update_date, author_id) 
                         VALUES (:toc_id, :title, :content, :creation_date, :last_update_date, :author_id)";
        $paramsArticle = [
            ':toc_id' => $article->getTocId(),
            ':title' => $article->getTitle(),
            ':content' => $article->getContent(),
            ':creation_date' => $article->getCreationDate(),
            ':last_update_date' => $article->getLastUpdateDate(),
            ':author_id' => $article->getAuthorId()
        ];
        return $this->executeQuery($queryArticle, $paramsArticle);
    }

    // Méthode pour récupérer un article par son slug en utilisant TOC
    public function findArticleBySlug(string $recitSlug, string $articleSlug): ?array {
        $query = "SELECT a.*, t.slug as toc_slug, t.title as toc_title 
                  FROM {$this->table} a 
                  JOIN toc t ON a.toc_id = t.toc_id 
                  JOIN recits r ON t.recit_id = r.recit_id
                  WHERE r.slug = :recitSlug AND t.slug = :articleSlug";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':recitSlug', $recitSlug, PDO::PARAM_STR);
        $stmt->bindParam(':articleSlug', $articleSlug, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Méthode pour mettre à jour un article et son titre dans TOC
    public function updateArticle(ArticleModel $article, int $recitId, string $slugArticle): bool {
        try {
            $this->conn->beginTransaction();

            // Étape 1 : Mettre à jour le titre dans TOC si nécessaire
            $queryTOC = "UPDATE toc SET title = :toc_title WHERE slug = :slugArticle AND recit_id = :recitId";
            $paramsTOC = [
                ':toc_title' => $article->getTitle(),
                ':slugArticle' => $slugArticle,
                ':recitId' => $recitId
            ];
            $this->executeQuery($queryTOC, $paramsTOC);

            // Étape 2 : Mettre à jour le contenu de l'article dans `articles`
            $queryArticle = "UPDATE articles SET title = :title, content = :content, last_update_date = :last_update_date 
                         WHERE toc_id = :tocId";
            $paramsArticle = [
                ':title' => $article->getTitle(),
                ':content' => $article->getContent(),
                ':last_update_date' => $article->getLastUpdateDate(),
                ':tocId' => $article->getTocId()
            ];
            $this->executeQuery($queryArticle, $paramsArticle);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw new Exception("Failed to update article: " . $e->getMessage());
        }
    }


    // Méthode pour supprimer un article et son entrée dans TOC
    public function deleteByTocId(int $tocId): bool {
        try {
            $this->conn->beginTransaction();

            // Suppression de l'article dans `articles`
            $queryArticle = "DELETE FROM articles WHERE toc_id = :tocId";
            $stmtArticle = $this->conn->prepare($queryArticle);
            $stmtArticle->bindParam(':tocId', $tocId, PDO::PARAM_INT);
            $stmtArticle->execute();

            // Suppression de l'entrée TOC
            $queryTOC = "DELETE FROM toc WHERE toc_id = :tocId";
            $stmtTOC = $this->conn->prepare($queryTOC);
            $stmtTOC->bindParam(':tocId', $tocId, PDO::PARAM_INT);
            $stmtTOC->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw new Exception("Failed to delete article: " . $e->getMessage());
        }
    }


    public function findTOCBySlugAndRecitId(string $slugArticle, int $recitId): ?array {
        $query = "SELECT toc_id FROM toc WHERE slug = :slugArticle AND recit_id = :recitId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':slugArticle', $slugArticle, PDO::PARAM_STR);
        $stmt->bindParam(':recitId', $recitId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findByTocId(int $tocId): ?array {
        $query = "SELECT * FROM articles WHERE toc_id = :tocId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tocId', $tocId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }


    public function update(int $articleId, mixed $article,): bool {return false;}
}
