<?php

declare(strict_types=1);

namespace App\Presentation\Backoffice\V1\SalesOrder\Controllers;

use App\Domain\Backoffice\Permission\Constants\PermissionConstant;
use App\Domain\Backoffice\Permission\PermissionAttribute;
use App\Domain\Backoffice\SalesOrder\Messages\SalesOrderMessage;
use App\Domain\Backoffice\SalesOrder\Requests\SalesOrderCreateRequest;
use App\Domain\Backoffice\SalesOrder\Requests\SalesOrderIndexRequest;
use App\Domain\Backoffice\SalesOrder\Requests\SalesOrderShowRequest;
use App\Domain\Backoffice\SalesOrder\Requests\SalesOrderUpdateRequest;
use App\Domain\Backoffice\SalesOrder\Services\SalesOrderService;
use App\Infrastructure\Enums\HttpStatusCode;
use App\Infrastructure\Responses\ApiDataResponse;
use App\Infrastructure\Responses\ApiPaginationResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class SalesOrderController extends Controller
{
    public function __construct(
        private SalesOrderService $salesOrderService
    ) {}

    #[PermissionAttribute(PermissionConstant::READ_SALES_ORDER)]
    public function index(SalesOrderIndexRequest $request): JsonResponse
    {
        $response = $this->salesOrderService->index($request);

        return (new ApiPaginationResponse(
            paginator: $response->getPaginator(),
            message: SalesOrderMessage::INDEX_SUCCESS
        ))->toResponse();
    }

    #[PermissionAttribute(PermissionConstant::READ_SALES_ORDER)]
    public function show(SalesOrderShowRequest $request, string $id): JsonResponse
    {
        $response = $this->salesOrderService->show($id);

        return (new ApiDataResponse(
            data: $response->toArray(),
            message: SalesOrderMessage::SHOW_SUCCESS,
            statusCode: HttpStatusCode::OK
        ))->toResponse();
    }

    #[PermissionAttribute(PermissionConstant::CREATE_SALES_ORDER)]
    public function store(SalesOrderCreateRequest $request): JsonResponse
    {
        $response = $this->salesOrderService->create($request);

        return (new ApiDataResponse(
            data: $response->toArray(),
            message: SalesOrderMessage::CREATE_SUCCESS,
            statusCode: HttpStatusCode::CREATED
        ))->toResponse();
    }

    #[PermissionAttribute(PermissionConstant::UPDATE_SALES_ORDER)]
    public function update(SalesOrderUpdateRequest $request, string $id): JsonResponse
    {
        $response = $this->salesOrderService->update($id, $request);

        return (new ApiDataResponse(
            data: $response->toArray(),
            message: SalesOrderMessage::UPDATE_SUCCESS,
            statusCode: HttpStatusCode::OK
        ))->toResponse();
    }
}
