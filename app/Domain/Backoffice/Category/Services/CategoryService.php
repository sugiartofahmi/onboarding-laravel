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
use App\Domain\Backoffice\AuditLog\Enums\AuditLogActionType;
use App\Domain\Backoffice\AuditLog\Services\AuditLogService;
use App\Infrastructure\Exceptions\NotFoundException;
use App\Domain\Backoffice\Category\Messages\CategoryMessage;
use Illuminate\Support\Facades\Log;

class CategoryService
{
    public function __construct(
        private CategoryQueryRepository $categoryQueryRepository,
        private CategoryStoreRepository $categoryStoreRepository,
        private AuditLogService $auditLogService
    ) {}

    public function index(CategoryIndexRequest $request): CategoryIndexResponse
    {
        $perPage = $request->input('per_page', 10);
        $filters = $request->only(['search']);

        $categories = $this->categoryQueryRepository->index($perPage, $filters);

        $this->auditLogService->logViewEvent(
            action: AuditLogActionType::VIEWED,
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

        $this->auditLogService->logViewEvent(
            action: AuditLogActionType::VIEWED,
            description: "Viewed Category: {$category->name}"
        );

        return new CategoryShowResponse($category);
    }

    public function create(CategoryCreateRequest $request): CategoryCreateResponse
    {
        try {
            $category = $this->categoryStoreRepository->create([
                'name' => $request->input('name'),
                'slug' => $request->input('slug'),
            ]);

            return new CategoryCreateResponse($category);
        } catch (\Exception $e) {
            Log::error('Failed to create Category', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            throw $e;
        }
    }

    public function update(string $id, CategoryUpdateRequest $request): CategoryUpdateResponse
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Failed to update Category', [
                'entity_id' => $id,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            throw $e;
        }
    }

    public function delete(string $id): CategoryDeleteResponse
    {
        try {
            $category = $this->categoryQueryRepository->findOneById($id);

            if (!$category) {
                throw new NotFoundException(CategoryMessage::NOT_FOUND);
            }

            $this->categoryStoreRepository->delete($category);

            return new CategoryDeleteResponse($id);
        } catch (\Exception $e) {
            Log::error('Failed to delete Category', [
                'entity_id' => $id,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            throw $e;
        }
    }
}
