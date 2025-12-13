<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Category\Repositories;

use App\Models\Category;

class CategoryStoreRepository
{
    public function __construct(private Category $model) {}

    public function create(array $data): Category
    {
        return $this->model->create($data);
    }

    public function update(Category $category, array $data): Category
    {
        $category->update($data);

        return $category->fresh();
    }

    public function delete(Category $category): bool
    {
        return $category->delete();
    }
}
