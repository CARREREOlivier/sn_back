<?php
declare(strict_types=1);

namespace App\Repositories;

require_once __DIR__.'/../Repositories/BaseRepository.php';
require_once __DIR__.'/../Models/NewsModel.php';

use App\Models\NewsModel;
use BaseRepository;
use PDO;
use Exception;

class NewsRepository extends BaseRepository
{
    protected string $table = 'News';
    protected string $primaryKey = 'news_id';

    public function create(mixed $news): bool
    {
        $query = "INSERT INTO {$this->table} (title, content, creation_date, last_update_date, author_id, isVisible)
                  VALUES (:title, :content, :creation_date, :last_update_date, :author_id, :isVisible)";
        $params = [
            ':title' => $news->getTitle(),
            ':content' => $news->getContent(),
            ':creation_date' => $news->getCreationDate(),
            ':last_update_date' => $news->getLastUpdateDate(),
            ':author_id' => $news->getAuthorId(),
            ':isVisible' => $news->isVisible(),
        ];
        return $this->executeQuery($query, $params);
    }

    public function findById(int $id): ?NewsModel
    {
        $query = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $data = $this->fetchSingleRow($query, [':id' => $id]);

        if ($data) {
            return new NewsModel(
                $data['news_id'],
                $data['title'],
                $data['content'],
                $data['creation_date'],
                $data['last_update_date'],
                $data['author_id'],
                (bool)$data['isVisible']  // Conversion explicite en booléen
            );
        }

        return null;
    }


    public function update(int $id, mixed $news): bool
    {
        $query = "UPDATE {$this->table} SET title = :title, content = :content, last_update_date = :last_update_date, isVisible = :isVisible
                  WHERE {$this->primaryKey} = :id";
        $params = [
            ':title' => $news->getTitle(),
            ':content' => $news->getContent(),
            ':last_update_date' => $news->getLastUpdateDate(),
            ':isVisible' => $news->isVisible(),
            ':id' => $id
        ];
        return $this->executeQuery($query, $params);
    }

    public function delete(int $id): bool
    {
        return parent::delete($id);
    }

    public function findAll(): array
    {
        $query = "SELECT * FROM {$this->table}";
        return $this->fetchAllRows($query);
    }

    public function findLast(): ?NewsModel
    {
        $query = "SELECT * FROM {$this->table} ORDER BY last_update_date DESC LIMIT 1";
        $data = $this->fetchSingleRow($query);

        if ($data) {
            return new NewsModel(
                $data['news_id'],
                $data['title'],
                $data['content'],
                $data['creation_date'],
                $data['last_update_date'],
                $data['author_id'],
                (bool)$data['isVisible'] // Conversion explicite en booléen
            );
        }

        return null;
    }

}
