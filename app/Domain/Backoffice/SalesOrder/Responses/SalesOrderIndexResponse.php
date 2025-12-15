<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\SalesOrder\Responses;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SalesOrderIndexResponse
{
    public function __construct(
        private LengthAwarePaginator $salesOrders
    ) {}

    public function toArray(): array
    {
        return $this->salesOrders->map(fn ($order) => [
            'id' => $order->id,
            'user' => $order->user ? [
                'id' => $order->user->id,
                'name' => $order->user->name,
            ] : null,
            'total_amount' => $order->total_amount,
            'status' => $order->status,
            'items_count' => $order->items->count(),
            'created_at' => $order->created_at->toISOString(),
        ])->toArray();
    }

    public function getPaginator(): LengthAwarePaginator
    {
        return $this->salesOrders;
    }
}
