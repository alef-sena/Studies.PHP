<?php

namespace App\Controllers;

use App\Services\CategoryService;
use App\Utils\Validator;

class CategoryController
{
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        try {
            $categorys = $this->categoryService->getAllCategories();
            echo json_encode($categorys);
        } catch (\Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            $category = $this->categoryService->getCategoryById($id);
            echo json_encode($category);
        } catch (\Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    public function store(array $data)
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
                http_response_code(400);
                echo json_encode([
                    'message' => 'The following fields are required: ' . implode(', ', $missingFields)
                ]);
                return;
            }

            Validator::validateString($data['name'], 'name');

            $id = $this->categoryService->createCategory($data);

            http_response_code(201);
            echo json_encode([
                'id' => $id,
                'name' => $data['name']
            ]);
        } catch (\Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    public function update($id, array $data, $partial = false)
    {
        try {
            if (!$partial && (empty($data['name']))) {
                throw new \Exception('All fields are required for full update.', 400);
            }
            if (isset($data['name'])) {
                Validator::validateString($data['name'], 'name');
            }

            $this->categoryService->updateCategory($id, $data, $partial);

            $updatedCategory = $this->categoryService->getCategoryById($id);

            http_response_code(200);
            echo json_encode($updatedCategory);
        } catch (\Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $this->categoryService->deleteCategory($id);
            http_response_code(204);
        } catch (\Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    public function listProducts($categoryId)
    {
        try {
            $products = $this->categoryService->getCategoryProducts($categoryId);
            echo json_encode($products);
        } catch (\Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(['message' => $e->getMessage()]);
        }
    }
}