<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\SalesOrder\Repositories;

use App\Domain\Backoffice\SalesOrder\Requests\SalesOrderIndexRequest;
use App\Models\SalesOrder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class SalesOrderQueryRepository
{
    public function __construct(private SalesOrder $model) {}

    public function index(SalesOrderIndexRequest $request): LengthAwarePaginator
    {
        $query = $this->model->query()->with(['user', 'items.product']);

        $query = $this->applyFilters($query, $request);
        $query = $this->applySorting($query, $request);

        return $query->paginate($request->getPerPage());
    }

    private function applyFilters(Builder $query, SalesOrderIndexRequest $request): Builder
    {
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->input('end_date'));
        }

        return $query;
    }

    private function applySorting(Builder $query, SalesOrderIndexRequest $request): Builder
    {
        $sortBy = $request->getSortBy();
        $order = $request->getOrder();

        $allowedSortColumns = ['id', 'created_at', 'total_amount', 'status'];

        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'created_at';
        }

        $query->orderBy($sortBy, $order);
        return $query;
    }

    public function findOneById(string $id): ?SalesOrder
    {
        return $this->model->find($id);
    }

    public function findOneByIdWithRelations(string $id): ?SalesOrder
    {
        return $this->model->with(['user', 'items.product'])->find($id);
    }
}
