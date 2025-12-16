<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Dashboard\Responses;

use Illuminate\Support\Collection;

class LowStockResponse
{
    public function __construct(private Collection $products) {}

    public function toArray(): array
    {
        return $this->products->map(fn ($product) => [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'stock' => $product->stock,
            'status' => $product->status,
            'category' => $product->category ? [
                'id' => $product->category->id,
                'name' => $product->category->name,
            ] : null,
        ])->toArray();
    }
}
