<?php

namespace App\Controllers;

use App\Services\TagService;
use App\Utils\Validator;

class TagController
{
    private $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    public function index()
    {
        try {
            $tags = $this->tagService->getAllTags();
            echo json_encode($tags);
        } catch (\Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            $tag = $this->tagService->getTagById($id);
            echo json_encode($tag);
        } catch (\Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    public function store(array $data)
    {
        try {
            $requiredFields = ['name'];
            $missingFields = [];

            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    $missingFields[] = "'$field'";
                }
            }

            if (!empty($missingFields)) {
                http_response_code(400);
                echo json_encode([
                    'message' => 'The following fields are required: ' . implode(', ', $missingFields)
                ]);
                return;
            }

            Validator::validateString($data['name'], 'name');

            $id = $this->tagService->createTag($data);

            http_response_code(201);
            echo json_encode([
                'id' => $id,
                'name' => $data['name']
            ]);
        } catch (\Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    public function update($id, array $data, $partial = false)
    {
        try {
            if (!$partial && (empty($data['name']))) {
                throw new \Exception('All fields are required for full update.', 400);
            }
            if (isset($data['name'])) {
                Validator::validateString($data['name'], 'name');
            }

            $this->tagService->updateTag($id, $data, $partial);

            $updatedTag = $this->tagService->getTagById($id);

            http_response_code(200);
            echo json_encode($updatedTag);
        } catch (\Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $this->tagService->deleteTag($id);
        http_response_code(204);
    }
}