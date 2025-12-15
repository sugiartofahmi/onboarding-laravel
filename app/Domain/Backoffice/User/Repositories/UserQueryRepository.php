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
