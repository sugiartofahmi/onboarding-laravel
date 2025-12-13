<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Role\Repositories;

use App\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class RoleQueryRepository
{
    public function __construct(private Role $model) {}

    public function index(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->query();

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                  ->orWhere('guard_name', 'like', "%{$filters['search']}%");
            });
        }

        return $query->paginate($perPage);
    }

    public function findOneById(string $id): ?Role
    {
        return $this->model->find($id);
    }

    public function findOneByGuardName(string $guardName): ?Role
    {
        return $this->model->where('guard_name', $guardName)->first();
    }

    public function findMany(): Collection
    {
        return $this->model->all();
    }

    public function findManyByUserId(string $userId): Collection
    {
        return $this->model->whereHas('users', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })->get();
    }

    public function findOneByUserIdAndRoleId(string $userId, string $roleId): ?Role
    {
        return $this->model->whereHas('users', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })->where('id', $roleId)->first();
    }
}
