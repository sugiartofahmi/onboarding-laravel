<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\SalesOrder\Requests;

use App\Infrastructure\Helpers\BaseQueryRequest;

class SalesOrderIndexRequest extends BaseQueryRequest
{
    protected function queryRules(): array
    {
        return [
            'user_id' => ['sometimes', 'uuid', 'exists:users,id'],
            'status' => ['sometimes', 'string', 'in:pending,paid,void'],
            'start_date' => ['sometimes', 'date'],
            'end_date' => ['sometimes', 'date', 'after_or_equal:start_date'],
        ];
    }
}
