<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\User\Observers;

use App\Domain\Backoffice\AuditLog\Enums\AuditLogActionType;
use App\Domain\Backoffice\AuditLog\Services\AuditLogService;
use App\Models\User;

class UserObserver
{
    public function __construct(
        private AuditLogService $auditLogService
    ) {}

    public function created(User $user): void
    {
        $this->auditLogService->logModelEvent(
            model: $user,
            action: AuditLogActionType::CREATED,
            description: "Created User: {$user->name} ({$user->email})"
        );
    }

    public function updated(User $user): void
    {
        $this->auditLogService->logModelEvent(
            model: $user,
            action: AuditLogActionType::UPDATED,
            description: "Updated User: {$user->name} ({$user->email})"
        );
    }

    public function deleted(User $user): void
    {
        $this->auditLogService->logModelEvent(
            model: $user,
            action: AuditLogActionType::DELETED,
            description: "Deleted User: {$user->name} ({$user->email})"
        );
    }
}
