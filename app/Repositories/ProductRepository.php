<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Database\Connection;

class ProductRepository implements ProductRepositoryInterface
{
    private $conn;

    public function __construct()
    {
        $this->conn = Connection::getInstance();
    }

    public function getAll()
    {
        try {
            $sql = "SELECT * FROM products";
            $stmt = $this->conn->query($sql);
            $products = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($products as &$product) {
                $sql = "SELECT t.id, t.name FROM tags t
                        INNER JOIN product_tag pt ON t.id = pt.tag_id
                        WHERE pt.product_id = :product_id";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute(['product_id' => $product['id']]);
                $tags = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
                $product['tags'] = $tags;

                $product['price'] = (float) $product['price'];
            }
    
            return $products;
        } catch (\PDOException $e) {
            throw new \Exception('Error fetching products: ' . $e->getMessage(), 500);
        }
    }

    public function getById($id)
    {
        try {
            $sql = "SELECT * FROM products WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['id' => $id]);
            $product = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$product) {
                throw new \Exception('Product not found.', 404);
            }

            $sql = "SELECT t.id, t.name FROM tags t
                    INNER JOIN product_tag pt ON t.id = pt.tag_id
                    WHERE pt.product_id = :product_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['product_id' => $id]);
            $tags = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $product['tags'] = $tags;

            $product['price'] = (float) $product['price'];

            return $product;
        } catch (\PDOException $e) {
            throw new \Exception('Error searching for product: ' . $e->getMessage(), 500);
        }
    }

    public function create(array $data)
    {
        try {
            $requiredFields = ['name', 'description', 'price', 'category_id'];
            $missingFields = [];
    
            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    $missingFields[] = "'$field'";
                }
            }

            if (!empty($missingFields)) {
                throw new \Exception('The following fields are required: ' . implode(', ', $missingFields), 400);
            }
    
            $sql = "INSERT INTO products (name, description, price, category_id) VALUES (:name, :description, :price, :category_id)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($data);

            $lastInsertId = $this->conn->lastInsertId();

            return $this->getById($lastInsertId);
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') {
                throw new \Exception('Product already exists.', 409);
            }
            throw new \Exception('Error creating product: ' . $e->getMessage(), 500);
        }
    }

    public function update($id, array $data)
    {
        try {
            $sql = "UPDATE products SET name = :name, description = :description, price = :price, category_id = :category_id, likes = :likes WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'id' => $id,
                'name' => $data['name'],
                'description' => $data['description'],
                'price' => $data['price'],
                'category_id' => $data['category_id'],
                'likes' => $data['likes']
            ]);
        } catch (\PDOException $e) {
            throw new \Exception('Error updating product: ' . $e->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function addTag($productId, $tagId)
    {
        try {
            $sql = "INSERT INTO product_tag (product_id, tag_id) VALUES (:product_id, :tag_id)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                'product_id' => $productId,
                'tag_id' => $tagId
            ]);
        } catch (\PDOException $e) {
            throw new \Exception('Erro ao vincular tag ao produto: ' . $e->getMessage(), 500);
        }
    }

    public function getTags($productId)
    {
        try {
            $sql = "SELECT t.id, t.name FROM tags t
                    INNER JOIN product_tag pt ON t.id = pt.tag_id
                    WHERE pt.product_id = :product_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['product_id' => $productId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception('Erro ao buscar tags do produto: ' . $e->getMessage(), 500);
        }
    }
}