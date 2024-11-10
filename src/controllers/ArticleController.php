<?php
declare(strict_types=1);

require_once __DIR__ . '/../repositories/ArticleRepository.php';
require_once __DIR__ . '/../repositories/RecitRepository.php';
require_once __DIR__ . '/../models/ArticleModel.php';

class ArticleController {
    private ArticleRepository $articleRepository;
    private RecitRepository $recitRepository;

    public function __construct() {
        $this->articleRepository = new ArticleRepository();
        $this->recitRepository = new RecitRepository();
    }

    // Méthode pour créer un nouvel article
    public function createArticle(string $slugRecit): void {
        $data = json_decode(file_get_contents("php://input"), true);

        // Vérifier les données nécessaires
        if (!isset($data['title'], $data['content'], $data['slug'], $data['author_id'])) {
            echo json_encode(["status" => "error", "message" => "Invalid data"]);
            http_response_code(400);
            return;
        }

        // Récupérer l'ID du récit à partir du slug du récit
        $recit = $this->recitRepository->findBySlug($slugRecit);
        if (!$recit) {
            echo json_encode(["status" => "error", "message" => "Recit not found"]);
            http_response_code(404);
            return;
        }

        // Étape 1 : Créer un modèle Article sans slug
        $article = new ArticleModel(
            null,
            0,  // Le toc_id sera défini après l'insertion dans TOC
            $data['title'],
            $data['content'],
            $data['author_id'],
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s')
        );

        // Étape 2 : Insertion de l'article avec le slug transmis directement
        try {
            $success = $this->articleRepository->createArticle($article, $recit->getId(), $data['slug']);
            if ($success) {
                echo json_encode(["status" => "success", "message" => "Article created successfully"]);
                http_response_code(201);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to create article"]);
                http_response_code(500);
            }
        } catch (Exception $exception) {
            echo json_encode(["status" => "error", "message" => "Error: " . $exception->getMessage()]);
        }
    }
    // Méthode pour afficher un article par son slug
    public function displayArticleBySlug(string $slugRecit, string $slugArticle): void {
        try {
            // Étape 1 : Récupérer l'ID du récit à partir du slug du récit
            $recit = $this->recitRepository->findBySlug($slugRecit);
            if (!$recit) {
                echo json_encode(["status" => "error", "message" => "Recit not found"]);
                http_response_code(404);
                return;
            }
            $recitId = $recit->getId();

            // Étape 2 : Récupérer l'ID du TOC en utilisant le slug de l'article et l'ID du récit
            $tocEntry = $this->articleRepository->findTOCBySlugAndRecitId($slugArticle, $recitId);
            if (!$tocEntry) {
                echo json_encode(["status" => "error", "message" => "Article TOC entry not found"]);
                http_response_code(404);
                return;
            }
            $tocId = $tocEntry['toc_id'];

            // Étape 3 : Récupérer l'article à partir du toc_id
            $article = $this->articleRepository->findByTocId($tocId);
            if (!$article) {
                echo json_encode(["status" => "error", "message" => "Article not found"]);
                http_response_code(404);
                return;
            }

            // Étape 4 : Envoyer les données de l'article sous forme de JSON pour le front-end
            echo json_encode(["status" => "success", "data" => $article]);
            http_response_code(200);
        } catch (Exception $exception) {
            echo json_encode(["status" => "error", "message" => "Error: " . $exception->getMessage()]);
            http_response_code(500);
        }
    }

    // Méthode pour mettre à jour un article
    public function updateArticle(string $slugRecit, string $slugArticle): void {
        $data = json_decode(file_get_contents("php://input"), true);

        // Vérification des champs nécessaires
        if (!isset($data['title'], $data['content'])) {
            echo json_encode(["status" => "error", "message" => "Invalid data"]);
            http_response_code(400);
            return;
        }

        try {
            // Étape 1 : Récupérer l'ID du récit à partir du slug du récit
            $recit = $this->recitRepository->findBySlug($slugRecit);
            if (!$recit) {
                echo json_encode(["status" => "error", "message" => "Recit not found"]);
                http_response_code(404);
                return;
            }
            $recitId = $recit->getId();

            // Étape 2 : Récupérer l'article existant et les informations nécessaires
            $tocEntry = $this->articleRepository->findTOCBySlugAndRecitId($slugArticle, $recitId);
            if (!$tocEntry) {
                echo json_encode(["status" => "error", "message" => "Article TOC entry not found"]);
                http_response_code(404);
                return;
            }
            $tocId = $tocEntry['toc_id'];

            // Récupérer l'article existant pour conserver l'auteur et la date de création
            $existingArticle = $this->articleRepository->findByTocId($tocId);
            if (!$existingArticle) {
                echo json_encode(["status" => "error", "message" => "Article not found"]);
                http_response_code(404);
                return;
            }

            // Étape 3 : Créer un modèle Article avec les nouvelles données
            $article = new ArticleModel(
                null,
                $tocId,
                $data['title'],
                $data['content'],
                $existingArticle['author_id'],       // Conserver l'auteur
                $existingArticle['creation_date'],   // Conserver la date de création
                date('Y-m-d H:i:s')                  // Date de dernière modification
            );

            // Étape 4 : Mettre à jour l'article dans la base de données
            $success = $this->articleRepository->updateArticle($article, $recitId, $slugArticle);
            if ($success) {
                echo json_encode(["status" => "success", "message" => "Article updated successfully"]);
                http_response_code(200);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to update article"]);
                http_response_code(500);
            }
        } catch (Exception $exception) {
            echo json_encode(["status" => "error", "message" => "Error: " . $exception->getMessage()]);
            http_response_code(500);
        }
    }


    // Méthode pour supprimer un article
    public function deleteArticle(string $slugRecit, string $slugArticle): void {
        try {
            // Étape 1 : Récupérer l'ID du récit à partir du slug du récit
            $recit = $this->recitRepository->findBySlug($slugRecit);
            if (!$recit) {
                echo json_encode(["status" => "error", "message" => "Recit not found"]);
                http_response_code(404);
                return;
            }
            $recitId = $recit->getId();

            // Étape 2 : Récupérer l'ID du TOC en utilisant le slug de l'article et l'ID du récit
            $tocEntry = $this->articleRepository->findTOCBySlugAndRecitId($slugArticle, $recitId);
            if (!$tocEntry) {
                echo json_encode(["status" => "error", "message" => "Article TOC entry not found"]);
                http_response_code(404);
                return;
            }
            $tocId = $tocEntry['toc_id'];

            // Étape 3 : Supprimer l'article et l'entrée TOC
            $success = $this->articleRepository->deleteByTocId($tocId);
            if ($success) {
                echo json_encode(["status" => "success", "message" => "Article deleted successfully"]);
                http_response_code(200);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to delete article"]);
                http_response_code(500);
            }
        } catch (Exception $exception) {
            echo json_encode(["status" => "error", "message" => "Error: " . $exception->getMessage()]);
            http_response_code(500);
        }
    }

}
