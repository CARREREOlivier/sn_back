<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\UserRepository;
use Exception;
require_once __DIR__ . '/../repositories/UserRepository.php';
class UserController
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function getAllUsers(): void
    {
        try {
            $users = $this->userRepository->findAll();
            echo json_encode($users);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            http_response_code(500);
        }
    }

    public function getUserById(int $id): void
    {
        try {
            $user = $this->userRepository->findById($id);
            if ($user) {
                echo json_encode($user);
            } else {
                echo json_encode(["status" => "error", "message" => "User not found"]);
                http_response_code(404);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            http_response_code(500);
        }
    }

    public function createUser(): void
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['username'], $data['email'], $data['password'], $data['role_id'])) {
            echo json_encode(["status" => "error", "message" => "Invalid data"]);
            http_response_code(400);
            return;
        }

        try {
            $created = $this->userRepository->create($data);
            if ($created) {
                echo json_encode(["status" => "success", "message" => "User created successfully"]);
                http_response_code(201);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to create user"]);
                http_response_code(500);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            http_response_code(500);
        }
    }

    public function updateUser(int $id): void
    {
        $data = json_decode(file_get_contents("php://input"), true);

        try {
            $updated = $this->userRepository->update($id, $data);
            if ($updated) {
                echo json_encode(["status" => "success", "message" => "User updated successfully"]);
            } else {
                echo json_encode(["status" => "error", "message" => "User not found or failed to update"]);
                http_response_code(404);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            http_response_code(500);
        }
    }

    public function deleteUser(int $id): void
    {
        try {
            $deleted = $this->userRepository->delete($id);
            if ($deleted) {
                echo json_encode(["status" => "success", "message" => "User deleted successfully"]);
            } else {
                echo json_encode(["status" => "error", "message" => "User not found"]);
                http_response_code(404);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            http_response_code(500);
        }
    }
}
