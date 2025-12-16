<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\SalesOrder\Observers;

use App\Domain\Backoffice\AuditLog\Enums\AuditLogActionType;
use App\Domain\Backoffice\AuditLog\Services\AuditLogService;
use App\Domain\Backoffice\Dashboard\Repositories\SalesSummaryStoreRepository;
use App\Domain\Backoffice\Dashboard\Repositories\StatisticsSummaryStoreRepository;
use App\Domain\Backoffice\SalesOrder\Enums\OrderStatusType;
use App\Models\SalesOrder;
use Carbon\Carbon;

class SalesOrderObserver
{
    public function __construct(
        private AuditLogService $auditLogService,
        private StatisticsSummaryStoreRepository $statisticsSummaryStoreRepository,
        private SalesSummaryStoreRepository $salesSummaryStoreRepository
    ) {}

    public function created(SalesOrder $salesOrder): void
    {
        $this->auditLogService->logModelEvent(
            model: $salesOrder,
            action: AuditLogActionType::CREATED,
            description: "Created SalesOrder: {$salesOrder->id}"
        );
    }

    public function updated(SalesOrder $salesOrder): void
    {
        if ($salesOrder->isDirty('status')) {
            $oldStatus = $salesOrder->getOriginal('status');
            $newStatus = $salesOrder->status;

            if ($newStatus === OrderStatusType::PAID->value && $oldStatus !== OrderStatusType::PAID->value) {
                $this->handleOrderPaid($salesOrder);
            }

            if ($oldStatus === OrderStatusType::PAID->value && $newStatus === OrderStatusType::VOID->value) {
                $this->handleOrderVoided($salesOrder);
            }
        }

        $this->auditLogService->logModelEvent(
            model: $salesOrder,
            action: AuditLogActionType::UPDATED,
            description: "Updated SalesOrder: {$salesOrder->id}"
        );
    }

    public function deleted(SalesOrder $salesOrder): void
    {
        if ($salesOrder->status === OrderStatusType::PAID->value) {
            $this->handleOrderVoided($salesOrder);
        }

        $this->auditLogService->logModelEvent(
            model: $salesOrder,
            action: AuditLogActionType::DELETED,
            description: "Deleted SalesOrder: {$salesOrder->id}"
        );
    }

    private function handleOrderPaid(SalesOrder $salesOrder): void
    {
        $revenue = (float) $salesOrder->total_amount;
        $itemsSold = $salesOrder->items->sum('quantity');
        $orderDate = Carbon::parse($salesOrder->created_at);

        $this->statisticsSummaryStoreRepository->incrementTotalOrdersAndRevenue($revenue);

        $this->salesSummaryStoreRepository->incrementByDate(
            $orderDate,
            $revenue,
            $itemsSold
        );
    }

    private function handleOrderVoided(SalesOrder $salesOrder): void
    {
        $revenue = (float) $salesOrder->total_amount;
        $itemsSold = $salesOrder->items->sum('quantity');
        $orderDate = Carbon::parse($salesOrder->created_at);

        $this->statisticsSummaryStoreRepository->decrementTotalOrdersAndRevenue($revenue);

        $this->salesSummaryStoreRepository->decrementByDate(
            $orderDate,
            $revenue,
            $itemsSold
        );
    }
}
