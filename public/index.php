<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\ProductController;
use App\Services\ProductService;
use App\Repositories\ProductRepository;

use App\Controllers\CategoryController;
use App\Services\CategoryService;
use App\Repositories\CategoryRepository;

use App\Controllers\TagController;
use App\Services\TagService;
use App\Repositories\TagRepository;

$categoryRepository = new CategoryRepository();
$categoryService = new CategoryService($categoryRepository);
$categoryController = new CategoryController($categoryService);

$tagRepository = new TagRepository();
$tagService = new TagService($tagRepository);
$tagController = new TagController($tagService);

$productRepository = new ProductRepository($categoryService);
$productService = new ProductService($productRepository, $categoryRepository, $tagRepository);
$productController = new ProductController($productService, $categoryService);

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

header('Content-Type: application/json');

function sendError($message, $statusCode = 400)
{
    http_response_code($statusCode);
    echo json_encode(['message' => $message]);
    exit;
}

try {
    if (preg_match('/^\/products(\/\d+)?$/', $requestUri, $matches)) {
        $param = isset($matches[1]) ? (int) str_replace('/', '', $matches[1]) : null;
        route($requestUri, $requestMethod, $productController, $param);
    } elseif (preg_match('/^\/products\/(\d+)\/tags$/', $requestUri, $matches) && $requestMethod === 'GET') {
        $productId = $matches[1];
        $productController->listTags($productId);
    } elseif (preg_match('/^\/products\/(\d+)\/tags\/(\d+)$/', $requestUri, $matches) && $requestMethod === 'POST') {
        $productId = $matches[1];
        $tagId = $matches[2];
        $productController->addTag($productId, $tagId);
    } elseif (preg_match('/^\/categories(\/\d+)?$/', $requestUri, $matches)) {
        $param = isset($matches[1]) ? (int) str_replace('/', '', $matches[1]) : null;
        route($requestUri, $requestMethod, $categoryController, $param);
    } elseif (preg_match('/^\/categories\/(\d+)\/products$/', $requestUri, $matches) && $requestMethod === 'GET') {
        $categoryId = $matches[1];
        $categoryController->listProducts($categoryId);
    } elseif (preg_match('/^\/tags(\/\d+)?$/', $requestUri, $matches)) {
        $param = isset($matches[1]) ? (int) str_replace('/', '', $matches[1]) : null;
        route($requestUri, $requestMethod, $tagController, $param);
    } else {
        sendError('Route not found.', 404);
    }
} catch (Exception $e) {
    sendError($e->getMessage(), 500);
}

function route($uri, $method, $controller, $param = null)
{
    switch ($method) {
        case 'GET':
            if ($param) {
                $controller->show($param);
            } else {
                $controller->index();
            }
            break;
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                sendError('Invalid JSON.', 400);
            }
            $controller->store($data);
            break;
        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                sendError('Invalid JSON.', 400);
            }
            $controller->update($param, $data, false);
            break;
        case 'PATCH':
            $data = json_decode(file_get_contents('php://input'), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                sendError('Invalid JSON.', 400);
            }
            $controller->update($param, $data, true);
            break;
        case 'DELETE':
            $controller->destroy($param);
            break;
        default:
            sendError('Method not allowed.', 405);
            break;
    }
}
