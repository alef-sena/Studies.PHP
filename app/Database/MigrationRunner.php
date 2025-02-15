<?php

namespace App\Database;

use App\Database\Migrations\CreateCategoriesTable;
use App\Database\Migrations\CreateTagsTable;
use App\Database\Migrations\CreateProductsTable;
use App\Database\Migrations\CreateProductTagTable;

class MigrationRunner
{
    public function run()
    {
        $migrations = [
            new CreateCategoriesTable(),
            new CreateTagsTable(),
            new CreateProductsTable(),
            new CreateProductTagTable(),
        ];

        foreach ($migrations as $migration) {
            $migration->up();
        }
    }
}