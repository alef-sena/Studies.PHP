<?php

namespace App\Controllers;

use App\Services\ProductService;
use App\Services\CategoryService;
use App\Utils\Validator;

class ProductController
{
    private $productService;
    private $categoryService;

    public function __construct(ProductService $productService, CategoryService $categoryService)
    {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        try {
            $products = $this->productService->getAllProducts();
            echo json_encode($products);
        } catch (\Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            $product = $this->productService->getProductById($id);
            echo json_encode($product);
        } catch (\Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    public function store(array $data)
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
                http_response_code(400);
                echo json_encode([
                    'message' => 'The following fields are required: ' . implode(', ', $missingFields)
                ]);
                return;
            }

            Validator::validateString($data['name'], 'name');
            Validator::validateString($data['description'], 'description');
            Validator::validateDecimal($data['price'], 'price');
            Validator::validateInteger($data['category_id'], 'category_id');

            $category = $this->categoryService->getCategoryById($data['category_id']);

            if (!$category) {
                http_response_code(400);
                echo json_encode([
                    'message' => "Category {$data['category_id']} not found."
                ]);
                return;
            }

            $createdProduct = $this->productService->createProduct($data);

            http_response_code(201);
            echo json_encode($createdProduct, JSON_NUMERIC_CHECK);
        } catch (\Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    public function update($id, array $data, $partial = false)
    {
        try {
            if (!$partial && (empty($data['name']) || empty($data['description']) || empty($data['price']) || empty($data['category_id']) || !isset($data['likes']))) {
                throw new \Exception('All fields are required for full update.', 400);
            }
            if (isset($data['name'])) {
                Validator::validateString($data['name'], 'name');
            }
            if (isset($data['description'])) {
                Validator::validateString($data['description'], 'description');
            }
            if (isset($data['price'])) {
                Validator::validateDecimal($data['price'], 'price');
            }
            if (isset($data['category_id'])) {
                Validator::validateInteger($data['category_id'], 'category_id');
            }
            if (isset($data['likes'])) {
                Validator::validateInteger($data['likes'], 'likes');
            }

            $this->productService->updateProduct($id, $data, $partial);

            $updatedProduct = $this->productService->getProductById($id);

            http_response_code(200);
            echo json_encode($updatedProduct);
        } catch (\Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $this->productService->deleteProduct($id);
        http_response_code(204);
    }

    public function addTag($productId, $tagId)
    {
        try {
            $product = $this->productService->addTag($productId, $tagId);
            http_response_code(200);
            echo json_encode($product);
        } catch (\Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    public function listTags($productId)
    {
        try {
            $tags = $this->productService->getTags($productId);
            echo json_encode($tags);
        } catch (\Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(['message' => $e->getMessage()]);
        }
    }
}