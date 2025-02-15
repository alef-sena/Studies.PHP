<?php

namespace App\Services;

use App\Interfaces\ProductRepositoryInterface;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\TagRepositoryInterface;

class ProductService
{
    private $productRepository;
    private $categoryRepository;
    private $tagRepository;

    public function __construct(ProductRepositoryInterface $productRepository, CategoryRepositoryInterface $categoryRepository, TagRepositoryInterface $tagRepository)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
    }

    public function getAllProducts()
    {
        try {
            return $this->productRepository->getAll();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function getProductById($id)
    {
        try {
            return $this->productRepository->getById($id);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function createProduct(array $data)
    {
        try {
            return $this->productRepository->create($data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function updateProduct($id, array $data, $partial = false)
    {
        try {
            $existingProduct = $this->productRepository->getById($id);
            if (!$existingProduct) {
                throw new \Exception('Product not found.', 404);
            }

            $updateData = $partial ? array_merge($existingProduct, $data) : $data;

            if (isset($data['category_id'])) {
                $category = $this->categoryRepository->getById($data['category_id']);
                if (!$category) {
                    throw new \Exception("Category {$data['category_id']} not found.", 400);
                }
            }

            $this->productRepository->update($id, $updateData);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function deleteProduct($id)
    {
        try {
            return $this->productRepository->delete($id);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function addTag($productId, $tagId)
    {
        try {
            $product = $this->productRepository->getById($productId);

            if (!$product) {
                throw new \Exception('Product not found', 404);
            }

            $tag = $this->tagRepository->getById($tagId);

            if (!$tag) {
                throw new \Exception('Tag not found', 404);
            }

            $this->productRepository->addTag($productId, $tagId);

            return $this->productRepository->getById($productId);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function getTags($productId)
    {
        try {
            return $this->productRepository->getTags($productId);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }
}