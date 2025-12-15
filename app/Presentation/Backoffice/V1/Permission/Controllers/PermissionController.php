<?php

declare(strict_types=1);

namespace App\Presentation\Backoffice\V1\Permission\Controllers;

use App\Domain\Backoffice\Permission\Constants\PermissionConstant;
use App\Domain\Backoffice\Permission\Requests\PermissionCreateRequest;
use App\Domain\Backoffice\Permission\Requests\PermissionIndexRequest;
use App\Domain\Backoffice\Permission\Requests\PermissionUpdateRequest;
use App\Domain\Backoffice\Permission\Services\PermissionService;
use App\Infrastructure\Attributes\PermissionAttribute;
use Illuminate\Http\JsonResponse;

class PermissionController
{
    public function __construct(
        private PermissionService $permissionService
    ) {}

    #[PermissionAttribute(PermissionConstant::READ_PERMISSION)]
    public function index(PermissionIndexRequest $request): JsonResponse
    {
        $response = $this->permissionService->index($request);

        return $response->toJsonResponse();
    }

    #[PermissionAttribute(PermissionConstant::READ_PERMISSION)]
    public function show(string $id): JsonResponse
    {
        $response = $this->permissionService->show($id);

        return $response->toJsonResponse();
    }

    #[PermissionAttribute(PermissionConstant::CREATE_PERMISSION)]
    public function store(PermissionCreateRequest $request): JsonResponse
    {
        $response = $this->permissionService->create($request);

        return $response->toJsonResponse();
    }

    #[PermissionAttribute(PermissionConstant::UPDATE_PERMISSION)]
    public function update(string $id, PermissionUpdateRequest $request): JsonResponse
    {
        $response = $this->permissionService->update($id, $request);

        return $response->toJsonResponse();
    }

    #[PermissionAttribute(PermissionConstant::DELETE_PERMISSION)]
    public function destroy(string $id): JsonResponse
    {
        $response = $this->permissionService->delete($id);

        return $response->toJsonResponse();
    }
}
