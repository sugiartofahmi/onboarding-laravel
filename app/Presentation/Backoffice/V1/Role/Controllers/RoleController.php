<?php

declare(strict_types=1);

namespace App\Presentation\Backoffice\V1\Role\Controllers;

use App\Domain\Backoffice\Permission\Constants\PermissionConstant;
use App\Domain\Backoffice\Role\Requests\RoleCreateRequest;
use App\Domain\Backoffice\Role\Requests\RoleIndexRequest;
use App\Domain\Backoffice\Role\Requests\RoleUpdateRequest;
use App\Domain\Backoffice\Role\Services\RoleService;
use App\Infrastructure\Attributes\PermissionAttribute;
use Illuminate\Http\JsonResponse;

class RoleController
{
    public function __construct(
        private RoleService $roleService
    ) {}

    #[PermissionAttribute(PermissionConstant::READ_ROLE)]
    public function index(RoleIndexRequest $request): JsonResponse
    {
        $response = $this->roleService->index($request);

        return $response->toJsonResponse();
    }

    #[PermissionAttribute(PermissionConstant::READ_ROLE)]
    public function show(string $id): JsonResponse
    {
        $response = $this->roleService->show($id);

        return $response->toJsonResponse();
    }

    #[PermissionAttribute(PermissionConstant::CREATE_ROLE)]
    public function store(RoleCreateRequest $request): JsonResponse
    {
        $response = $this->roleService->create($request);

        return $response->toJsonResponse();
    }

    #[PermissionAttribute(PermissionConstant::UPDATE_ROLE)]
    public function update(string $id, RoleUpdateRequest $request): JsonResponse
    {
        $response = $this->roleService->update($id, $request);

        return $response->toJsonResponse();
    }

    #[PermissionAttribute(PermissionConstant::DELETE_ROLE)]
    public function destroy(string $id): JsonResponse
    {
        $response = $this->roleService->delete($id);

        return $response->toJsonResponse();
    }
}
