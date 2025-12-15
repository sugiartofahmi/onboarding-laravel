<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\StockMovement\Responses;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class StockMovementIndexResponse
{
    public function __construct(
        private LengthAwarePaginator $stockMovements
    ) {}

    public function toArray(): array
    {
        return $this->stockMovements->map(fn ($movement) => [
            'id' => $movement->id,
            'product' => [
                'id' => $movement->product->id,
                'name' => $movement->product->name,
            ],
            'user' => $movement->user ? [
                'id' => $movement->user->id,
                'name' => $movement->user->name,
            ] : null,
            'type' => $movement->type,
            'quantity' => $movement->quantity,
            'note' => $movement->note,
            'created_at' => $movement->created_at->toISOString(),
        ])->toArray();
    }

    public function getPaginator(): LengthAwarePaginator
    {
        return $this->stockMovements;
    }
}
