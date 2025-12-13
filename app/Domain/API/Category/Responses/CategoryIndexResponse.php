<?php

declare(strict_types=1);

namespace App\Domain\API\Category\Responses;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryIndexResponse
{
    public function __construct(
        private LengthAwarePaginator $categories
    ) {}

    public function toArray(): array
    {
        return $this->categories->map(fn ($category) => [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'created_at' => $category->created_at,
            'updated_at' => $category->updated_at,
        ])->toArray();
    }

    public function getPaginator(): LengthAwarePaginator
    {
        return $this->categories;
    }
}
