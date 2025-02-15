<?php

namespace App\Database\Migrations;

use App\Database\Connection;

class CreateTagsTable
{
    public function up()
    {
        $conn = Connection::getInstance();
        $sql = "
            CREATE TABLE IF NOT EXISTS tags (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL UNIQUE
            )
        ";
        $conn->exec($sql);
    }
}