<?php

namespace App\Database\Migrations;

use App\Database\Connection;

class CreateProductsTable
{
    public function up()
    {
        $conn = Connection::getInstance();
        $sql = "
            CREATE TABLE IF NOT EXISTS products (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL UNIQUE,
                description TEXT,
                price DECIMAL(10, 2) NOT NULL,
                category_id INT NOT NULL,
                likes INT DEFAULT 0,
                FOREIGN KEY (category_id) REFERENCES categories(id)
            )
        ";
        $conn->exec($sql);
    }
}