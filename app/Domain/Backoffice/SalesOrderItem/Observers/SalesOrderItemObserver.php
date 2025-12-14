<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\SalesOrderItem\Observers;

use App\Domain\Backoffice\AuditLog\Enums\AuditLogActionType;
use App\Domain\Backoffice\AuditLog\Services\AuditLogService;
use App\Models\SalesOrderItem;

class SalesOrderItemObserver
{
    public function __construct(
        private AuditLogService $auditLogService
    ) {}

    public function created(SalesOrderItem $salesOrderItem): void
    {
        $this->auditLogService->logModelEvent(
            model: $salesOrderItem,
            action: AuditLogActionType::CREATED,
            description: "Created SalesOrderItem for Order ID: {$salesOrderItem->sales_order_id}"
        );
    }

    public function updated(SalesOrderItem $salesOrderItem): void
    {
        $this->auditLogService->logModelEvent(
            model: $salesOrderItem,
            action: AuditLogActionType::UPDATED,
            description: "Updated SalesOrderItem for Order ID: {$salesOrderItem->sales_order_id}"
        );
    }

    public function deleted(SalesOrderItem $salesOrderItem): void
    {
        $this->auditLogService->logModelEvent(
            model: $salesOrderItem,
            action: AuditLogActionType::DELETED,
            description: "Deleted SalesOrderItem for Order ID: {$salesOrderItem->sales_order_id}"
        );
    }
}
