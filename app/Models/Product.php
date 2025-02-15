<?php

namespace App\Models;

class Product
{
    public $id;
    public $name;
    public $description;
    public $price;
    public $category_id;
    public $likes;

    public function __construct($id, $name, $description, $price, $category_id, $likes = 0)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->category_id = $category_id;
        $this->likes = $likes;
    }
}