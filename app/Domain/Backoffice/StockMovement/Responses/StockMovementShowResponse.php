<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\StockMovement\Responses;

use App\Models\StockMovement;

class StockMovementShowResponse
{
    public function __construct(
        private StockMovement $stockMovement
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->stockMovement->id,
            'product' => [
                'id' => $this->stockMovement->product->id,
                'name' => $this->stockMovement->product->name,
                'stock' => $this->stockMovement->product->stock,
            ],
            'user' => $this->stockMovement->user ? [
                'id' => $this->stockMovement->user->id,
                'name' => $this->stockMovement->user->name,
                'email' => $this->stockMovement->user->email,
            ] : null,
            'type' => $this->stockMovement->type,
            'quantity' => $this->stockMovement->quantity,
            'note' => $this->stockMovement->note,
            'created_at' => $this->stockMovement->created_at->toISOString(),
            'updated_at' => $this->stockMovement->updated_at->toISOString(),
        ];
    }
}
