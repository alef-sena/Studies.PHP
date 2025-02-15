<?php

namespace App\Services;

use App\Interfaces\CategoryRepositoryInterface;
use App\Repositories\ProductRepository;

class CategoryService
{
    private $categoryRepository;
    private $productRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = new ProductRepository();
    }

    public function getAllCategories()
    {
        try {
            return $this->categoryRepository->getAll();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function getCategoryById($id)
    {
        try {
            return $this->categoryRepository->getById($id);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function createCategory(array $data)
    {
        try {
            return $this->categoryRepository->create($data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function updateCategory($id, array $data, $partial = false)
    {
        try {
            $existingCategory = $this->categoryRepository->getById($id);
            if (!$existingCategory) {
                throw new \Exception('Category not found.', 404);
            }

            $updateData = $partial ? array_merge($existingCategory, $data) : $data;

            $this->categoryRepository->update($id, $updateData);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function deleteCategory($id)
    {
        try {
            if ($this->categoryRepository->hasProducts($id)) {
                throw new \Exception('Cannot delete category because there are products linked to it.', 400);
            }
            return $this->categoryRepository->delete($id);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function getCategoryProducts($categoryId)
    {

        try {
            $products = $this->categoryRepository->getCategoryProducts($categoryId);
    
            foreach ($products as &$product) {
                $product['tags'] = $this->productRepository->getTags($product['id']);
            }
    
            return $products;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }
}