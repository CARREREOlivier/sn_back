<?php
declare(strict_types=1);

require_once __DIR__ . '/../repositories/RecitRepository.php';

class RecitController {
    private RecitRepository $repository;

    public function __construct() {
        $this->repository = new RecitRepository();
    }

    // Méthode pour récupérer et afficher tous les récits
    public function displayAllRecits(): void {
        try {
            // Utiliser le repository pour récupérer les récits
            $recits = $this->repository->findAll();

            // Affichage des récits en JSON
            echo json_encode($recits);

        } catch (Exception $exception) {
            echo json_encode([
                "status" => "error",
                "message" => "Error: " . $exception->getMessage()
            ]);
        }
    }


    // Méthode pour récupérer et afficher un récit par son slug
    public function displayRecitBySlug(string $slug): void {
        try {
            $recit = $this->repository->findBySlug($slug);
            if ($recit) {
                echo json_encode($recit);
            } else {
                echo json_encode(["status" => "error", "message" => "Récit not found"]);
                http_response_code(404);
            }

        } catch (Exception $exception) {
            echo json_encode(["status" => "error", "message" => "Error: " . $exception->getMessage()]);
        }
    }

     // Méthode pour créer un nouveau récit
    public function createRecit(): void {
        // Récupérer les données JSON
        $data = json_decode(file_get_contents("php://input"), true);

        // Vérification des champs requis
        if (!isset($data['title'], $data['description'], $data['slug'])) {
            echo json_encode(["status" => "error", "message" => "Invalid data"]);
            http_response_code(400);
            return;
        }

        // Ajouter l'auteur, la date de création et la date de mise à jour
        $data['author_id'] = 4;  // ID de l'auteur en dur pour le moment
        $data['creation_date'] = $data['last_update_date'] = date('Y-m-d H:i:s');

        // Créer une instance de RecitModel à partir des données
        $recit = new RecitModel(
            null,
            $data['title'],
            $data['description'],
            $data['slug'],
            $data['author_id'],
            $data['creation_date'],
            $data['last_update_date']
        );

        // Appeler le repository pour créer le récit
        try {
            $success = $this->repository->create($recit);
            if ($success) {
                echo json_encode(["status" => "success", "message" => "Récit created successfully"]);
                http_response_code(201);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to create récit"]);
                http_response_code(500);
            }
        } catch (Exception $exception) {
            echo json_encode(["status" => "error", "message" => "Error: " . $exception->getMessage()]);
        }
    }


    // Méthode pour supprimer un récit par son slug
    public function deleteRecit(string $slug): void {
        try {
            $success = $this->repository->deleteBySlug($slug);
            if ($success) {
                echo json_encode(["status" => "success", "message" => "Récit deleted successfully"]);
                http_response_code(200);
            } else {
                echo json_encode(["status" => "error", "message" => "Récit not found"]);
                http_response_code(404);
            }
        } catch (Exception $exception) {
            echo json_encode(["status" => "error", "message" => "Error: " . $exception->getMessage()]);
            http_response_code(500);
        }
    }


    // Méthode pour mettre à jour un récit par son slug
    public function updateRecit(string $slug): void {
        try {
            $recit = $this->repository->findBySlug($slug);
            if (!$recit) {
                echo json_encode(["status" => "error", "message" => "Récit not found"]);
                http_response_code(404);
                return;
            }

            $data = json_decode(file_get_contents("php://input"), true);
            $recit->setTitle($data['title'] ?? $recit->getTitle());
            $recit->setDescription($data['description'] ?? $recit->getDescription());
            $recit->setLastUpdateDate(date('Y-m-d H:i:s'));

            $this->repository->update($recit->getId(), $recit);
            echo json_encode(["status" => "success", "message" => "Récit updated successfully"]);
        } catch (Exception $exception) {
            echo json_encode(["status" => "error", "message" => "Error: " . $exception->getMessage()]);
            http_response_code(500);
        }
    }

}
?>
