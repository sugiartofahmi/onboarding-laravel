<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\User\Requests;

use App\Infrastructure\Helpers\BaseQueryRequest;

class UserIndexRequest extends BaseQueryRequest
{
    public function queryRules(): array
    {
        return [
            'role_id' => ['sometimes', 'uuid', 'exists:roles,id'],
        ];
    }
}
