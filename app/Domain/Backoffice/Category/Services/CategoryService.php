<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Category\Services;

use App\Domain\Backoffice\Category\Requests\CategoryCreateRequest;
use App\Domain\Backoffice\Category\Requests\CategoryIndexRequest;
use App\Domain\Backoffice\Category\Requests\CategoryUpdateRequest;
use App\Domain\Backoffice\Category\Responses\CategoryCreateResponse;
use App\Domain\Backoffice\Category\Responses\CategoryDeleteResponse;
use App\Domain\Backoffice\Category\Responses\CategoryIndexResponse;
use App\Domain\Backoffice\Category\Responses\CategoryShowResponse;
use App\Domain\Backoffice\Category\Responses\CategoryUpdateResponse;
use App\Domain\Backoffice\Category\Repositories\CategoryQueryRepository;
use App\Domain\Backoffice\Category\Repositories\CategoryStoreRepository;
use App\Infrastructure\Exceptions\NotFoundException;
use App\Domain\Backoffice\Category\Messages\CategoryMessage;
use App\Infrastructure\Enums\AuditActionType;
use App\Infrastructure\Services\AuditService;

class CategoryService
{
    public function __construct(
        private CategoryQueryRepository $categoryQueryRepository,
        private CategoryStoreRepository $categoryStoreRepository,
        private AuditService $auditService
    ) {}

    public function index(CategoryIndexRequest $request): CategoryIndexResponse
    {
        $perPage = $request->input('per_page', 10);
        $filters = $request->only(['search']);

        $categories = $this->categoryQueryRepository->index($perPage, $filters);

        $this->auditService->logViewEvent(
            action: AuditActionType::VIEWED,
            description: 'Viewed Category List'
        );

        return new CategoryIndexResponse($categories);
    }

    public function show(string $id): CategoryShowResponse
    {
        $category = $this->categoryQueryRepository->findOneById($id);

        if (!$category) {
            throw new NotFoundException(CategoryMessage::NOT_FOUND);
        }

        $this->auditService->logViewEvent(
            action: AuditActionType::VIEWED,
            description: "Viewed Category: {$category->name}"
        );

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
