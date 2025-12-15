<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\StockMovement\Repositories;

use App\Domain\Backoffice\StockMovement\Requests\StockMovementIndexRequest;
use App\Models\StockMovement;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class StockMovementQueryRepository
{
    public function __construct(private StockMovement $model) {}

    public function index(StockMovementIndexRequest $request): LengthAwarePaginator
    {
        $query = $this->model->query()->with(['product', 'user']);

        $query = $this->applyFilters($query, $request);
        $query = $this->applySorting($query, $request);

        return $query->paginate($request->getPerPage());
    }

    private function applyFilters(Builder $query, StockMovementIndexRequest $request): Builder
    {
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->input('product_id'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->input('end_date'));
        }

        return $query;
    }

    private function applySorting(Builder $query, StockMovementIndexRequest $request): Builder
    {
        $sortBy = $request->getSortBy();
        $order = $request->getOrder();

        $allowedSortColumns = ['id', 'created_at', 'type', 'quantity'];

        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'created_at';
        }

        $query->orderBy($sortBy, $order);
        return $query;
    }

    public function findOneById(string $id): ?StockMovement
    {
        return $this->model->find($id);
    }

    public function findOneByIdWithRelations(string $id): ?StockMovement
    {
        return $this->model->with(['product', 'user'])->find($id);
    }
}
