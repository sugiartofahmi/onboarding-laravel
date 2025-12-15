<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\SalesOrder\Services;

use App\Domain\Backoffice\Product\Messages\ProductErrorMessage;
use App\Domain\Backoffice\Product\Repositories\ProductQueryRepository;
use App\Domain\Backoffice\SalesOrder\Enums\OrderStatusType;
use App\Domain\Backoffice\SalesOrder\Messages\SalesOrderErrorMessage;
use App\Domain\Backoffice\SalesOrder\Repositories\SalesOrderQueryRepository;
use App\Domain\Backoffice\SalesOrder\Repositories\SalesOrderStoreRepository;
use App\Domain\Backoffice\SalesOrder\Requests\SalesOrderCreateRequest;
use App\Domain\Backoffice\SalesOrder\Requests\SalesOrderIndexRequest;
use App\Domain\Backoffice\SalesOrder\Requests\SalesOrderUpdateRequest;
use App\Domain\Backoffice\SalesOrder\Responses\SalesOrderCreateResponse;
use App\Domain\Backoffice\SalesOrder\Responses\SalesOrderIndexResponse;
use App\Domain\Backoffice\SalesOrder\Responses\SalesOrderShowResponse;
use App\Domain\Backoffice\SalesOrder\Responses\SalesOrderUpdateResponse;
use App\Domain\Backoffice\StockMovement\Enums\StockMovementType;
use App\Domain\Backoffice\StockMovement\Repositories\StockMovementStoreRepository;
use App\Domain\Backoffice\AuditLog\Enums\AuditLogActionType;
use App\Domain\Backoffice\AuditLog\Services\AuditLogService;
use App\Infrastructure\Exceptions\BadRequestException;
use App\Infrastructure\Exceptions\NotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class SalesOrderService
{
    public function __construct(
        private SalesOrderQueryRepository $salesOrderQueryRepository,
        private SalesOrderStoreRepository $salesOrderStoreRepository,
        private ProductQueryRepository $productQueryRepository,
        private StockMovementStoreRepository $stockMovementStoreRepository,
        private AuditLogService $auditLogService
    ) {}

    public function index(SalesOrderIndexRequest $request): SalesOrderIndexResponse
    {
        $salesOrders = $this->salesOrderQueryRepository->index($request);

        $this->auditLogService->logViewEvent(
            action: AuditLogActionType::VIEWED,
            description: 'Viewed Sales Order List'
        );

        return new SalesOrderIndexResponse($salesOrders);
    }

    public function show(string $id): SalesOrderShowResponse
    {
        $salesOrder = $this->salesOrderQueryRepository->findOneByIdWithRelations($id);

        if (!$salesOrder) {
            throw new NotFoundException(SalesOrderErrorMessage::NOT_FOUND);
        }

        $this->auditLogService->logViewEvent(
            action: AuditLogActionType::VIEWED,
            description: "Viewed Sales Order: {$salesOrder->id}"
        );

        return new SalesOrderShowResponse($salesOrder);
    }

    public function create(SalesOrderCreateRequest $request): SalesOrderCreateResponse
    {
        try {
            $user = JWTAuth::user();

            $salesOrder = DB::transaction(function () use ($request, $user) {
                // 1. Validate all products exist and have sufficient stock
                $items = $request->input('items');
                $totalAmount = 0;

                foreach ($items as $item) {
                    $product = $this->productQueryRepository->findOneById($item['product_id']);

                    if (!$product) {
                        throw new NotFoundException(ProductErrorMessage::NOT_FOUND);
                    }

                    if ($product->stock < $item['quantity']) {
                        throw new BadRequestException(SalesOrderErrorMessage::INSUFFICIENT_STOCK);
                    }

                    $totalAmount += $product->price * $item['quantity'];
                }

                // 2. Create sales order
                $salesOrder = $this->salesOrderStoreRepository->create([
                    'user_id' => $user->id,
                    'total_amount' => $totalAmount,
                    'status' => OrderStatusType::PENDING->value,
                ]);

                // 3. Create sales order items
                foreach ($items as $item) {
                    $product = $this->productQueryRepository->findOneById($item['product_id']);

                    $salesOrder->items()->create([
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                        'subtotal' => $product->price * $item['quantity'],
                    ]);
                }

                // 4. Create stock movements (OUT)
                foreach ($items as $item) {
                    $this->stockMovementStoreRepository->create([
                        'product_id' => $item['product_id'],
                        'user_id' => $user->id,
                        'type' => StockMovementType::OUT->value,
                        'quantity' => $item['quantity'],
                        'note' => "Sales Order: {$salesOrder->id}",
                    ]);
                }

                return $salesOrder->fresh(['items.product']);
            });

            return new SalesOrderCreateResponse($salesOrder);
        } catch (\Exception $e) {
            Log::error('Failed to create Sales Order', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            throw $e;
        }
    }

    public function update(string $id, SalesOrderUpdateRequest $request): SalesOrderUpdateResponse
    {
        try {
            $salesOrder = $this->salesOrderQueryRepository->findOneById($id);

            if (!$salesOrder) {
                throw new NotFoundException(SalesOrderErrorMessage::NOT_FOUND);
            }

            // Only allow status update
            $salesOrder = $this->salesOrderStoreRepository->update($salesOrder, [
                'status' => $request->input('status'),
            ]);

            return new SalesOrderUpdateResponse($salesOrder);
        } catch (\Exception $e) {
            Log::error('Failed to update Sales Order', [
                'entity_id' => $id,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            throw $e;
        }
    }
}
