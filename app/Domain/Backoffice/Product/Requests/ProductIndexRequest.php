<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Product\Requests;

use App\Infrastructure\Helpers\BaseQueryRequest;

class ProductIndexRequest extends BaseQueryRequest
{
    protected function queryRules(): array
    {
        return [
            'category_id' => ['sometimes', 'uuid', 'exists:categories,id'],
            'status' => ['sometimes', 'string', 'in:available,low_stock,out_of_stock'],
        ];
    }
}
