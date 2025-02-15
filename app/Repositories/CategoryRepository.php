<?php

namespace App\Repositories;

use App\Interfaces\CategoryRepositoryInterface;
use App\Database\Connection;

class CategoryRepository implements CategoryRepositoryInterface
{
    private $conn;

    public function __construct()
    {
        $this->conn = Connection::getInstance();
    }

    public function getAll()
    {
        try {
            $sql = "SELECT * FROM categories";
            $stmt = $this->conn->query($sql);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception('Error fetching categories: ' . $e->getMessage(), 500);
        }
    }

    public function getById($id)
    {
        try {
            $sql = "SELECT * FROM categories WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['id' => $id]);
            $category = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$category) {
                throw new \Exception('Category not found.', 404);
            }

            return $category;
        } catch (\PDOException $e) {
            throw new \Exception('Error searching for category: ' . $e->getMessage(), 500);
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
    
            $sql = "INSERT INTO categories (name) VALUES (:name)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($data);
            return $this->conn->lastInsertId();
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') {
                throw new \Exception('Category name already exists.', 409);
            }
            throw $e;
        }
    }

    public function update($id, array $data)
    {
        try {
            $sql = "UPDATE categories SET name = :name WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'id' => $id,
                'name' => $data['name']
            ]);
        } catch (\PDOException $e) {
            throw new \Exception('Error updating category: ' . $e->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM categories WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function hasProducts($categoryId)
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM products WHERE category_id = :category_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['category_id' => $categoryId]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            return $result['total'] > 0;
        } catch (\PDOException $e) {
            throw new \Exception('Error checking linked products: ' . $e->getMessage(), 500);
        }
    }

    public function getCategoryProducts($categoryId)
    {
        try {
            $sql = "SELECT * FROM products WHERE category_id = :category_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['category_id' => $categoryId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception('Error fetching products by category: ' . $e->getMessage(), 500);
        }
    }
}