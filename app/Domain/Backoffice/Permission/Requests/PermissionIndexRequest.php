<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Permission\Requests;

use App\Infrastructure\Helpers\BaseQueryRequest;

class PermissionIndexRequest extends BaseQueryRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'search' => 'sometimes|string|max:255',
        ]);
    }
}
