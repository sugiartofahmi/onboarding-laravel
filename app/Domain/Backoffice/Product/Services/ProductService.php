<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Product\Services;

use App\Domain\Backoffice\Product\Messages\ProductErrorMessage;
use App\Domain\Backoffice\Product\Requests\ProductCreateRequest;
use App\Domain\Backoffice\Product\Requests\ProductIndexRequest;
use App\Domain\Backoffice\Product\Requests\ProductUpdateRequest;
use App\Domain\Backoffice\Product\Responses\ProductCreateResponse;
use App\Domain\Backoffice\Product\Responses\ProductDeleteResponse;
use App\Domain\Backoffice\Product\Responses\ProductIndexResponse;
use App\Domain\Backoffice\Product\Responses\ProductShowResponse;
use App\Domain\Backoffice\Product\Responses\ProductUpdateResponse;
use App\Domain\Backoffice\Product\Repositories\ProductQueryRepository;
use App\Domain\Backoffice\Product\Repositories\ProductStoreRepository;
use App\Domain\Backoffice\AuditLog\Enums\AuditLogActionType;
use App\Domain\Backoffice\AuditLog\Services\AuditLogService;
use App\Infrastructure\Exceptions\NotFoundException;
use App\Infrastructure\Storage\Services\StorageService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductService
{
    public function __construct(
        private ProductQueryRepository $productQueryRepository,
        private ProductStoreRepository $productStoreRepository,
        private AuditLogService $auditLogService,
        private StorageService $storageService
    ) {}

    public function index(ProductIndexRequest $request): ProductIndexResponse
    {
        $products = $this->productQueryRepository->index($request);

        $this->auditLogService->logViewEvent(
            action: AuditLogActionType::VIEWED,
            description: 'Viewed Product List'
        );

        return new ProductIndexResponse($products);
    }

    public function show(string $id): ProductShowResponse
    {
        $product = $this->productQueryRepository->findOneByIdWithCategoryAndImages($id);

        if (!$product) {
            throw new NotFoundException(ProductErrorMessage::NOT_FOUND);
        }

        $this->auditLogService->logViewEvent(
            action: AuditLogActionType::VIEWED,
            description: "Viewed Product: {$product->name}"
        );

        return new ProductShowResponse($product);
    }

    public function create(ProductCreateRequest $request): ProductCreateResponse
    {
        try {
            $product = DB::transaction(function () use ($request) {
                $product = $this->productStoreRepository->create([
                    'category_id' => $request->input('category_id'),
                    'name' => $request->input('name'),
                    'slug' => $request->input('slug'),
                    'description' => $request->input('description'),
                    'price' => $request->input('price'),
                    'thumbnail_path' => $request->input('thumbnail_path'),
                    'stock' => $request->input('stock'),
                    // status will be auto-set by observer based on stock
                ]);

                // Handle images array from file paths
                $imagesPaths = $request->input('images_paths');
                foreach ($imagesPaths as $imagePath) {
                    $product->images()->create([
                        'image_path' => $imagePath,
                    ]);
                }

                return $product;
            });

            return new ProductCreateResponse($product);
        } catch (\Exception $e) {
            Log::error('Failed to create Product', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            throw $e;
        }
    }

    public function update(string $id, ProductUpdateRequest $request): ProductUpdateResponse
    {
        try {
            $product = $this->productQueryRepository->findOneById($id);

            if (!$product) {
                throw new NotFoundException(ProductErrorMessage::NOT_FOUND);
            }

            $product = DB::transaction(function () use ($product, $request) {
                $data = array_filter([
                    'category_id' => $request->input('category_id'),
                    'name' => $request->input('name'),
                    'slug' => $request->input('slug'),
                    'description' => $request->input('description'),
                    'price' => $request->input('price'),
                    'stock' => $request->input('stock'),
                    // status will be auto-updated by observer if stock changes
                ], fn ($value) => $value !== null);

                // Handle thumbnail replacement with file path
                if ($request->has('thumbnail_path')) {
                    $data['thumbnail_path'] = $request->input('thumbnail_path');
                }

                $product = $this->productStoreRepository->update($product, $data);

                // Handle additional images from file paths
                if ($request->has('images_paths')) {
                    $imagesPaths = $request->input('images_paths');
                    foreach ($imagesPaths as $imagePath) {
                        $product->images()->create([
                            'image_path' => $imagePath,
                        ]);
                    }
                }

                return $product;
            });

            return new ProductUpdateResponse($product);
        } catch (\Exception $e) {
            Log::error('Failed to update Product', [
                'entity_id' => $id,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            throw $e;
        }
    }

    public function delete(string $id): ProductDeleteResponse
    {
        try {
            $product = $this->productQueryRepository->findOneById($id);

            if (!$product) {
                throw new NotFoundException(ProductErrorMessage::NOT_FOUND);
            }

            $this->productStoreRepository->delete($product);

            return new ProductDeleteResponse($id);
        } catch (\Exception $e) {
            Log::error('Failed to delete Product', [
                'entity_id' => $id,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            throw $e;
        }
    }
}
