<?php

declare(strict_types=1);

namespace App\Presentation\Backoffice\V1\Dashboard\Controllers;

use App\Domain\Backoffice\Dashboard\Messages\DashboardMessage;
use App\Domain\Backoffice\Dashboard\Requests\SalesSummaryRequest;
use App\Domain\Backoffice\Dashboard\Services\DashboardService;
use App\Domain\Backoffice\Permission\Constants\PermissionConstant;
use App\Infrastructure\Attributes\PermissionAttribute;
use App\Infrastructure\Enums\HttpStatusCode;
use App\Infrastructure\Helpers\ApiDataResponse;
use App\Infrastructure\Helpers\BaseController;
use Illuminate\Http\JsonResponse;

class DashboardController extends BaseController
{
    public function __construct(
        private DashboardService $dashboardService
    ) {}

    #[PermissionAttribute(PermissionConstant::READ_DASHBOARD)]
    public function stats(): JsonResponse
    {
        $response = $this->dashboardService->getStats();

        return (new ApiDataResponse(
            data: $response->toArray(),
            message: DashboardMessage::STATS_SUCCESS,
            statusCode: HttpStatusCode::OK
        ))->toResponse();
    }

    #[PermissionAttribute(PermissionConstant::READ_DASHBOARD)]
    public function salesSummary(SalesSummaryRequest $request): JsonResponse
    {
        $response = $this->dashboardService->getSalesSummary($request);

        return (new ApiDataResponse(
            data: $response->toArray(),
            message: DashboardMessage::SALES_SUMMARY_SUCCESS,
            statusCode: HttpStatusCode::OK
        ))->toResponse();
    }

    #[PermissionAttribute(PermissionConstant::READ_DASHBOARD)]
    public function lowStock(): JsonResponse
    {
        $response = $this->dashboardService->getLowStockProducts();

        return (new ApiDataResponse(
            data: $response->toArray(),
            message: DashboardMessage::LOW_STOCK_SUCCESS,
            statusCode: HttpStatusCode::OK
        ))->toResponse();
    }
}
