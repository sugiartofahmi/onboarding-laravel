<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Category\Requests;

use App\Infrastructure\Helpers\BaseQueryRequest;

class CategoryIndexRequest extends BaseQueryRequest
{
    protected function queryRules(): array
    {
        return [];
    }
}
