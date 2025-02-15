<?php

namespace App\Database\Migrations;

use App\Database\Connection;

class CreateCategoriesTable
{
    public function up()
    {
        $conn = Connection::getInstance();
        $sql = "
            CREATE TABLE IF NOT EXISTS categories (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL UNIQUE
            )
        ";
        $conn->exec($sql);
    }
}