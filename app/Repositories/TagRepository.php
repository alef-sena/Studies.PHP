<?php

namespace App\Repositories;

use App\Interfaces\TagRepositoryInterface;
use App\Database\Connection;

class TagRepository implements TagRepositoryInterface
{
    private $conn;

    public function __construct()
    {
        $this->conn = Connection::getInstance();
    }

    public function getAll()
    {
        try {
            $sql = "SELECT * FROM tags";
            $stmt = $this->conn->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception('Error fetching tags: ' . $e->getMessage(), 500);
        }
    }

    public function getById($id)
    {
        try {
            $sql = "SELECT * FROM tags WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['id' => $id]);
            $tag = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$tag) {
                throw new \Exception('Tag not found.', 404);
            }

            return $tag;
        } catch (\PDOException $e) {
            throw new \Exception('Error searching for tag: ' . $e->getMessage(), 500);
        }
    }

    public function create(array $data)
    {

        try {
            $requiredFields = ['name'];
            $missingFields = [];
    
            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    $missingFields[] = "'$field'";
                }
            }

            if (!empty($missingFields)) {
                throw new \Exception('The following fields are required: ' . implode(', ', $missingFields), 400);
            }
    
            $sql = "INSERT INTO tags (name) VALUES (:name)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($data);
            return $this->conn->lastInsertId();
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') {
                throw new \Exception('Tag name already exists.', 409);
            }
            throw $e;
        }
    }

    public function update($id, array $data)
    {
        try {
            $sql = "UPDATE tags SET name = :name WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'id' => $id,
                'name' => $data['name']
            ]);
        } catch (\PDOException $e) {
            throw new \Exception('Error updating product: ' . $e->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM tags WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}