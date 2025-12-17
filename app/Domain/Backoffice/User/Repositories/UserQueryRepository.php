<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\User\Repositories;

use App\Domain\Backoffice\User\Requests\UserIndexRequest;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class UserQueryRepository
{
    public function __construct(private User $model) {}

    public function index(UserIndexRequest $request): LengthAwarePaginator
    {
        $query = $this->model->query()->with(['roles']);

        $query = $this->applySearch($query, $request);
        $query = $this->applyFilters($query, $request);
        $query = $this->applySorting($query, $request);

        return $query->paginate($request->getPerPage());
    }

    private function applyFilters(Builder $query, UserIndexRequest $request): Builder
    {
        if ($request->filled('role_id')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('roles.id', $request->input('role_id'));
            });
        }

        return $query;
    }

    private function applySorting(Builder $query, UserIndexRequest $request): Builder
    {
        $sortBy = $request->getSortBy();
        $order = $request->getOrder();

        $allowedSortColumns = ['id', 'name', 'email', 'created_at', 'updated_at'];

        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'created_at';
        }

        $query->orderBy($sortBy, $order);

        return $query;

    }

    private function applySearch(Builder $query, UserIndexRequest $request): Builder
    {
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    public function findOneById(string $id): ?User
    {
        return $this->model->find($id);
    }

    public function findOneByIdWithRoles(string $id): ?User
    {
        return $this->model->with(['roles'])->find($id);
    }

    public function findOneByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    public function findMany(): Collection
    {
        return $this->model->all();
    }
}
