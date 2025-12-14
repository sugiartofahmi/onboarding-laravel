<?php

declare(strict_types=1);

namespace App\Presentation\Backoffice\V1\Category\Controllers;

use App\Domain\Backoffice\Category\Messages\CategoryMessage;
use App\Domain\Backoffice\Category\Requests\CategoryCreateRequest;
use App\Domain\Backoffice\Category\Requests\CategoryDeleteRequest;
use App\Domain\Backoffice\Category\Requests\CategoryIndexRequest;
use App\Domain\Backoffice\Category\Requests\CategoryShowRequest;
use App\Domain\Backoffice\Category\Requests\CategoryUpdateRequest;
use App\Domain\Backoffice\Category\Services\CategoryService;
use App\Domain\Backoffice\Permission\Constants\PermissionConstant;
use App\Infrastructure\Attributes\PermissionAttribute;
use App\Infrastructure\Enums\HttpStatusCode;
use App\Infrastructure\Helpers\ApiDataResponse;
use App\Infrastructure\Helpers\ApiPaginationResponse;
use App\Infrastructure\Helpers\BaseController;
use Illuminate\Http\JsonResponse;

class CategoryController extends BaseController
{
    public function __construct(
        private CategoryService $categoryService
    ) {}

    #[PermissionAttribute(PermissionConstant::READ_CATEGORY)]
    public function index(CategoryIndexRequest $request): JsonResponse
    {
        $response = $this->categoryService->index($request);

        return (new ApiPaginationResponse(
            paginator: $response->getPaginator(),
            message: CategoryMessage::INDEX_SUCCESS,
            statusCode: HttpStatusCode::OK
        ))->toResponse();
    }

    #[PermissionAttribute(PermissionConstant::READ_CATEGORY)]
    public function show(CategoryShowRequest $request, string $id): JsonResponse
    {
        $response = $this->categoryService->show($id);

        return (new ApiDataResponse(
            data: $response->toArray(),
            message: CategoryMessage::SHOW_SUCCESS,
            statusCode: HttpStatusCode::OK
        ))->toResponse();
    }

    #[PermissionAttribute(PermissionConstant::CREATE_CATEGORY)]
    public function store(CategoryCreateRequest $request): JsonResponse
    {
        $response = $this->categoryService->create($request);

        return (new ApiDataResponse(
            data: $response->toArray(),
            message: CategoryMessage::CREATE_SUCCESS,
            statusCode: HttpStatusCode::CREATED
        ))->toResponse();
    }

    #[PermissionAttribute(PermissionConstant::UPDATE_CATEGORY)]
    public function update(CategoryUpdateRequest $request, string $id): JsonResponse
    {
        $response = $this->categoryService->update($id, $request);

        return (new ApiDataResponse(
            data: $response->toArray(),
            message: CategoryMessage::UPDATE_SUCCESS,
            statusCode: HttpStatusCode::OK
        ))->toResponse();
    }

    #[PermissionAttribute(PermissionConstant::DELETE_CATEGORY)]
    public function destroy(CategoryDeleteRequest $request, string $id): JsonResponse
    {
        $response = $this->categoryService->delete($id);

        return (new ApiDataResponse(
            data: $response->toArray(),
            message: CategoryMessage::DELETE_SUCCESS,
            statusCode: HttpStatusCode::OK
        ))->toResponse();
    }
}
