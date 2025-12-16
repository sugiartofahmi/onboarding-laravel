<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Product\Observers;

use App\Domain\Backoffice\AuditLog\Enums\AuditLogActionType;
use App\Domain\Backoffice\AuditLog\Services\AuditLogService;
use App\Domain\Backoffice\Dashboard\Repositories\StatisticsSummaryStoreRepository;
use App\Domain\Backoffice\Product\Enums\ProductStatusType;
use App\Infrastructure\Storage\Services\StorageService;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductObserver
{
    public function __construct(
        private AuditLogService $auditLogService,
        private StorageService $storageService,
        private StatisticsSummaryStoreRepository $statisticsSummaryStoreRepository
    ) {}

    public function creating(Product $product): void
    {
        $this->generateSlugIfNeeded($product);
        $this->updateStatusBasedOnStock($product);
    }

    public function updating(Product $product): void
    {
        $this->generateSlugIfNeeded($product);

        if ($product->isDirty('stock')) {
            $this->updateStatusBasedOnStock($product);
        }
    }

    public function created(Product $product): void
    {
        $this->statisticsSummaryStoreRepository->incrementTotalProducts();

        if ($this->isLowStock($product->status)) {
            $this->statisticsSummaryStoreRepository->incrementLowStockCount();
        }

        $this->auditLogService->logModelEvent(
            model: $product,
            action: AuditLogActionType::CREATED,
            description: "Created Product: {$product->name}"
        );
    }

    public function updated(Product $product): void
    {
        if ($product->isDirty('status')) {
            $oldStatus = $product->getOriginal('status');
            $newStatus = $product->status;

            $wasLowStock = $this->isLowStock($oldStatus);
            $isNowLowStock = $this->isLowStock($newStatus);

            if (!$wasLowStock && $isNowLowStock) {
                $this->statisticsSummaryStoreRepository->incrementLowStockCount();
            } elseif ($wasLowStock && !$isNowLowStock) {
                $this->statisticsSummaryStoreRepository->decrementLowStockCount();
            }
        }

        $this->auditLogService->logModelEvent(
            model: $product,
            action: AuditLogActionType::UPDATED,
            description: "Updated Product: {$product->name}"
        );
    }

    public function deleted(Product $product): void
    {
        $this->statisticsSummaryStoreRepository->decrementTotalProducts();

        if ($this->isLowStock($product->status)) {
            $this->statisticsSummaryStoreRepository->decrementLowStockCount();
        }

        // Delete thumbnail file
        if ($product->thumbnail_path) {
            $this->storageService->delete($product->thumbnail_path);
        }

        // Delete all product images
        foreach ($product->images as $image) {
            if ($image->image_path) {
                $this->storageService->delete($image->image_path);
            }
        }

        $this->auditLogService->logModelEvent(
            model: $product,
            action: AuditLogActionType::DELETED,
            description: "Deleted Product: {$product->name}"
        );
    }

    private function generateSlugIfNeeded(Product $product): void
    {
        if (empty($product->slug)) {
            $baseSlug = Str::slug($product->name);
            $slug = $baseSlug;
            $counter = 1;

            while (Product::where('slug', $slug)
                ->where('id', '!=', $product->id ?? '')
                ->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $product->slug = $slug;
        }
    }

    private function updateStatusBasedOnStock(Product $product): void
    {
        if ($product->stock <= 0) {
            $product->status = ProductStatusType::OUT_OF_STOCK->value;
        } elseif ($product->stock <= 10) {
            $product->status = ProductStatusType::LOW_STOCK->value;
        } else {
            $product->status = ProductStatusType::AVAILABLE->value;
        }
    }

    private function isLowStock(?string $status): bool
    {
        return in_array($status, [
            ProductStatusType::LOW_STOCK->value,
            ProductStatusType::OUT_OF_STOCK->value,
        ], true);
    }
}
