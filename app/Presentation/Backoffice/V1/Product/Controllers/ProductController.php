<?php

declare(strict_types=1);

namespace App\Presentation\Backoffice\V1\Product\Controllers;

use App\Domain\Backoffice\Product\Messages\ProductMessage;
use App\Domain\Backoffice\Product\Requests\ProductCreateRequest;
use App\Domain\Backoffice\Product\Requests\ProductDeleteRequest;
use App\Domain\Backoffice\Product\Requests\ProductIndexRequest;
use App\Domain\Backoffice\Product\Requests\ProductShowRequest;
use App\Domain\Backoffice\Product\Requests\ProductUpdateRequest;
use App\Domain\Backoffice\Product\Services\ProductService;
use App\Domain\Backoffice\Permission\Constants\PermissionConstant;
use App\Infrastructure\Attributes\PermissionAttribute;
use App\Infrastructure\Enums\HttpStatusCode;
use App\Infrastructure\Helpers\ApiDataResponse;
use App\Infrastructure\Helpers\ApiPaginationResponse;
use App\Infrastructure\Helpers\BaseController;
use Illuminate\Http\JsonResponse;

class ProductController extends BaseController
{
    public function __construct(
        private ProductService $productService
    ) {}

    #[PermissionAttribute(PermissionConstant::READ_PRODUCT)]
    public function index(ProductIndexRequest $request): JsonResponse
    {
        $response = $this->productService->index($request);

        return (new ApiPaginationResponse(
            paginator: $response->getPaginator(),
            message: ProductMessage::INDEX_SUCCESS,
            statusCode: HttpStatusCode::OK
        ))->toResponse();
    }

    #[PermissionAttribute(PermissionConstant::READ_PRODUCT)]
    public function show(ProductShowRequest $request, string $id): JsonResponse
    {
        $response = $this->productService->show($id);

        return (new ApiDataResponse(
            data: $response->toArray(),
            message: ProductMessage::SHOW_SUCCESS,
            statusCode: HttpStatusCode::OK
        ))->toResponse();
    }

    #[PermissionAttribute(PermissionConstant::CREATE_PRODUCT)]
    public function store(ProductCreateRequest $request): JsonResponse
    {
        $response = $this->productService->create($request);

        return (new ApiDataResponse(
            data: $response->toArray(),
            message: ProductMessage::CREATE_SUCCESS,
            statusCode: HttpStatusCode::CREATED
        ))->toResponse();
    }

    #[PermissionAttribute(PermissionConstant::UPDATE_PRODUCT)]
    public function update(ProductUpdateRequest $request, string $id): JsonResponse
    {
        $response = $this->productService->update($id, $request);

        return (new ApiDataResponse(
            data: $response->toArray(),
            message: ProductMessage::UPDATE_SUCCESS,
            statusCode: HttpStatusCode::OK
        ))->toResponse();
    }

    #[PermissionAttribute(PermissionConstant::DELETE_PRODUCT)]
    public function destroy(ProductDeleteRequest $request, string $id): JsonResponse
    {
        $response = $this->productService->delete($id);

        return (new ApiDataResponse(
            data: $response->toArray(),
            message: ProductMessage::DELETE_SUCCESS,
            statusCode: HttpStatusCode::OK
        ))->toResponse();
    }
}
