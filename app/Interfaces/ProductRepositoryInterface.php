<?php

namespace App\Interfaces;

// use App\Models\Produto;

interface ProductRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function addTag($productId, $tagId);
    public function getTags($productId);
}