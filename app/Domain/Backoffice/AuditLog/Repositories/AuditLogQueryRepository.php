<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\AuditLog\Repositories;

use App\Domain\Backoffice\AuditLog\Requests\AuditLogIndexRequest;
use App\Models\AuditLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class AuditLogQueryRepository
{
    public function __construct(private AuditLog $model) {}

    public function index(AuditLogIndexRequest $request): LengthAwarePaginator
    {
        $query = $this->model->query()->with(['user:id,name,email']);

        $query = $this->applyFilters($query, $request);
        $query = $this->applySorting($query, $request);

        return $query->paginate($request->getPerPage());
    }

    private function applyFilters(Builder $query, AuditLogIndexRequest $request): Builder
    {
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        if ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->input('end_date'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('description', 'like', "%{$search}%");
        }

        return $query;
    }

    private function applySorting(Builder $query, AuditLogIndexRequest $request): Builder
    {
        $sortBy = $request->getSortBy();
        $order = $request->getOrder();

        $allowedSortColumns = ['created_at', 'action'];

        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'created_at';
        }

        $query->orderBy($sortBy, $order);

        return $query;
    }
}
