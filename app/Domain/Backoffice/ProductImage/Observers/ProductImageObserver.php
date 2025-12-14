<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\ProductImage\Observers;

use App\Domain\Backoffice\AuditLog\Enums\AuditLogActionType;
use App\Domain\Backoffice\AuditLog\Services\AuditLogService;
use App\Infrastructure\Storage\Services\StorageService;
use App\Models\ProductImage;

class ProductImageObserver
{
    public function __construct(
        private AuditLogService $auditLogService,
        private StorageService $storageService
    ) {}

    public function created(ProductImage $productImage): void
    {
        $this->auditLogService->logModelEvent(
            model: $productImage,
            action: AuditLogActionType::CREATED,
            description: "Created ProductImage for Product ID: {$productImage->product_id}"
        );
    }

    public function updated(ProductImage $productImage): void
    {
        $this->auditLogService->logModelEvent(
            model: $productImage,
            action: AuditLogActionType::UPDATED,
            description: "Updated ProductImage for Product ID: {$productImage->product_id}"
        );
    }

    public function deleted(ProductImage $productImage): void
    {
        // Delete image file from storage
        if ($productImage->image_path) {
            $this->storageService->delete($productImage->image_path);
        }

        $this->auditLogService->logModelEvent(
            model: $productImage,
            action: AuditLogActionType::DELETED,
            description: "Deleted ProductImage for Product ID: {$productImage->product_id}"
        );
    }
}
