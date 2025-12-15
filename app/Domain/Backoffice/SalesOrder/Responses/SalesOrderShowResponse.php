<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\SalesOrder\Responses;

use App\Models\SalesOrder;

class SalesOrderShowResponse
{
    public function __construct(
        private SalesOrder $salesOrder
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->salesOrder->id,
            'user' => $this->salesOrder->user ? [
                'id' => $this->salesOrder->user->id,
                'name' => $this->salesOrder->user->name,
                'email' => $this->salesOrder->user->email,
            ] : null,
            'total_amount' => $this->salesOrder->total_amount,
            'status' => $this->salesOrder->status,
            'items' => $this->salesOrder->items->map(fn ($item) => [
                'id' => $item->id,
                'product' => [
                    'id' => $item->product->id,
                    'name' => $item->product->name,
                ],
                'quantity' => $item->quantity,
                'price' => $item->price,
                'subtotal' => $item->subtotal,
            ])->toArray(),
            'created_at' => $this->salesOrder->created_at->toISOString(),
            'updated_at' => $this->salesOrder->updated_at->toISOString(),
        ];
    }
}
