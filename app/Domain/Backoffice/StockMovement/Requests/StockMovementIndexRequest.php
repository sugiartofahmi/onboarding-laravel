<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\StockMovement\Requests;

use App\Infrastructure\Helpers\BaseQueryRequest;

class StockMovementIndexRequest extends BaseQueryRequest
{
    protected function queryRules(): array
    {
        return [
            'product_id' => ['sometimes', 'uuid', 'exists:products,id'],
            'type' => ['sometimes', 'string', 'in:in,out'],
            'start_date' => ['sometimes', 'date'],
            'end_date' => ['sometimes', 'date', 'after_or_equal:start_date'],
        ];
    }
}
