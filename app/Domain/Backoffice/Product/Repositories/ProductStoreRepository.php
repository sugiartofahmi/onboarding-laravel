<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Product\Repositories;

use App\Models\Product;

class ProductStoreRepository
{
    public function __construct(private Product $model) {}

    public function create(array $data): Product
    {
        return $this->model->create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);
        return $product->fresh();
    }

    public function delete(Product $product): bool
    {
        return $product->delete();
    }
}
