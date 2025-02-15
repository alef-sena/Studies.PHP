<?php

namespace App\Services;

use App\Interfaces\TagRepositoryInterface;

class TagService
{
    private $tagRepository;

    public function __construct(TagRepositoryInterface $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function getAllTags()
    {
        try {
            return $this->tagRepository->getAll();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function getTagById($id)
    {
        try {
            return $this->tagRepository->getById($id);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function createTag(array $data)
    {
        try {
            return $this->tagRepository->create($data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function updateTag($id, array $data, $partial = false)
    {
        try {
            $existingTag = $this->tagRepository->getById($id);
            if (!$existingTag) {
                throw new \Exception('Tag not found.', 404);
            }

            $updateData = $partial ? array_merge($existingTag, $data) : $data;

            $this->tagRepository->update($id, $updateData);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function deleteTag($id)
    {
        try {
            return $this->tagRepository->delete($id);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }
}