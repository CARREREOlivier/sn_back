<?php

namespace RecitController;

use RecitRepository\RecitRepository;

class RecitController
{
    private $repository;

    public function __construct() {
        $this->repository = new RecitRepository();
    }

    public function getAllRecits() {
        $recits = $this->repository->findAll();
        echo json_encode($recits);
    }

    public function getRecit($id) {
        $recit = $this->repository->findById($id);
        echo json_encode($recit);
    }

    public function createRecit($data) {
        $newRecit = new RecitModel();
        $newRecit->setTitle($data['title']);
        $newRecit->setDescription($data['description']);
        $this->repository->create($newRecit);
        echo json_encode(['message' => 'Récit created successfully']);
    }

    public function updateRecit($id, $data) {
        $recit = $this->repository->findById($id);
        if ($recit) {
            $recit->setTitle($data['title']);
            $recit->setDescription($data['description']);
            $this->repository->update($recit);
            echo json_encode(['message' => 'Récit updated successfully']);
        } else {
            echo json_encode(['message' => 'Récit not found']);
        }
    }

    public function deleteRecit($id) {
        $this->repository->delete($id);
        echo json_encode(['message' => 'Récit deleted successfully']);
    }
}