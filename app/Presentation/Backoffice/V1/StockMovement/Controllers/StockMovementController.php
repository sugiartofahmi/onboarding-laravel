<?php

declare(strict_types=1);

namespace App\Presentation\Backoffice\V1\StockMovement\Controllers;

use App\Domain\Backoffice\Permission\Constants\PermissionConstant;
use App\Domain\Backoffice\StockMovement\Messages\StockMovementMessage;
use App\Domain\Backoffice\StockMovement\Requests\StockMovementCreateRequest;
use App\Domain\Backoffice\StockMovement\Requests\StockMovementIndexRequest;
use App\Domain\Backoffice\StockMovement\Requests\StockMovementShowRequest;
use App\Domain\Backoffice\StockMovement\Services\StockMovementService;
use App\Infrastructure\Attributes\PermissionAttribute;
use App\Infrastructure\Enums\HttpStatusCode;
use App\Infrastructure\Helpers\ApiDataResponse;
use App\Infrastructure\Helpers\ApiPaginationResponse;
use App\Infrastructure\Helpers\BaseController;
use Illuminate\Http\JsonResponse;

class StockMovementController extends BaseController
{
    public function __construct(
        private StockMovementService $stockMovementService
    ) {}

    #[PermissionAttribute(PermissionConstant::READ_STOCK_MOVEMENT)]
    public function index(StockMovementIndexRequest $request): JsonResponse
    {
        $response = $this->stockMovementService->index($request);

        return (new ApiPaginationResponse(
            paginator: $response->getPaginator(),
            message: StockMovementMessage::INDEX_SUCCESS,
            statusCode: HttpStatusCode::OK
        ))->toResponse();
    }

    #[PermissionAttribute(PermissionConstant::READ_STOCK_MOVEMENT)]
    public function show(StockMovementShowRequest $request, string $id): JsonResponse
    {
        $response = $this->stockMovementService->show($id);

        return (new ApiDataResponse(
            data: $response->toArray(),
            message: StockMovementMessage::SHOW_SUCCESS,
            statusCode: HttpStatusCode::OK
        ))->toResponse();
    }

    #[PermissionAttribute(PermissionConstant::CREATE_STOCK_MOVEMENT)]
    public function store(StockMovementCreateRequest $request): JsonResponse
    {
        $response = $this->stockMovementService->create($request);

        return (new ApiDataResponse(
            data: $response->toArray(),
            message: StockMovementMessage::CREATE_SUCCESS,
            statusCode: HttpStatusCode::CREATED
        ))->toResponse();
    }
}
