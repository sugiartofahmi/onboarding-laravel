<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Category\Repositories;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CategoryQueryRepository
{
    public function __construct(private Category $model) {}

    public function index(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->query();

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                  ->orWhere('slug', 'like', "%{$filters['search']}%");
            });
        }

        return $query->paginate($perPage);
    }

    public function findOneById(string $id): ?Category
    {
        return $this->model->find($id);
    }

    public function findOneBySlug(string $slug): ?Category
    {
        return $this->model->where('slug', $slug)->first();
    }

    public function findMany(): Collection
    {
        return $this->model->all();
    }
}
