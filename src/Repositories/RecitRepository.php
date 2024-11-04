<?php

namespace RecitRepository;

require_once '../../config/database.php';

use RecitModel;
class RecitRepository {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function findAll() {
        $query = "SELECT * FROM recits";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'RecitModel');
    }

    public function findById($id) {
        $query = "SELECT * FROM recits WHERE recit_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchObject('RecitModel');
    }

    public function create($recit) {
        $query = "INSERT INTO recits (title, description) VALUES (:title, :description)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':title', $recit->getTitle());
        $stmt->bindParam(':description', $recit->getDescription());
        $stmt->execute();
    }

    public function update($recit) {
        $query = "UPDATE recits SET title = :title, description = :description WHERE recit_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':title', $recit->getTitle());
        $stmt->bindParam(':description', $recit->getDescription());
        $stmt->bindParam(':id', $recit->getId());
        $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM recits WHERE recit_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}