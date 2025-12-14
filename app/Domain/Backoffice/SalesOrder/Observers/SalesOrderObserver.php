<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\SalesOrder\Observers;

use App\Domain\Backoffice\AuditLog\Enums\AuditLogActionType;
use App\Domain\Backoffice\AuditLog\Services\AuditLogService;
use App\Models\SalesOrder;

class SalesOrderObserver
{
    public function __construct(
        private AuditLogService $auditLogService
    ) {}

    public function created(SalesOrder $salesOrder): void
    {
        $this->auditLogService->logModelEvent(
            model: $salesOrder,
            action: AuditLogActionType::CREATED,
            description: "Created SalesOrder: {$salesOrder->order_number}"
        );
    }

    public function updated(SalesOrder $salesOrder): void
    {
        $this->auditLogService->logModelEvent(
            model: $salesOrder,
            action: AuditLogActionType::UPDATED,
            description: "Updated SalesOrder: {$salesOrder->order_number}"
        );
    }

    public function deleted(SalesOrder $salesOrder): void
    {
        $this->auditLogService->logModelEvent(
            model: $salesOrder,
            action: AuditLogActionType::DELETED,
            description: "Deleted SalesOrder: {$salesOrder->order_number}"
        );
    }
}
