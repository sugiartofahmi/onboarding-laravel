<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Category\Observers;

use App\Infrastructure\Enums\AuditActionType;
use App\Infrastructure\Services\AuditService;
use App\Models\Category;

class CategoryObserver
{
    public function __construct(
        private AuditService $auditService
    ) {}

    public function created(Category $category): void
    {
        $this->auditService->logModelEvent(
            model: $category,
            action: AuditActionType::CREATED,
            description: "Created Category: {$category->name}"
        );
    }

    public function updated(Category $category): void
    {
        $this->auditService->logModelEvent(
            model: $category,
            action: AuditActionType::UPDATED,
            description: "Updated Category: {$category->name}"
        );
    }

    public function deleted(Category $category): void
    {
        $this->auditService->logModelEvent(
            model: $category,
            action: AuditActionType::DELETED,
            description: "Deleted Category: {$category->name}"
        );
    }
}
