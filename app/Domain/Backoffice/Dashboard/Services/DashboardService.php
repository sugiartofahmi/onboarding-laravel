<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Dashboard\Services;

use App\Domain\Backoffice\AuditLog\Enums\AuditLogActionType;
use App\Domain\Backoffice\AuditLog\Services\AuditLogService;
use App\Domain\Backoffice\Dashboard\Repositories\SalesSummaryQueryRepository;
use App\Domain\Backoffice\Dashboard\Repositories\StatisticsSummaryQueryRepository;
use App\Domain\Backoffice\Dashboard\Requests\SalesSummaryRequest;
use App\Domain\Backoffice\Dashboard\Responses\LowStockResponse;
use App\Domain\Backoffice\Dashboard\Responses\SalesSummaryResponse;
use App\Domain\Backoffice\Dashboard\Responses\StatisticsSummaryResponse;
use App\Domain\Backoffice\Product\Enums\ProductStatusType;
use App\Models\Product;

class DashboardService
{
    public function __construct(
        private StatisticsSummaryQueryRepository $statisticsSummaryQueryRepository,
        private SalesSummaryQueryRepository $salesSummaryQueryRepository,
        private AuditLogService $auditLogService
    ) {}

    public function getStats(): StatisticsSummaryResponse
    {
        $stats = $this->statisticsSummaryQueryRepository->findOne();

        $this->auditLogService->logViewEvent(
            action: AuditLogActionType::VIEWED,
            description: 'Viewed Dashboard Stats'
        );

        return new StatisticsSummaryResponse($stats);
    }

    public function getSalesSummary(SalesSummaryRequest $request): SalesSummaryResponse
    {
        $salesSummary = $this->salesSummaryQueryRepository->findByDateRange($request);

        $this->auditLogService->logViewEvent(
            action: AuditLogActionType::VIEWED,
            description: 'Viewed Dashboard Sales Summary'
        );

        return new SalesSummaryResponse($salesSummary);
    }

    public function getLowStockProducts(): LowStockResponse
    {
        $products = Product::whereIn('status', [
            ProductStatusType::LOW_STOCK->value,
            ProductStatusType::OUT_OF_STOCK->value,
        ])
            ->with('category')
            ->orderBy('stock', 'asc')
            ->get();

        $this->auditLogService->logViewEvent(
            action: AuditLogActionType::VIEWED,
            description: 'Viewed Dashboard Low Stock Alerts'
        );

        return new LowStockResponse($products);
    }
}
