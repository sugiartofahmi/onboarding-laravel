<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\StockMovement\Observers;

use App\Domain\Backoffice\AuditLog\Enums\AuditLogActionType;
use App\Domain\Backoffice\AuditLog\Services\AuditLogService;
use App\Domain\Backoffice\StockMovement\Enums\StockMovementType;
use App\Domain\Backoffice\StockMovement\Messages\StockMovementErrorMessage;
use App\Infrastructure\Exceptions\BadRequestException;
use App\Models\StockMovement;

class StockMovementObserver
{
    public function __construct(
        private AuditLogService $auditLogService
    ) {}

    public function created(StockMovement $stockMovement): void
    {
        // Update product stock
        $product = $stockMovement->product;

        if ($stockMovement->type === StockMovementType::IN->value) {
            $product->stock += $stockMovement->quantity;
        } else {
            if ($product->stock < $stockMovement->quantity) {
                throw new BadRequestException(StockMovementErrorMessage::INSUFFICIENT_STOCK);
            }
            $product->stock -= $stockMovement->quantity;
        }

        $product->save();  // Product observer will handle status update

        // Audit log
        $this->auditLogService->logModelEvent(
            model: $stockMovement,
            action: AuditLogActionType::CREATED,
            description: "Created StockMovement: {$stockMovement->type} for Product ID: {$stockMovement->product_id}"
        );
    }

    public function updated(StockMovement $stockMovement): void
    {
        $this->auditLogService->logModelEvent(
            model: $stockMovement,
            action: AuditLogActionType::UPDATED,
            description: "Updated StockMovement: {$stockMovement->type} for Product ID: {$stockMovement->product_id}"
        );
    }

    public function deleted(StockMovement $stockMovement): void
    {
        $this->auditLogService->logModelEvent(
            model: $stockMovement,
            action: AuditLogActionType::DELETED,
            description: "Deleted StockMovement: {$stockMovement->type} for Product ID: {$stockMovement->product_id}"
        );
    }
}
