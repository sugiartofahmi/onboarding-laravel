<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Product\Responses;

class ProductDeleteResponse
{
    public function __construct(
        private string $id
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
        ];
    }
}
