<?php
declare(strict_types=1);

namespace App\Repositories;
require_once __DIR__ . '/BaseRepository.php';

use BaseRepository;
use PDO;
use App\Models\User;
use Exception;

class UserRepository extends BaseRepository
{
    protected string $table = 'users';
    protected string $primaryKey = 'user_id';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param mixed $data
     * @return bool
     */
    public function create(mixed $data): bool
    {
        $query = "INSERT INTO {$this->table} (username, email, password, role_id) 
                  VALUES (:username, :email, :password, :role_id)";
        $params = [
            ':username' => $data['username'],
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_BCRYPT),
            ':role_id' => $data['role_id']
        ];
        return $this->executeQuery($query, $params);
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function findById(int $id): ?array
    {
        $query = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        return $this->fetchSingleRow($query, [':id' => $id]);
    }

    /**
     * @param int $id
     * @param mixed $data
     * @return bool
     */
    public function update(int $id, mixed $data): bool
    {
        $query = "UPDATE {$this->table} SET username = :username, email = :email, role_id = :role_id WHERE {$this->primaryKey} = :id";
        $params = [
            ':username' => $data['username'] ?? null,
            ':email' => $data['email'] ?? null,
            ':role_id' => $data['role_id'] ?? null,
            ':id' => $id
        ];
        return $this->executeQuery($query, $params);
    }

    public function delete(int $id): bool
    {
        return parent::delete($id);
    }
}
