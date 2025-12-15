<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Permission\Observers;

use App\Domain\Backoffice\AuditLog\Services\AuditLogService;
use App\Domain\Backoffice\Permission\Services\PermissionService;
use App\Infrastructure\Enums\AuditActionType;
use App\Models\Permission;
use Illuminate\Support\Str;

class PermissionObserver
{
    public function __construct(
        private AuditLogService $auditLogService,
        private PermissionService $permissionService
    ) {}

    public function creating(Permission $permission): void
    {
        $this->generateSlugIfNeeded($permission);
    }

    public function updating(Permission $permission): void
    {
        $this->generateSlugIfNeeded($permission);
    }

    public function created(Permission $permission): void
    {
        $this->auditLogService->logModelEvent(
            model: $permission,
            action: AuditActionType::CREATED,
            description: "Created Permission: {$permission->name}"
        );
    }

    public function updated(Permission $permission): void
    {
        // Clear cache for all roles that have this permission
        $this->clearRelatedRoleCaches($permission);

        $this->auditLogService->logModelEvent(
            model: $permission,
            action: AuditActionType::UPDATED,
            description: "Updated Permission: {$permission->name}"
        );
    }

    public function deleted(Permission $permission): void
    {
        // Clear cache for all roles that have this permission
        $this->clearRelatedRoleCaches($permission);

        $this->auditLogService->logModelEvent(
            model: $permission,
            action: AuditActionType::DELETED,
            description: "Deleted Permission: {$permission->name}"
        );
    }

    private function generateSlugIfNeeded(Permission $permission): void
    {
        if (empty($permission->guard_name)) {
            $baseSlug = Str::slug($permission->name);
            $slug = $baseSlug;
            $counter = 1;

            while (Permission::where('guard_name', $slug)
                ->where('id', '!=', $permission->id ?? '')
                ->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $permission->guard_name = $slug;
        }
    }

    private function clearRelatedRoleCaches(Permission $permission): void
    {
        // Get all roles that have this permission
        $roles = $permission->roles;

        foreach ($roles as $role) {
            $this->permissionService->clearPermissionsCacheByRoleId($role->id);
        }
    }
}
