<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Category\Observers;

use App\Domain\Backoffice\AuditLog\Enums\AuditLogActionType;
use App\Domain\Backoffice\AuditLog\Services\AuditLogService;
use App\Domain\Backoffice\Dashboard\Repositories\StatisticsSummaryStoreRepository;
use App\Models\Category;

class CategoryObserver
{
    public function __construct(
        private AuditLogService $auditLogService,
        private StatisticsSummaryStoreRepository $statisticsSummaryStoreRepository
    ) {}

    public function created(Category $category): void
    {
        $this->statisticsSummaryStoreRepository->incrementTotalCategories();

        $this->auditLogService->logModelEvent(
            model: $category,
            action: AuditLogActionType::CREATED,
            description: "Created Category: {$category->name}"
        );
    }

    public function updated(Category $category): void
    {
        $this->auditLogService->logModelEvent(
            model: $category,
            action: AuditLogActionType::UPDATED,
            description: "Updated Category: {$category->name}"
        );
    }

    public function deleted(Category $category): void
    {
        $this->statisticsSummaryStoreRepository->decrementTotalCategories();

        $this->auditLogService->logModelEvent(
            model: $category,
            action: AuditLogActionType::DELETED,
            description: "Deleted Category: {$category->name}"
        );
    }
}
