<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\SalesOrder\Responses;

use App\Models\SalesOrder;

class SalesOrderUpdateResponse
{
    public function __construct(
        private SalesOrder $salesOrder
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->salesOrder->id,
            'status' => $this->salesOrder->status,
            'updated_at' => $this->salesOrder->updated_at->toISOString(),
        ];
    }
}
