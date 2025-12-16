<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Role\Observers;

use App\Domain\Backoffice\AuditLog\Services\AuditLogService;
use App\Domain\Backoffice\AuditLog\Enums\AuditLogActionType;
use App\Models\Role;
use Illuminate\Support\Str;

class RoleObserver
{
    public function __construct(
        private AuditLogService $auditLogService
    ) {}

    public function creating(Role $role): void
    {
        $this->generateSlugIfNeeded($role);
    }

    public function updating(Role $role): void
    {
        $this->generateSlugIfNeeded($role);
    }

    public function created(Role $role): void
    {
        $this->auditLogService->logModelEvent(
            model: $role,
            action: AuditLogActionType::CREATED,
            description: "Created Role: {$role->name}"
        );
    }

    public function updated(Role $role): void
    {
        $this->auditLogService->logModelEvent(
            model: $role,
            action: AuditLogActionType::UPDATED,
            description: "Updated Role: {$role->name}"
        );
    }

    public function deleted(Role $role): void
    {
        $this->auditLogService->logModelEvent(
            model: $role,
            action: AuditLogActionType::DELETED,
            description: "Deleted Role: {$role->name}"
        );
    }

    private function generateSlugIfNeeded(Role $role): void
    {
        if (empty($role->guard_name)) {
            $baseSlug = Str::slug($role->name);
            $slug = $baseSlug;
            $counter = 1;

            while (Role::where('guard_name', $slug)
                ->where('id', '!=', $role->id ?? '')
                ->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $role->guard_name = $slug;
        }
    }
}
