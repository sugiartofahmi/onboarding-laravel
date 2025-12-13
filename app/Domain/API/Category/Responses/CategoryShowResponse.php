<?php

declare(strict_types=1);

namespace App\Domain\API\Category\Responses;

use App\Models\Category;

class CategoryShowResponse
{
    public function __construct(
        private Category $category
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->category->id,
            'name' => $this->category->name,
            'slug' => $this->category->slug,
            'created_at' => $this->category->created_at,
            'updated_at' => $this->category->updated_at,
        ];
    }
}
