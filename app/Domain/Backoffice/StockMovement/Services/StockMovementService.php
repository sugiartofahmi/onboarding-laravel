<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\StockMovement\Services;

use App\Domain\Backoffice\StockMovement\Messages\StockMovementErrorMessage;
use App\Domain\Backoffice\StockMovement\Repositories\StockMovementQueryRepository;
use App\Domain\Backoffice\StockMovement\Repositories\StockMovementStoreRepository;
use App\Domain\Backoffice\StockMovement\Requests\StockMovementCreateRequest;
use App\Domain\Backoffice\StockMovement\Requests\StockMovementIndexRequest;
use App\Domain\Backoffice\StockMovement\Responses\StockMovementCreateResponse;
use App\Domain\Backoffice\StockMovement\Responses\StockMovementIndexResponse;
use App\Domain\Backoffice\StockMovement\Responses\StockMovementShowResponse;
use App\Domain\Backoffice\AuditLog\Enums\AuditLogActionType;
use App\Domain\Backoffice\AuditLog\Services\AuditLogService;
use App\Infrastructure\Exceptions\NotFoundException;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class StockMovementService
{
    public function __construct(
        private StockMovementQueryRepository $stockMovementQueryRepository,
        private StockMovementStoreRepository $stockMovementStoreRepository,
        private AuditLogService $auditLogService
    ) {}

    public function index(StockMovementIndexRequest $request): StockMovementIndexResponse
    {
        $stockMovements = $this->stockMovementQueryRepository->index($request);

        $this->auditLogService->logViewEvent(
            action: AuditLogActionType::VIEWED,
            description: 'Viewed Stock Movement List'
        );

        return new StockMovementIndexResponse($stockMovements);
    }

    public function show(string $id): StockMovementShowResponse
    {
        $stockMovement = $this->stockMovementQueryRepository->findOneByIdWithRelations($id);

        if (!$stockMovement) {
            throw new NotFoundException(StockMovementErrorMessage::NOT_FOUND);
        }

        $this->auditLogService->logViewEvent(
            action: AuditLogActionType::VIEWED,
            description: "Viewed Stock Movement: {$stockMovement->type} - {$stockMovement->quantity} units"
        );

        return new StockMovementShowResponse($stockMovement);
    }

    public function create(StockMovementCreateRequest $request): StockMovementCreateResponse
    {
        try {
            $user = JWTAuth::user();

            $stockMovement = $this->stockMovementStoreRepository->create([
                'product_id' => $request->input('product_id'),
                'user_id' => $user->id,
                'type' => $request->input('type'),
                'quantity' => $request->input('quantity'),
                'note' => $request->input('note'),
            ]);

            // Observer will automatically update product stock

            return new StockMovementCreateResponse($stockMovement);
        } catch (\Exception $e) {
            Log::error('Failed to create Stock Movement', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            throw $e;
        }
    }
}
