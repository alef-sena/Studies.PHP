<?php

namespace App\Database\Migrations;

use App\Database\Connection;

class CreateProductTagTable
{
    public function up()
    {
        $conn = Connection::getInstance();
        $sql = "
            CREATE TABLE IF NOT EXISTS product_tag (
                product_id INT,
                tag_id INT,
                PRIMARY KEY (product_id, tag_id),
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
                FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
            )
        ";
        $conn->exec($sql);
    }
}