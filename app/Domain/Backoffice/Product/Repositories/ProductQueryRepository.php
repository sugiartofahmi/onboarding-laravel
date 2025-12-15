<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Product\Repositories;

use App\Domain\Backoffice\Product\Requests\ProductIndexRequest;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ProductQueryRepository
{
    public function __construct(private Product $model) {}

    public function index(ProductIndexRequest $request): LengthAwarePaginator
    {
        $query = $this->model->query()->with('category');

        $query = $this->applySearch($query, $request);
        $query = $this->applyFilters($query, $request);
        $query = $this->applySorting($query, $request);

        return $query->paginate($request->getPerPage());
    }

    private function applySearch(Builder $query, ProductIndexRequest $request): Builder
    {
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    private function applyFilters(Builder $query, ProductIndexRequest $request): Builder
    {
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        return $query;
    }

    private function applySorting(Builder $query, ProductIndexRequest $request): Builder
    {
        $sortBy = $request->getSortBy();
        $order = $request->getOrder();

        $allowedSortColumns = [
            'id',
            'name',
            'price',
            'stock',
            'status',
            'created_at',
            'updated_at',
        ];

        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'created_at';
        }

        $query->orderBy($sortBy, $order);

        return $query;
    }

    public function findOneById(string $id): ?Product
    {
        return $this->model->find($id);
    }

    public function findOneByIdWithCategoryAndImages(string $id): ?Product
    {
        return $this->model->with(['category', 'images'])->find($id);
    }

    public function findOneBySlugWithCategoryAndImages(string $slug): ?Product
    {
        return $this->model->with(['category', 'images'])->where('slug', $slug)->first();
    }

    public function findMany(): Collection
    {
        return $this->model->all();
    }
}
