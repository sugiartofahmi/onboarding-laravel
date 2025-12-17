<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Category\Repositories;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use App\Domain\Backoffice\Category\Requests\CategoryIndexRequest;
use Illuminate\Database\Eloquent\Builder;

class CategoryQueryRepository
{
    public function __construct(private Category $model) {}

    public function index(CategoryIndexRequest $request): LengthAwarePaginator
    {
        $query = $this->model->query();

        $query = $this->applySearch($query, $request);
        $query = $this->applySorting($query, $request);

        return $query->paginate($request->getPerPage());
    }

    private function applySearch(Builder $query, CategoryIndexRequest $request): Builder
    {
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        return $query;
    }

    private function applySorting(Builder $query, CategoryIndexRequest $request): Builder
    {
        $sortBy = $request->getSortBy();
        $order = $request->getOrder();

        $allowedSortColumns = ['id', 'name', 'slug', 'created_at', 'updated_at'];

        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'created_at';
        }

        $query->orderBy($sortBy, $order);

        return $query;
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
