<?php
declare(strict_types=1);

namespace App\Controllers;
require_once __DIR__ . '/../Repositories/NewsRepository.php';

use App\Repositories\NewsRepository;
use App\Models\NewsModel;
use Exception;

class NewsController
{
    private NewsRepository $newsRepository;

    public function __construct()
    {
        $this->newsRepository = new NewsRepository();
    }

    public function getAllNews(): void
    {
        try {
            $newsList = $this->newsRepository->findAll();
            echo json_encode($newsList);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            http_response_code(500);
        }
    }

    public function getNewsById(int $id): void
    {
        try {
            $news = $this->newsRepository->findById($id);
            if ($news) {
                echo json_encode($news->toArray());
            } else {
                echo json_encode(["status" => "error", "message" => "News not found"]);
                http_response_code(404);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            http_response_code(500);
        }
    }

    public function createNews(): void
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['title'], $data['content'], $data['creation_date'], $data['author_id'])) {
            echo json_encode(["status" => "error", "message" => "Invalid data"]);
            http_response_code(400);
            return;
        }

        $news = new NewsModel(null, $data['title'], $data['content'], $data['creation_date'], $data['last_update_date'] ?? null, $data['author_id'], $data['isVisible'] ?? true);

        try {
            $created = $this->newsRepository->create($news);
            if ($created) {
                echo json_encode(["status" => "success", "message" => "News created successfully"]);
                http_response_code(201);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to create news"]);
                http_response_code(500);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            http_response_code(500);
        }
    }

    public function updateNews(int $id): void
    {
        $data = json_decode(file_get_contents("php://input"), true);
        try {
            $news = $this->newsRepository->findById($id);
            if (!$news) {
                echo json_encode(["status" => "error", "message" => "News not found"]);
                http_response_code(404);
                return;
            }
            $news->setTitle($data['title'] ?? $news->getTitle());
            $news->setContent($data['content'] ?? $news->getContent());
            $news->setLastUpdateDate($data['last_update_date'] ?? date('Y-m-d'));
            $news->setIsVisible($data['isVisible'] ?? $news->isVisible());

            $updated = $this->newsRepository->update($id, $news);
            echo json_encode(["status" => $updated ? "success" : "error", "message" => $updated ? "News updated successfully" : "Failed to update news"]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            http_response_code(500);
        }
    }

    public function deleteNews(int $id): void
    {
        try {
            $deleted = $this->newsRepository->delete($id);
            echo json_encode(["status" => $deleted ? "success" : "error", "message" => $deleted ? "News deleted successfully" : "News not found"]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            http_response_code(500);
        }
    }

    /**
     * @return void
     */
    public function getLatestNews(): void
    {
        try {
            $news = $this->newsRepository->findLast();
            if ($news) {
                echo json_encode($news->toArray());
            } else {
                echo json_encode(["status" => "error", "message" => "News not found"]);
                http_response_code(404);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            http_response_code(500);
        }
    }
}
