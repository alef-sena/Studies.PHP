<?php

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

return [
    'host' => $_ENV['MYSQL_HOST'] ?? 'db',
    'dbname' => $_ENV['MYSQL_DATABASE'] ?? 'root',
    'username' => $_ENV['MYSQL_USER'] ?? 'root',
    'password' => $_ENV['MYSQL_PASSWORD'] ?? ''
];