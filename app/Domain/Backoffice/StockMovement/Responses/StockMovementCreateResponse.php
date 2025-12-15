<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\StockMovement\Responses;

use App\Models\StockMovement;

class StockMovementCreateResponse
{
    public function __construct(
        private StockMovement $stockMovement
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->stockMovement->id,
            'product_id' => $this->stockMovement->product_id,
            'user_id' => $this->stockMovement->user_id,
            'type' => $this->stockMovement->type,
            'quantity' => $this->stockMovement->quantity,
            'note' => $this->stockMovement->note,
            'created_at' => $this->stockMovement->created_at->toISOString(),
        ];
    }
}
