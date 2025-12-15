<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Permission\Services;

use App\Domain\Backoffice\AuditLog\Services\AuditLogService;
use App\Domain\Backoffice\Permission\Messages\PermissionErrorMessage;
use App\Domain\Backoffice\Permission\Repositories\PermissionQueryRepository;
use App\Domain\Backoffice\Permission\Repositories\PermissionStoreRepository;
use App\Domain\Backoffice\Permission\Requests\PermissionCreateRequest;
use App\Domain\Backoffice\Permission\Requests\PermissionIndexRequest;
use App\Domain\Backoffice\Permission\Requests\PermissionUpdateRequest;
use App\Domain\Backoffice\Permission\Responses\PermissionCreateResponse;
use App\Domain\Backoffice\Permission\Responses\PermissionDeleteResponse;
use App\Domain\Backoffice\Permission\Responses\PermissionIndexResponse;
use App\Domain\Backoffice\Permission\Responses\PermissionShowResponse;
use App\Domain\Backoffice\Permission\Responses\PermissionUpdateResponse;
use App\Infrastructure\Enums\AuditActionType;
use App\Infrastructure\Exceptions\ForbiddenException;
use App\Infrastructure\Exceptions\NotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PermissionService
{
    private const CACHE_PREFIX = 'permissions:role:';
    private const CACHE_TTL = 3600; // 1 hour

    public function __construct(
        private PermissionQueryRepository $permissionQueryRepository,
        private PermissionStoreRepository $permissionStoreRepository,
        private AuditLogService $auditLogService
    ) {}

    public function checkPermission(string $roleId, string $requiredPermission): void
    {
        $permissions = $this->getPermissionsByRoleIdWithCache($roleId);

        if (!in_array($requiredPermission, $permissions, true)) {
            throw new ForbiddenException("You don't have permission to access this resource");
        }
    }

    public function getPermissionsByRoleIdWithCache(string $roleId): array
    {
        $cacheKey = self::CACHE_PREFIX . $roleId;

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($roleId) {
            $permissions = $this->permissionQueryRepository->findManyByRoleId($roleId);

            return $permissions->pluck('guard_name')->toArray();
        });
    }

    public function clearPermissionsCacheByRoleId(string $roleId): void
    {
        Cache::forget(self::CACHE_PREFIX . $roleId);
    }

    public function index(PermissionIndexRequest $request): PermissionIndexResponse
    {
        $permissions = $this->permissionQueryRepository->index($request);

        $this->auditLogService->logViewEvent(
            action: AuditActionType::VIEWED,
            description: 'Viewed Permission List'
        );

        return new PermissionIndexResponse($permissions);
    }

    public function show(string $id): PermissionShowResponse
    {
        $permission = $this->permissionQueryRepository->findOneById($id);

        if (!$permission) {
            throw new NotFoundException(PermissionErrorMessage::NOT_FOUND);
        }

        $this->auditLogService->logViewEvent(
            action: AuditActionType::VIEWED,
            description: "Viewed Permission: {$permission->name}"
        );

        return new PermissionShowResponse($permission);
    }

    public function create(PermissionCreateRequest $request): PermissionCreateResponse
    {
        try {
            $permission = $this->permissionStoreRepository->create([
                'name' => $request->input('name'),
                'guard_name' => $request->input('guard_name'),
            ]);

            // Observer handles audit logging

            return new PermissionCreateResponse($permission); // NO fresh()
        } catch (\Exception $e) {
            Log::error('Failed to create Permission', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            throw $e;
        }
    }

    public function update(string $id, PermissionUpdateRequest $request): PermissionUpdateResponse
    {
        try {
            $permission = $this->permissionQueryRepository->findOneById($id);

            if (!$permission) {
                throw new NotFoundException(PermissionErrorMessage::NOT_FOUND);
            }

            $data = array_filter([
                'name' => $request->input('name'),
                'guard_name' => $request->input('guard_name'),
            ], fn ($value) => $value !== null);

            $permission = $this->permissionStoreRepository->update($permission, $data);

            // Observer handles audit logging & cache clearing

            return new PermissionUpdateResponse($permission); // NO fresh()
        } catch (\Exception $e) {
            Log::error('Failed to update Permission', [
                'entity_id' => $id,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            throw $e;
        }
    }

    public function delete(string $id): PermissionDeleteResponse
    {
        try {
            $permission = $this->permissionQueryRepository->findOneById($id);

            if (!$permission) {
                throw new NotFoundException(PermissionErrorMessage::NOT_FOUND);
            }

            $this->permissionStoreRepository->delete($permission);

            // Observer handles audit logging & cache clearing

            return new PermissionDeleteResponse($id);
        } catch (\Exception $e) {
            Log::error('Failed to delete Permission', [
                'entity_id' => $id,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            throw $e;
        }
    }
}
