<?php

declare(strict_types=1);

namespace App\Domain\Backoffice\Permission\Repositories;

use App\Models\Permission;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PermissionQueryRepository
{
    public function __construct(private Permission $model) {}

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

    public function findOneById(string $id): ?Permission
    {
        return $this->model->find($id);
    }

    public function findOneByGuardName(string $guardName): ?Permission
    {
        return $this->model->where('guard_name', $guardName)->first();
    }

    public function findMany(): Collection
    {
        return $this->model->all();
    }

    public function findManyByIds(array $ids): Collection
    {
        return $this->model->whereIn('id', $ids)->get();
    }

    public function findManyByGuardNames(array $guardNames): Collection
    {
        return $this->model->whereIn('guard_name', $guardNames)->get();
    }

    public function findManyByRoleId(string $roleId): Collection
    {
        return $this->model->whereHas('roles', function ($query) use ($roleId) {
            $query->where('roles.id', $roleId);
        })->get();
    }
}
