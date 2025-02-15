<?php

namespace App\Interfaces;

// use App\Models\Category;

interface CategoryRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function hasProducts($categoryId);
    public function getCategoryProducts($categoryId);
}