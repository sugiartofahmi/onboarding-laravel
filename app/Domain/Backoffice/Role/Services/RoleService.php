<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Role\Services;

use App\Domain\Backoffice\AuditLog\Services\AuditLogService;
use App\Domain\Backoffice\Role\Messages\RoleErrorMessage;
use App\Domain\Backoffice\Role\Repositories\RoleQueryRepository;
use App\Domain\Backoffice\Role\Repositories\RoleStoreRepository;
use App\Domain\Backoffice\Role\Requests\RoleCreateRequest;
use App\Domain\Backoffice\Role\Requests\RoleIndexRequest;
use App\Domain\Backoffice\Role\Requests\RoleUpdateRequest;
use App\Domain\Backoffice\Role\Responses\RoleCreateResponse;
use App\Domain\Backoffice\Role\Responses\RoleDeleteResponse;
use App\Domain\Backoffice\Role\Responses\RoleIndexResponse;
use App\Domain\Backoffice\Role\Responses\RoleShowResponse;
use App\Domain\Backoffice\Role\Responses\RoleUpdateResponse;
use App\Domain\Backoffice\AuditLog\Enums\AuditLogActionType;
use App\Infrastructure\Exceptions\DataNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoleService
{
    public function __construct(
        private RoleQueryRepository $roleQueryRepository,
        private RoleStoreRepository $roleStoreRepository,
        private AuditLogService $auditLogService
    ) {}

    public function index(RoleIndexRequest $request): RoleIndexResponse
    {
        $roles = $this->roleQueryRepository->index($request);

        $this->auditLogService->logViewEvent(
            action: AuditLogActionType::VIEWED,
            description: 'Viewed Role List'
        );

        return new RoleIndexResponse($roles);
    }

    public function show(string $id): RoleShowResponse
    {
        $role = $this->roleQueryRepository->findOneByIdWithPermissions($id);

        if (!$role) {
            throw new DataNotFoundException(RoleErrorMessage::NOT_FOUND);
        }

        $this->auditLogService->logViewEvent(
            action: AuditLogActionType::VIEWED,
            description: "Viewed Role: {$role->name}"
        );

        return new RoleShowResponse($role);
    }

    public function create(RoleCreateRequest $request): RoleCreateResponse
    {
        try {
            $role = DB::transaction(function () use ($request) {
                $role = $this->roleStoreRepository->create([
                    'name' => $request->input('name'),
                    'guard_name' => $request->input('guard_name'),
                ]);

                // Attach permissions if provided
                if ($request->filled('permission_ids')) {
                    $role->permissions()->attach($request->input('permission_ids'));
                }

                return $role; // Return as-is, NO fresh()
            });

            // Observer handles audit logging

            return new RoleCreateResponse($role);
        } catch (\Exception $e) {
            Log::error('Failed to create Role', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            throw $e;
        }
    }

    public function update(string $id, RoleUpdateRequest $request): RoleUpdateResponse
    {
        try {
            $role = $this->roleQueryRepository->findOneById($id);

            if (!$role) {
                throw new DataNotFoundException(RoleErrorMessage::NOT_FOUND);
            }

            $role = DB::transaction(function () use ($role, $request) {
                $data = array_filter([
                    'name' => $request->input('name'),
                    'guard_name' => $request->input('guard_name'),
                ], fn ($value) => $value !== null);

                $role = $this->roleStoreRepository->update($role, $data);

                // Sync permissions if provided
                if ($request->filled('permission_ids')) {
                    $role->permissions()->sync($request->input('permission_ids'));
                }

                return $role; // Return as-is, NO fresh()
            });

            // Observer handles audit logging

            return new RoleUpdateResponse($role);
        } catch (\Exception $e) {
            Log::error('Failed to update Role', [
                'entity_id' => $id,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            throw $e;
        }
    }

    public function delete(string $id): RoleDeleteResponse
    {
        try {
            $role = $this->roleQueryRepository->findOneById($id);

            if (!$role) {
                throw new DataNotFoundException(RoleErrorMessage::NOT_FOUND);
            }

            $this->roleStoreRepository->delete($role);

            // Observer handles audit logging

            return new RoleDeleteResponse($id);
        } catch (\Exception $e) {
            Log::error('Failed to delete Role', [
                'entity_id' => $id,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            throw $e;
        }
    }
}
