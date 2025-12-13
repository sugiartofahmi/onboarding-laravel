<?php

declare(strict_types=1);

namespace App\Domain\API\Category\Services;

use App\Domain\API\Category\Requests\CategoryCreateRequest;
use App\Domain\API\Category\Requests\CategoryIndexRequest;
use App\Domain\API\Category\Requests\CategoryUpdateRequest;
use App\Domain\API\Category\Responses\CategoryCreateResponse;
use App\Domain\API\Category\Responses\CategoryDeleteResponse;
use App\Domain\API\Category\Responses\CategoryIndexResponse;
use App\Domain\API\Category\Responses\CategoryShowResponse;
use App\Domain\API\Category\Responses\CategoryUpdateResponse;
use App\Domain\Backoffice\Category\Repositories\CategoryQueryRepository;
use App\Domain\Backoffice\Category\Repositories\CategoryStoreRepository;
use App\Infrastructure\Exceptions\NotFoundException;
use App\Domain\API\Category\Messages\CategoryMessage;

class CategoryService
{
    public function __construct(
        private CategoryQueryRepository $categoryQueryRepository,
        private CategoryStoreRepository $categoryStoreRepository
    ) {}

    public function index(CategoryIndexRequest $request): CategoryIndexResponse
    {
        $perPage = $request->input('per_page', 10);
        $filters = $request->only(['search']);

        $categories = $this->categoryQueryRepository->index($perPage, $filters);

        return new CategoryIndexResponse($categories);
    }

    public function show(string $id): CategoryShowResponse
    {
        $category = $this->categoryQueryRepository->findOneById($id);

        if (!$category) {
            throw new NotFoundException(CategoryMessage::NOT_FOUND);
        }

        return new CategoryShowResponse($category);
    }

    public function create(CategoryCreateRequest $request): CategoryCreateResponse
    {
        $category = $this->categoryStoreRepository->create([
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
        ]);

        return new CategoryCreateResponse($category);
    }

    public function update(string $id, CategoryUpdateRequest $request): CategoryUpdateResponse
    {
        $category = $this->categoryQueryRepository->findOneById($id);

        if (!$category) {
            throw new NotFoundException(CategoryMessage::NOT_FOUND);
        }

        $data = array_filter([
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
        ], fn ($value) => $value !== null);

        $category = $this->categoryStoreRepository->update($category, $data);

        return new CategoryUpdateResponse($category);
    }

    public function delete(string $id): CategoryDeleteResponse
    {
        $category = $this->categoryQueryRepository->findOneById($id);

        if (!$category) {
            throw new NotFoundException(CategoryMessage::NOT_FOUND);
        }

        $this->categoryStoreRepository->delete($category);

        return new CategoryDeleteResponse($id);
    }
}
