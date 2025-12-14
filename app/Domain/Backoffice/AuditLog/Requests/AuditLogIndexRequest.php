<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\AuditLog\Requests;

use App\Infrastructure\Helpers\BaseQueryRequest;

class AuditLogIndexRequest extends BaseQueryRequest
{
    protected function queryRules(): array
    {
        return [
            'user_id' => ['sometimes', 'uuid', 'exists:users,id'],
            'action' => ['sometimes', 'string', 'in:created,updated,deleted,viewed,login,logout'],
            'start_date' => ['sometimes', 'date'],
            'end_date' => ['sometimes', 'date', 'after_or_equal:start_date'],
        ];
    }
}
