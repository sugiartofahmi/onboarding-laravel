<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\SalesOrder\Responses;

use App\Models\SalesOrder;

class SalesOrderCreateResponse
{
    public function __construct(
        private SalesOrder $salesOrder
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->salesOrder->id,
            'user_id' => $this->salesOrder->user_id,
            'total_amount' => $this->salesOrder->total_amount,
            'status' => $this->salesOrder->status,
            'items' => $this->salesOrder->items->map(fn ($item) => [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'subtotal' => $item->subtotal,
            ])->toArray(),
            'created_at' => $this->salesOrder->created_at->toISOString(),
        ];
    }
}
